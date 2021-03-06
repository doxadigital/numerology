<?php
namespace App\Orchid\Screens\User;

use App\Models\User;
use App\Orchid\Layouts\Role\RolePermissionLayout;
use App\Orchid\Layouts\User\UserCreditLayout;
use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserPasswordLayout;
use App\Orchid\Layouts\User\UserRoleLayout;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Orchid\Access\UserSwitch;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class UserEditScreen extends Screen
{
    public ?User $user;

    public function query(User $user): iterable
    {
        $user->load(['roles', 'credit']);

        return [
            'user'       => $user,
            'permission' => $user->getStatusPermission(),
        ];
    }

    public function name(): ?string
    {
        return $this->user->exists ? 'Edit User' : 'Create User';
    }

    public function description(): ?string
    {
        return 'Details such as name, email and password';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.systems.users',
        ];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make(__('Impersonate user'))
                ->icon('login')
                ->confirm('You can revert to your original state by logging out.')
                ->method('loginAs')
                ->canSee($this->user->exists && \request()->user()->id !== $this->user->id),

            Button::make(__('Remove'))
                ->icon('trash')
                ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'))
                ->method('remove')
                ->canSee($this->user->exists),

            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [

            Layout::block(UserEditLayout::class)
                ->title(__('Profile Information'))
                ->description(__('Update your account\'s profile information and email address.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

            Layout::block(UserPasswordLayout::class)
                ->title(__('Password'))
                ->description(__('Ensure your account is using a long, random password to stay secure.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

            Layout::block(UserRoleLayout::class)
                ->title(__('Roles'))
                ->description(__('A Role defines a set of tasks a user assigned the role is allowed to perform.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

            Layout::block(UserCreditLayout::class)
                ->title(__('Credit'))
                ->description(__('A User Credit.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

            Layout::block(RolePermissionLayout::class)
                ->title(__('Permissions'))
                ->description(__('Allow the user to perform some actions that are not provided for by his roles'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->user->exists)
                        ->method('save')
                ),

        ];
    }

    public function save(User $user, Request $request): RedirectResponse
    {
        $request->validate([
            'user.email' => [
                'required',
                Rule::unique(User::class, 'email')->ignore($user),
            ],
        ]);

        $permissions = collect($request->get('permissions'))
            ->map(function ($value, $key) {
                return [base64_decode($key) => $value];
            })
            ->collapse()
            ->toArray();

        $userData = $request->get('user');
        if ($user->exists && (string) $userData['password'] === '') {
            // When updating existing user null password means "do not change current password"
            unset($userData['password']);
        } else {
            $userData['password'] = Hash::make($userData['password']);
        }

        $user->fill($userData)
            ->fill(['permissions' => $permissions]);

        $user->birth_date = $userData['birth_date'];
        $user->valid_date = $userData['expiration_date']['start'];
        $user->expired_date = $userData['expiration_date']['end'];
        $user->save();

        $user->replaceRoles($request->input('user.roles'));

        if (collect($request->get('user'))->get('credit', false)) {
            $this->updateCredit($user, $request->get('user')['credit']['point']);
        }

        Toast::info(__('User was saved.'));

        return redirect()->route('platform.systems.users');
    }

    public function updateCredit(User $user, int $credit)
    {
        $user = (new User())->newQuery()->find($user->id);

        if ($user instanceof User) {
            $user->credit->point = $credit;
            $user->credit->save();
        }
    }

    public function remove(User $user): RedirectResponse
    {
        try {
            $user->delete();
            Toast::info(__('User was removed'));
        } catch (Exception $e) {}

        return redirect()->route('platform.systems.users');
    }

    public function loginAs(User $user): RedirectResponse
    {
        UserSwitch::loginAs($user);

        Toast::info(__('You are now impersonating this user'));

        return redirect()->route(config('platform.index'));
    }
}
