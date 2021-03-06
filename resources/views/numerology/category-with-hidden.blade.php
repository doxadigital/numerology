<div class="p-2 w-full sm:max-w-md md:max-w-full mx-auto bg-gray-200 rounded-lg border shadow-md {{ ($year ?? 0 ) == $numerology->getYear() ? 'border-green-400 border-8' : null }}">
    <div class="grid grid-cols-2">
        <div class="flex justify-between {{ (isset($swap) ? null : 'col-start-2') }}">
            <p class="text-xs font-extrabold uppercase px-2 mb-0">
                {{ $numerology->getName() }}
            </p>
            <div class="w-auto text-xs font-bold">
                <small>{{ $numerology->getYear() }}</small>
            </div>
        </div>
    </div>

    <div class="w-full grid grid-cols-2 gap-2">
        <ul class="w-full grid gap-2">
            @foreach($numerology->getTraitCodes() as $i => $trait)
                @php
                    $palace = isset($palaces[$trait]) ? $palaces[$trait][0] : $numerology->getTraits()[$trait];
                @endphp
                @if($i == (isset($swap) ? 0 : 2) || $i == (isset($swap) ? 1 : 3))
                    <li>
                        @include((isset($swap) ? 'numerology.category.trait' : 'numerology.category.hidden-trait'), [
                            'trait' => $palace,
                            'modalName' => \Illuminate\Support\Str::camel($numerology->getName() . $palace . ($i + 1)),
                            'color' => isset($palaces[$trait]) ? $palaces[$trait][2] : null,
                            'backgroundColor' => isset($palaces[$trait]) ? $palaces[$trait][1] : null,
                        ])
                    </li>
                @endif
            @endforeach
        </ul>
        <ul class="w-full grid gap-2">
            @foreach($numerology->getTraitCodes() as $i => $trait)
                @php
                    $palace = isset($palaces[$trait]) ? $palaces[$trait][0] : $numerology->getTraits()[$trait];
                @endphp
                @if($i == (isset($swap) ? 2 : 0) || $i == (isset($swap) ? 3 : 1))
                    <li>
                        @include((isset($swap) ? 'numerology.category.hidden-trait' : 'numerology.category.trait'), [
                            'trait' => $palace,
                            'modalName' => \Illuminate\Support\Str::camel($numerology->getName() . $palace . ($i + 1)),
                            'color' => isset($palaces[$trait]) ? $palaces[$trait][2] : null,
                            'backgroundColor' => isset($palaces[$trait]) ? $palaces[$trait][1] : null,
                        ])
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
