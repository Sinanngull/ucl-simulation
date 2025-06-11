<?php

namespace App\Services;

use App\Models\Team;
use App\Models\MatchGame;

class FixtureGeneratorService
{
    public function generate(): void
    {
        MatchGame::truncate();

        $teams = Team::all();
        $teamCount = $teams->count();

        if ($teamCount < 2) {
            return; // En az 2 takım gerekli
        }

        $isOdd = $teamCount % 2 !== 0;

        if ($isOdd) {
            $teams->push((object)['id' => null, 'name' => 'BYE']);
            $teamCount++;
        }

        $teamIds = $teams->pluck('id')->toArray();
        $weeks = $teamCount - 1;
        $matchesPerWeek = $teamCount / 2;

        for ($week = 1; $week <= $weeks; $week++) {
            for ($i = 0; $i < $matchesPerWeek; $i++) {
                $home = $teamIds[$i];
                $away = $teamIds[$teamCount - 1 - $i];

                if (!is_null($home) && !is_null($away)) {
                    MatchGame::create([
                        'home_team_id' => $home,
                        'away_team_id' => $away,
                        'week' => $week,
                        'played' => false,
                        'home_team_score' => null,
                        'away_team_score' => null,
                    ]);
                }
            }

            $firstTeam = array_shift($teamIds);
            $lastTeam = array_pop($teamIds);
            array_unshift($teamIds, $firstTeam);
            array_splice($teamIds, 1, 0, $lastTeam);
        }
    }
}
