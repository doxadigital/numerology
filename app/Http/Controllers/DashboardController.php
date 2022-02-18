<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Objects\StaticNumerology;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateInterval;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $carbon = Carbon::parse($this->getBirthDate($request));

        return view('dashboard.index', [
            'birth_date' => $carbon->format('m/d/Y'),
            'numerology' => new StaticNumerology(
                $carbon->format('d'),
                $carbon->format('m'),
                $carbon->format('Y')
            ),
            'year_numerology' => new StaticNumerology(
                $carbon->format('d'),
                $carbon->format('m'),
                $carbon->format('Y'),
                $request->get('year', now()->format('Y'))
            ),
            'months' => $this->getMonths()
        ]);
    }

    private function getBirthDate(Request $request): string
    {
        $user = auth()->user();
        $date = now()->toDateString();

        if ($user instanceof User && $user->birth_date) $date = $user->birth_date;
        if (collect($request->all())->filter()->has('birth_date'))
            $date = Carbon::parse($request->get('birth_date'))->toDateString();

        return $date;
    }

    private function getMonths(): array
    {
        $month = [];
        $range = CarbonPeriod::create(now()->startOfYear(), now()->endOfYear());
        $range->setDateInterval(DateInterval::createFromDateString('1 month'));

        foreach ($range as $item) {
            $month[] = $item->format('F');
        }

        return $month;
    }
}
