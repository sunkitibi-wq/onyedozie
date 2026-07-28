<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('about', 'about')->name('about');
Route::view('founder', 'founder')->name('founder');
Route::view('vision', 'vision')->name('vision');
Route::view('join', 'join')->name('join');
Route::view('achievements', 'achievements')->name('achievements');
Route::view('gallery', 'gallery')->name('gallery');
Route::view('video-gallery', 'video-gallery')->name('video-gallery');
Route::view('image-gallery', 'image-gallery')->name('image-gallery');
Route::view('privacy-policy', 'privacy')->name('privacy');
Route::get('updates', \App\Livewire\PublicNews::class)->name('updates');
Route::get('updates/{id}', \App\Livewire\PublicNewsDetail::class)->name('updates.detail');

Route::get('queue-work', function (\Illuminate\Http\Request $request) {
    $secret = env('QUEUE_WEB_KEY');
    if (!$secret || $request->query('key') !== $secret) {
        abort(403, 'Unauthorized');
    }
    
    try {
        \Illuminate\Support\Facades\Artisan::call('queue:work', [
            '--stop-when-empty' => true,
            '--timeout' => 60,
        ]);
        
        return response()->json([
            'status' => 'success',
            'output' => \Illuminate\Support\Facades\Artisan::output(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
})->name('queue.work');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('recruitment-statistics', 'pages.recruitment-statistics')
        ->middleware('role:Super Admin')
        ->name('recruitment-statistics');
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
    Route::view('news', 'pages.news')->name('news');
});

Route::get('share/dashboard/{token}', \App\Livewire\CandidateDashboard::class)->name('candidate.public-share');

require __DIR__.'/settings.php';
