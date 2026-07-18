<?php

use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

$routes = function (): void {
    Route::view('/', 'pages.home')->name('home');
    Route::view('/fitur', 'pages.features')->name('features');
    Route::view('/harga', 'pages.pricing')->name('pricing');
    Route::view('/kontak', 'pages.contact')->name('contact');
    Route::view('/privasi', 'pages.privacy')->name('privacy');

    Route::post('/kontak', [LeadController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('contact.store');
};

if (config('marketing.domain')) {
    Route::domain(config('marketing.domain'))->group($routes);
} else {
    $routes();
}
