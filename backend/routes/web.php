<?php

use Illuminate\Support\Facades\Route;
use App\Services\MatchSimulatorService;
use App\Services\LeagueTableService;
use App\Services\ChampionshipPredictionService;
use App\Services\WeekByWeekSimulatorService;



Route::get('/simulate-matches', function (MatchSimulatorService $matchSimulator) {
    $matchSimulator->simulate();
    return 'Matches simulated!';
});

Route::get('/league-table', function (LeagueTableService $leagueTableService) {
    return response()->json($leagueTableService->getTable());
});

Route::get('/championship-predictions', function (ChampionshipPredictionService $predictionService) {
    return response()->json($predictionService->calculate());
});

Route::get('/play-week', function (WeekByWeekSimulatorService $weekSimulator) {
    return response()->json($weekSimulator->playNextWeek());
});
