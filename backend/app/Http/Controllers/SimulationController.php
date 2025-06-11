<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;

class SimulationController extends Controller
{
    public function reset(): JsonResponse
    {
        Artisan::call('migrate:fresh --seed');

        return response()->json(['message' => 'Veritabanı sıfırlandı ve seed edildi.']);
    }

}


