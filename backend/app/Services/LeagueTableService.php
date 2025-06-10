<?php

namespace App\Services;

use App\Models\Team;
use App\Models\MatchGame;

class LeagueTableService
{
    public function getStandings(): array
    {
        $teams = Team::all()->keyBy('id');
        $standings = [];

        foreach ($teams as $team) {
            $standings[$team->id] = [
                'team' => $team->name,
                'played' => 0,
                'won' => 0,
                'draw' => 0,
                'lost' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'goal_difference' => 0,
                'points' => 0,
            ];
        }

        $matches = MatchGame::where('played', true)->get();

        foreach ($matches as $match) {
            $home = &$standings[$match->home_team_id];
            $away = &$standings[$match->away_team_id];

            $home['played']++;
            $away['played']++;

            $home['goals_for'] += $match->home_team_score;
            $home['goals_against'] += $match->away_team_score;

            $away['goals_for'] += $match->away_team_score;
            $away['goals_against'] += $match->home_team_score;

            if ($match->home_team_score > $match->away_team_score) {
                $home['won']++;
                $away['lost']++;
                $home['points'] += 3;
            } elseif ($match->home_team_score < $match->away_team_score) {
                $away['won']++;
                $home['lost']++;
                $away['points'] += 3;
            } else {
                $home['draw']++;
                $away['draw']++;
                $home['points'] += 1;
                $away['points'] += 1;
            }
        }

        foreach ($standings as &$team) {
            $team['goal_difference'] = $team['goals_for'] - $team['goals_against'];
        }

        usort($standings, function ($a, $b) {
            return [$b['points'], $b['goal_difference'], $b['goals_for']]
                <=> [$a['points'], $a['goal_difference'], $a['goals_for']];
        });

        return $standings;
    }
}
