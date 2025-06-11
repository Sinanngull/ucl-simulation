<?php

use Illuminate\Support\Facades\Route;
use App\Models\Team;
use App\Models\MatchGame;
use App\Http\Controllers\SimulationController;
use App\Services\MatchSimulatorService;
use App\Services\LeagueTableService;
use App\Services\ChampionshipPredictionService;
use App\Services\WeekByWeekSimulatorService;
use App\Services\FixtureGeneratorService;



Route::get('/teams', fn () => Team::all());

Route::post('/generate-fixtures', function () {
    app(FixtureGeneratorService::class)->generate();
    return response()->json(['message' => 'Fixtures generated.']);
});

Route::get('/all-fixtures', function () {
    return MatchGame::with('homeTeam', 'awayTeam')
        ->orderBy('week')
        ->orderBy('id')
        ->get();
});

Route::get('/week-by-week', function () {
    return response()->json(
        app(WeekByWeekSimulatorService::class)->groupedByWeek()
    );
});

Route::get('/simulate-matches', function (MatchSimulatorService $simulator) {
    $simulator->simulate();
    return response()->json(['message' => 'All matches simulated.']);
});

Route::get('/simulate-week', function (MatchSimulatorService $simulator) {
    return response()->json($simulator->simulateNextWeek());
});

Route::get('/league-table', function (LeagueTableService $service) {
    return response()->json($service->getTable());
});

Route::get('/championship-predictions', function (ChampionshipPredictionService $service) {
    return response()->json($service->calculate());
});

Route::get('/next-week-matches', function () {
    $nextWeek = MatchGame::where('played', false)->min('week');

    if (is_null($nextWeek)) {
        return response()->json([
            'week' => null,
            'matches' => [],
        ]);
    }

    $matches = MatchGame::with('homeTeam', 'awayTeam')
        ->where('week', $nextWeek)
        ->get();

    return response()->json([
        'week' => $nextWeek,
        'matches' => $matches,
    ]);
});

Route::post('/reset', [SimulationController::class, 'reset']);

Route::get('/matches', function () {
    return MatchGame::with('homeTeam', 'awayTeam')
        ->orderBy('week')
        ->orderBy('id')
        ->get();
});
