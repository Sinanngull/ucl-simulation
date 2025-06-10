<?php

namespace App\Services;

use App\Models\MatchGame;
use App\Models\Team;

class ChampionshipPredictionService
{
    public function calculate(int $simulationCount = 1000): array
    {
        $teams = Team::all();
        $teamIds = $teams->pluck('id')->all();
        $winCounts = array_fill_keys($teamIds, 0);

        for ($i = 0; $i < $simulationCount; $i++) {
            $points = array_fill_keys($teamIds, 0);


            foreach (MatchGame::all() as $match) {
                $home = $match->home_team_id;
                $away = $match->away_team_id;

                $homePower = $teams->firstWhere('id', $home)->power;
                $awayPower = $teams->firstWhere('id', $away)->power;


                $totalPower = $homePower + $awayPower;
                $chance = rand(1, $totalPower);

                if ($chance <= $homePower) {
                    $points[$home] += 3;
                } elseif ($chance <= $homePower + ($awayPower * 0.2)) {
                    $points[$home] += 1;
                    $points[$away] += 1;
                } else {
                    $points[$away] += 3;
                }
            }


            $maxPoints = max($points);
            $winners = array_keys($points, $maxPoints);

            foreach ($winners as $winnerId) {
                $winCounts[$winnerId] += 1 / count($winners);
            }
        }

        $results = [];
        foreach ($teams as $team) {
            $results[] = [
                'team' => $team->name,
                'chance' => round(($winCounts[$team->id] / $simulationCount) * 100, 2),
            ];
        }

        return $results;
    }
}
