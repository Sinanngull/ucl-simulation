<?php

use Illuminate\Support\Facades\Route;
use App\Services\MatchSimulatorService;

Route::get('/simulate-matches', function (MatchSimulatorService $simulator) {
    $simulator->simulate();
    return 'Matches simulated!';
});



?>

