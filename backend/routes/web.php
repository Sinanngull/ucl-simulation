<?php

use Illuminate\Support\Facades\Route;
use App\Services\FixtureGeneratorService;

Route::get('/generate-fixtures', function (FixtureGeneratorService $fixtureService) {
    $fixtureService->generate();
    return 'Fixtures generated!';
});


?>

