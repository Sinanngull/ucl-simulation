<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\MatchGame;

class MatchSeeder extends Seeder
{
    public function run(): void
    {
        $teams = Team::all();

        if ($teams->count() < 2) {
            $this->command->warn('Yeterli takım yok. En az 2 takım gerekli.');
            return;
        }

        $matches = [];
        $week = 1;

        for ($i = 0; $i < $teams->count(); $i++) {
            for ($j = $i + 1; $j < $teams->count(); $j++) {
                $matches[] = [
                    'home_team_id' => $teams[$i]->id,
                    'away_team_id' => $teams[$j]->id,
                    'week' => $week,
                    'played' => false,
                    'home_team_score' => null,
                    'away_team_score' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $week++;
                if ($week > 7) {
                    $week = 1;
                }
            }
        }

        MatchGame::insert($matches);

        $this->command->info(count($matches) . ' maç başarıyla eklendi.');
    }
}
