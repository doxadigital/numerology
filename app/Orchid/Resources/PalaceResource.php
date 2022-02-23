<?php

namespace App\Orchid\Resources;

use App\Models\Palace;
use Illuminate\Database\Eloquent\Model;
use Orchid\Crud\Resource;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;

class PalaceResource extends Resource
{
    public static $model = Palace::class;

    public function fields(): array
    {
        return [
            Input::make('code')->required()->type('number')->title('Code'),
            Input::make('name')->required()->title('Name'),
            Input::make('description')->required()->title('Description'),
            Input::make('font_color')->required()->title('Font Color (Hexadecimal)'),
            Input::make('background_color')->required()->title('Background Color (Hexadecimal)'),
        ];
    }

    public function rules(Model $model): array
    {
        $unique = "unique:palaces,code";
        if ($model instanceof Palace) $unique .= ",$model->id";
        return [
            'code' => ['required', 'numeric', 'min:0', 'max:255', $unique],
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'description' => ['required', 'string', 'min:1', 'max:255'],
            'font_color' => ['required', 'string', 'min:1', 'max:7', 'starts_with:#'],
            'background_color' => ['required', 'string', 'min:1', 'max:7', 'starts_with:#'],
        ];
    }

    public function columns(): array
    {
        return [
            TD::make('id'),
            TD::make('name')->render(function ($model) {
                return "<span style=\"color: $model->background_color;\">$model->name</span>";
            }),
            TD::make('created_at', 'Date of creation')
                ->render(function ($model) {
                    return $model->created_at->toDateTimeString();
                }),
        ];
    }

    public function legend(): array
    {
        return [
            Sight::make('id'),
            Sight::make('name')->render(function ($model) {
                return "<span style=\"color: $model->color;\">$model->name</span>";
            }),
            Sight::make('description'),
            Sight::make('color'),
            Sight::make('created_at', 'Date of creation')
                ->render(function ($model) {
                    return $model->created_at->toDateTimeString();
                }),
            Sight::make('updated_at', 'Update date')
                ->render(function ($model) {
                    return $model->updated_at->toDateTimeString();
                }),
        ];
    }

    public function filters(): array
    {
        return [];
    }
}