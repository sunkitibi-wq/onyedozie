<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('about', 'about')->name('about');
Route::view('founder', 'founder')->name('founder');
Route::view('vision', 'vision')->name('vision');
Route::view('join', 'join')->name('join');
Route::view('achievements', 'achievements')->name('achievements');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('lgas', 'pages.lgas')->name('lgas');
    Route::view('users', 'pages.users')->name('users');
    Route::view('rbac', 'pages.rbac')->name('rbac');
    Route::view('results', 'pages.results')->name('results');
    Route::view('location-tracking', 'pages.location-tracking')->name('location-tracking');
    Route::view('canvassing-tracker', 'pages.canvassing-tracker')->name('canvassing-tracker');
    Route::view('tasks', 'pages.tasks')->name('tasks');
    Route::view('activity-logs', 'pages.activity-logs')->name('activity-logs');
    Route::view('artisan-console', 'pages.artisan-console')->name('artisan-console');
    Route::view('reports', 'pages.reports')->name('reports');
    Route::view('candidate-dashboard', 'pages.candidate-dashboard')->name('candidate-dashboard');
    Route::view('notifications', 'pages.notifications')->name('notifications');
    Route::view('communications', 'pages.communications')->name('communications');
});

Route::get('share/dashboard/{token}', \App\Livewire\CandidateDashboard::class)->name('candidate.public-share');

require __DIR__.'/settings.php';
