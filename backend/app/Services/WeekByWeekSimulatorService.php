<?php

namespace App\Services;

use App\Models\MatchGame;

class WeekByWeekSimulatorService
{
    const MATCHES_PER_WEEK = 4;

    public function playNextWeek(): array
    {
        $matches = MatchGame::where('played', false)
            ->orderBy('id')
            ->limit(self::MATCHES_PER_WEEK)
            ->get();

        if ($matches->isEmpty()) {
            return ['message' => 'All weeks have been simulated.'];
        }

        foreach ($matches as $match) {
            $homePower = $match->homeTeam->power;
            $awayPower = $match->awayTeam->power;

            $homeScore = rand(0, round($homePower / 10));
            $awayScore = rand(0, round($awayPower / 10));

            $match->update([
                'home_team_score' => $homeScore,
                'away_team_score' => $awayScore,
                'played' => true,
            ]);
        }

        return [
            'message' => 'One week of matches has been simulated.',
            'matches' => $matches,
        ];
    }


    public function groupedByWeek(): array
    {
        return MatchGame::with('homeTeam', 'awayTeam')
            ->orderBy('week')
            ->orderBy('id')
            ->get()
            ->groupBy('week')
            ->map(function ($matches, $week) {
                return [
                    'week' => (int) $week,
                    'matches' => $matches->map(function ($match) {
                        return [
                            'home_team' => $match->homeTeam->name,
                            'away_team' => $match->awayTeam->name,
                        ];
                    })->values(),
                ];
            })
            ->values()
            ->toArray();
    }

}
