<div class="p-4 w-full sm:max-w-md md:max-w-full mx-auto bg-gray-800 rounded-lg border shadow-md text-white">
    <div class="flex justify-center mb-4 py-4">
        <h3 class="font-bold text-3xl uppercase" style="color: #98FF47;">
            {{ $numerology->getName() }}
        </h3>
    </div>

    <ul class="w-full grid gap-4">
        @foreach($numerology->getTraitCodes() as $trait)
        <li>
            @include('numerology.category.trait', [
                'trait' => isset($palaces[$trait]) ? $palaces[$trait][0] : $numerology->getTraits()[$trait],
                'color' => isset($palaces[$trait]) ? $palaces[$trait][2] : null,
                'backgroundColor' => isset($palaces[$trait]) ? $palaces[$trait][1] : null,
                'buttonClass' => 'p-3'
            ])
        </li>
        @endforeach
    </ul>
</div>