<?php

use App\Services\FideliteService;

return [
    [
        'name' => 'envoyer-codes-fidelite',
        'expression' => '00 10 * * 1', 
        'callback' => 'App\Console\Commands\FideliteCommand@handle',
        'timezone' => 'Africa/Casablanca',
        'without_overlapping' => true,
    ],
];