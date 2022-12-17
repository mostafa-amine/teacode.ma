<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ContributorController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GotoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 6months => max_age = 60 * 60 * 24 * 183
Route::middleware('cache.headers:public;max_age=15811200;etag')->group(function () {

    // \Auth::routes();

    Route::get('/_assets/{type?}', [PageController::class , 'getAssets']);

    Route::group(['prefix' => 'admin'], function () {

        Route::redirect('/', 'admin/calendar');
        Route::get('/login', [LoginController::class , 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class , 'login']);

        Route::get('/password/reset', [ForgotPasswordController::class , 'showLinkRequestForm'])->name('password.request');
        Route::post('/password/email', [ForgotPasswordController::class , 'sendResetLinkEmail'])->name('password.email');
        Route::get('/password/reset/{token}', [ResetPasswordController::class , 'showResetForm'])->name('password.reset');
        Route::post('/password/reset', [ResetPasswordController::class , 'reset'])->name('password.update');

        Route::group(['middleware' => 'auth'], function() {
            // Events
            Route::get('/events', [EventController::class , 'getEvents'])->name('events.index');
            Route::post('/events', [EventController::class , 'updateEvent'])->name('events.update');
            Route::get('/calendar', [EventController::class , 'calender']);
            // Contributors
            Route::get('/contributors', [ContributorController::class , 'getContributors'])->name('contributors.get');
            Route::post('/contributors', [ContributorController::class , 'updateContributors'])->name('contributors.update');
            // Auth
            Route::post('/logout', [LoginController::class , 'logout'])->name('logout');

            // Tools
            Route::get('/export-db', [ToolController::class , 'export_db']);
        });
    });

    Route::get('/', [HomeController::class , 'home']);

    // Pages
    Route::get('/p/{page}', [PageController::class , 'getPage']);

    // SiteMap
    Route::get('/sitemap', [SitemapController::class , 'sitemap']);
    Route::get('/generateSitemap', [SitemapController::class , 'generateSitemap']);

    // External
    Route::get('/{link}', [GotoController::class , 'goto']);

    Route::group(['prefix' => 'api'], function () {
        Route::get('/events', [ApiController::class , 'getEvents']);
        Route::get('/events/next', [ApiController::class , 'getNextEvent']);
    });

    Route::any('/{var}', [HomeController::class , 'home'])->where('var', '.*');
});
