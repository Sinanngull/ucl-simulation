<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamSeeder extends Seeder
{

    public function run(): void
    {
        $teams = [
            ['name' => 'Real Madrid', 'power' => 90],
            ['name' => 'Manchester City', 'power' => 88],
            ['name' => 'Bayern Munich', 'power' => 85],
            ['name' => 'Inter Milan', 'power' => 83],
            ['name' => 'Arsenal', 'power' => 82],
            ['name' => 'Barcelona', 'power' => 87],
            ['name' => 'Paris Saint-Germain', 'power' => 84],
            ['name' => 'Juventus', 'power' => 80],
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }
    }
}
