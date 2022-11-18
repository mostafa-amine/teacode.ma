<?php

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

    Route::get('/_assets/{type?}', 'PageController@getAssets');

    Route::group(['prefix' => 'admin'], function () {

        Route::redirect('/', 'admin/calendar');
        Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('/login', 'Auth\LoginController@login');

        Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

        Route::group(['middleware' => 'auth'], function() {
            // Events
            Route::get('/events', 'EventController@getEvents')->name('events.index');
            Route::post('/events', 'EventController@updateEvent')->name('events.update');
            Route::get('/calendar', 'EventController@calendar');
            // Contributors
            Route::get('/contributors', 'ContributorController@getContributors')->name('contributors.get');
            Route::post('/contributors', 'ContributorController@updateContributors')->name('contributors.update');
            // Auth
            Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

            // Tools
            Route::get('/export-db', 'ToolController@export_db');
        });
    });

    Route::get('/', 'HomeController@home');

    // Pages
    Route::get('/p/{page}', 'PageController@getPage');

    // SiteMap
    Route::get('/sitemap', 'SitemapController@sitemap');
    Route::get('/generateSitemap', 'SitemapController@generateSitemap');

    // External
    Route::get('/{link}', 'GotoController@goto');

    Route::group(['prefix' => 'api'], function () {
        Route::get('/events', 'ApiController@getEvents');
        Route::get('/events/next', 'ApiController@getNextEvent');
    });

    Route::any('/{var}', 'HomeController@home')->where('var', '.*');
});
