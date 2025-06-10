<?php

namespace App\Services;

use App\Models\Team;
use App\Models\MatchGame;

class LeagueTableService
{
    public function getTable(): array
    {
        $teams = Team::all();
        $table = [];

        foreach ($teams as $team) {
            $playedMatches = MatchGame::where(function ($q) use ($team) {
                $q->where('home_team_id', $team->id)
                    ->orWhere('away_team_id', $team->id);
            })->where('played', true)->get();

            $points = 0;
            $goalsFor = 0;
            $goalsAgainst = 0;

            foreach ($playedMatches as $match) {
                $isHome = $match->home_team_id === $team->id;
                $teamGoals = $isHome ? $match->home_team_score : $match->away_team_score;
                $opponentGoals = $isHome ? $match->away_team_score : $match->home_team_score;

                $goalsFor += $teamGoals;
                $goalsAgainst += $opponentGoals;

                if ($teamGoals > $opponentGoals) {
                    $points += 3;
                } elseif ($teamGoals === $opponentGoals) {
                    $points += 1;
                }
            }

            $table[] = [
                'team' => $team->name,
                'played' => $playedMatches->count(),
                'points' => $points,
                'goals_for' => $goalsFor,
                'goals_against' => $goalsAgainst,
                'goal_difference' => $goalsFor - $goalsAgainst,
            ];
        }

        usort($table, function ($a, $b) {
            return $b['points'] <=> $a['points'] ?: ($b['goal_difference'] <=> $a['goal_difference']);
        });

        return $table;
    }
}
