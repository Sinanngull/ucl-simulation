<?php

namespace App\Services;

use App\Models\Team;
use App\Models\MatchGame;

class FixtureGeneratorService
{
    public function generate(): void
    {
        $teams = Team::all();

        foreach ($teams as $i => $teamA) {
            for ($j = $i + 1; $j < count($teams); $j++) {
                $teamB = $teams[$j];

                MatchGame::create([
                    'home_team_id' => $teamA->id,
                    'away_team_id' => $teamB->id,
                    'home_team_score' => null,
                    'away_team_score' => null,
                    'played' => false,
                ]);

                MatchGame::create([
                    'home_team_id' => $teamB->id,
                    'away_team_id' => $teamA->id,
                    'home_team_score' => null,
                    'away_team_score' => null,
                    'played' => false,
                ]);
            }
        }
    }
}
?>
