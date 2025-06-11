<?php

namespace App\Services;

use App\Models\MatchGame;
use App\Models\Team;

class LeagueTableService
{
    public function getTable(): array
    {
        $teams = Team::all();
        $pointsTable = [];

        foreach ($teams as $team) {
            $points = 0;

            $homeMatches = MatchGame::where('home_team_id', $team->id)->where('played', true)->get();
            $awayMatches = MatchGame::where('away_team_id', $team->id)->where('played', true)->get();

            foreach ($homeMatches as $match) {
                if ($match->home_team_score > $match->away_team_score) {
                    $points += 3;
                } elseif ($match->home_team_score === $match->away_team_score) {
                    $points += 1;
                }
            }

            foreach ($awayMatches as $match) {
                if ($match->away_team_score > $match->home_team_score) {
                    $points += 3;
                } elseif ($match->away_team_score === $match->home_team_score) {
                    $points += 1;
                }
            }

            $pointsTable[] = [
                'team' => $team->name,
                'points' => $points,
            ];
        }

        usort($pointsTable, fn($a, $b) => $b['points'] <=> $a['points']);

        return $pointsTable;
    }
}
