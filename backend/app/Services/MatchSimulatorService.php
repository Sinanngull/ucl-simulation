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
            $this->simulateMatch($match);
        }
    }

    public function simulateNextWeek(): array
    {
        $teamCount = Team::count();
        $matchesPerWeek = $teamCount / 2;

        $matches = MatchGame::where('played', false)
            ->orderBy('week')
            ->orderBy('id')
            ->limit($matchesPerWeek)
            ->get();

        if ($matches->isEmpty()) {
            return ['message' => 'All weeks have been simulated.'];
        }

        foreach ($matches as $match) {
            $this->simulateMatch($match);
        }

        return [
            'message' => 'One week of matches has been simulated.',
            'matches' => $matches,
        ];
    }

    private function simulateMatch(MatchGame $match): void
    {
        $home = Team::find($match->home_team_id);
        $away = Team::find($match->away_team_id);

        $homeAdvantage = 1.1;
        $homeChance = $home->power * $homeAdvantage;
        $awayChance = $away->power;
        $total = $homeChance + $awayChance;

        $homeGoals = rand(0, 3) * ($homeChance / $total);
        $awayGoals = rand(0, 3) * ($awayChance / $total);

        $match->update([
            'home_team_score' => round($homeGoals),
            'away_team_score' => round($awayGoals),
            'played' => true,
        ]);
    }
}
