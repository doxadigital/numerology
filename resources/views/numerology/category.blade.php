<div class="p-2 w-full sm:max-w-md md:max-w-full mx-auto bg-gray-200 rounded-lg border shadow-md {{ ($year ?? 0 ) == $numerology->getYear() ? 'border-green-400 border-8' : null }}">
    <div class="flex justify-between">
        <p class="text-xs font-extrabold uppercase px-2 mb-0">
            {{ $numerology->getName() }}
        </p>
        <div class="w-auto text-xs font-bold">
            <small>{{ $numerology->getYear() }}</small>
        </div>
    </div>

    <ul class="w-full grid gap-1">
        @foreach($numerology->getTraitCodes() as $i => $trait)
            @php
                $palace = isset($palaces[$trait]) ? $palaces[$trait][0] : $numerology->getTraits()[$trait];
            @endphp
            <li>
                @include('numerology.category.trait', [
                    'trait' => $palace,
                    'color' => isset($palaces[$trait]) ? $palaces[$trait][2] : null,
                    'backgroundColor' => isset($palaces[$trait]) ? $palaces[$trait][1] : null,
                    'modalName' => \Illuminate\Support\Str::camel($numerology->getName() . $palace . ($i + 1)),
                ])
            </li>
        @endforeach
    </ul>
</div>
