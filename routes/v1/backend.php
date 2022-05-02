<?php
use App\rZeBot\sexodomeKernel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['domain' => 'accounts.'.sexodomeKernel::getMainPlataformDomain()], function () {
    Route::get("/", '\Sexodome\Shared\Infrastructure\Rest\Backend\HomePage@__invoke')->name('home');
    Route::post('/admin/category/create/{site_id}', '\Sexodome\Shared\Infrastructure\Rest\Backend\CreateCategoryPage@__invoke')->name('createCategory');
    Route::get('/admin/addSite/', '\Sexodome\Shared\Infrastructure\Rest\Backend\CreateSitePage@__invoke')->name('addSite');
    Route::get('/admin/tag/create/{site_id}', '\Sexodome\Shared\Infrastructure\Rest\Backend\CreateTagPage@__invoke')->name('createTag');
    Route::get('/ajax/category/tags/{category_id}', '\Sexodome\Shared\Infrastructure\Rest\Backend\GetCategoryTagsPage@__invoke')->name('categoryTags');
    Route::get('/ajax/category/thumbs/{category_id}', '\Sexodome\Shared\Infrastructure\Rest\Backend\GetCategoryThumbnailsPage@__invoke')->name('categoryThumbs');
    Route::get('/ajax/cronjobs/{site_id}', '\Sexodome\Shared\Infrastructure\Rest\Backend\GetCronjobsPage@__invoke')->name('ajaxCronJobs');
    Route::get('/site/{site_id}/order_categories/', '\Sexodome\Shared\Infrastructure\Rest\Backend\GetOrderCategoriesPage@__invoke')->name('orderCategories');
    Route::get('/ajax/popunders/{site_id}', '\Sexodome\Shared\Infrastructure\Rest\Backend\GetPopundersPage@__invoke')->name('ajaxPopunders');
    Route::get('/ajax/scene/thumbs/{site_id}', '\Sexodome\Shared\Infrastructure\Rest\Backend\GetSceneThumbnailsPage@__invoke')->name('sceneThumbs');
    Route::get('/site/categories/{site_id}', '\Sexodome\Shared\Infrastructure\Rest\Backend\GetSiteCategoriesPage@__invoke')->name('ajaxSiteCategories');
    Route::get('/site/{site_id}', '\Sexodome\Shared\Infrastructure\Rest\Backend\GetSitePage@__invoke')->name('site');
    Route::get('/site/pornstars/{site_id}', '\Sexodome\Shared\Infrastructure\Rest\Backend\GetSitePornstarsPage@__invoke')->name('ajaxSitePornstars');
    Route::get('/scenes/{site_id}', '\Sexodome\Shared\Infrastructure\Rest\Backend\GetSiteScenesPage@__invoke')->name('content');
    Route::get('/sites', '\Sexodome\Shared\Infrastructure\Rest\Backend\GetSitesPage@__invoke')->name('sites');
    Route::get('/site/tags/{site_id}', '\Sexodome\Shared\Infrastructure\Rest\Backend\GetSiteTagsPage@__invoke')->name('ajaxSiteTags');
    Route::get('/ajax/preview/{scene_id}', '\Sexodome\Shared\Infrastructure\Rest\Backend\ScenePreviewPage@__invoke')->name('scenePreview');
    Route::get('unverified', '\Sexodome\Shared\Infrastructure\Rest\Backend\UnverifiedPage@__invoke')->name('unverified');
    Route::get('welcome', '\Sexodome\Shared\Infrastructure\Rest\Backend\WelcomePage@__invoke')->name('welcome');
    Route::get('/admin/category/create/{site_id}', '\Sexodome\Shared\Infrastructure\Rest\Backend\CreateCategoryPage@__invoke')->name('createCategory');
    //Route::match(['get', 'post'], '/admin/translateTag/{tag_id}', 'AdminController@translateTag')->name('translateTag');
    Auth::routes();
});
