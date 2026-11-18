<?php

return [
    // Albanian keyword sets
    'albanian' => [
        'main' => [
            'platformë imobiliare',
            'platformë pasurish të paluajtshme',
            'agjenci imobiliare online',
            'njoftime imobiliare',
            'reklama imobiliare',
            'imokam'
        ],

        'buy' => [
            'banesë për shitje',
            'apartament për shitje',
            'shtëpi për shitje',
            'blej banesë',
            'blerje prone',
            'investim në pasuri të paluajtshme'
        ],

        'rent' => [
            'banesë për qira',
            'apartament për qira',
            'shtëpi për qira',
            'qira afatshkurtër',
            'qira afatgjatë',
            'qira nga pronari',
            'qira e lirë'
        ],

        'landlord' => [
            'jep me qira',
            'listo pronën për qira',
            'si të jepni pronën me qira',
            'rent out property' // keep English fallback
        ],

        'search_phrases' => [
            'si të gjej banesë',
            'si të gjej apartament',
            'banesë në shitje',
            'banese ne shitje', // without diacritics for search
            'banesë për qira',
            'banese per qira'
        ],
    ],

    // Strong city list (Albania & Kosovo examples) used for permutations
    'cities' => [
        'Tiranë',
        'Tirana',
        'Prishtinë',
        'Prishtina',
        'Durrës',
        'Vlorë',
        'Shkodër',
        'Elbasan',
        'Gjakovë',
        'Pejë'
    ],

    // A reasonable default limit for generated keyword permutations
    'default_limit' => 20,
];