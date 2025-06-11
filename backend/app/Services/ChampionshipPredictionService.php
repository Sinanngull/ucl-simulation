<?php

namespace App\Services;

use App\Models\Team;
use App\Models\MatchGame;

class ChampionshipPredictionService
{
    public function calculate(): array
    {
        $teams = Team::all();
        $points = [];

        foreach ($teams as $team) {
            $playedMatches = MatchGame::where(function ($q) use ($team) {
                $q->where('home_team_id', $team->id)->orWhere('away_team_id', $team->id);
            })->where('played', true)->get();

            $teamPoints = 0;

            foreach ($playedMatches as $match) {
                $isHome = $match->home_team_id === $team->id;
                $teamGoals = $isHome ? $match->home_team_score : $match->away_team_score;
                $opponentGoals = $isHome ? $match->away_team_score : $match->home_team_score;

                if ($teamGoals > $opponentGoals) {
                    $teamPoints += 3;
                } elseif ($teamGoals === $opponentGoals) {
                    $teamPoints += 1;
                }
            }

            $points[$team->name] = $teamPoints;
        }

        $totalPoints = array_sum($points) ?: 1; // 0'a bölünme hatasını engelle
        $prediction = [];

        foreach ($points as $teamName => $teamPoint) {
            $prediction[] = [
                'team' => $teamName,
                'chance' => round(($teamPoint / $totalPoints) * 100),
            ];
        }

        return $prediction;
    }
}
