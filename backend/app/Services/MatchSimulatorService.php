<?php

namespace App\Services;

use App\Models\MatchGame;
use App\Models\Team;

class MatchSimulatorService
{
    public function simulate(): void
    {
        $matches = MatchGame::where('played', false)->get();

        foreach ($matches as $match) {
            $home = Team::find($match->home_team_id);
            $away = Team::find($match->away_team_id);

            $homeAdvantage = 1.1;
            $homeChance = $home->power * $homeAdvantage;
            $awayChance = $away->power;
            $total = $homeChance + $awayChance;

            $homeGoals = rand(0, 3) * ($homeChance / $total);
            $awayGoals = rand(0, 3) * ($awayChance / $total);

            $homeGoals = round($homeGoals);
            $awayGoals = round($awayGoals);

            $match->update([
                'home_team_score' => $homeGoals,
                'away_team_score' => $awayGoals,
                'played' => true,
            ]);
        }
    }
}
