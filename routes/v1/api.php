<?php
use App\rZeBot\sexodomeKernel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['domain' => 'accounts.'.sexodomeKernel::getMainPlataformDomain()], function () {
    Route::get('verify/{token}', 'Auth\LoginController@verify')->name('verify');

    Route::post("/fetch/{site_id}", '\Sexodome\Shared\Infrastructure\Rest\Api\FetchFeedPage@__invoke')->name('fetch');
    Route::post('/ajax/updateSiteSEO/{site_id}', '\Sexodome\Shared\Infrastructure\Rest\Api\UpdateSiteSeoPage@__invoke')->name('updateSiteSEO');
    Route::post('/site/{site_id}/order_categories/', '\Sexodome\Shared\Infrastructure\Rest\Api\SaveOrderCategoriesPage@__invoke')->name('orderCategories');
    Route::post('/ajax/savePopunder/{site_id}', '\Sexodome\Shared\Infrastructure\Rest\Api\SavePopunderPage@__invoke')->name('ajaxSavePopunder');
    Route::post('/ajaxSaveCronJob', '\Sexodome\Shared\Infrastructure\Rest\Api\SaveCronjobPage@__invoke')->name('ajaxSaveCronJob');
    Route::post( '/ajax/category/unlock/{category_translation_id}', '\Sexodome\Shared\Infrastructure\Rest\Api\CategoryUnlockPage@__invoke')->name('categoryUnlock');
    Route::post( '/ajax/category/tags/{category_id}', '\Sexodome\Shared\Infrastructure\Rest\Api\SaveCategoryTagsPage@__invoke')->name('categoryTags');
    Route::post( '/saveSceneTranslation/{scene_id}', '\Sexodome\Shared\Infrastructure\Rest\Api\SaveSceneTranslationPage@__invoke')->name('saveSceneTranslation');
    Route::post( '/admin/saveTagTranslation/{tag_id}', '\Sexodome\Shared\Infrastructure\Rest\Api\SaveTagTranslationPage@__invoke')->name('saveTagTranslation');
    Route::post( '/admin/saveCategoryTranslation/{scene_id}', '\Sexodome\Shared\Infrastructure\Rest\Api\SaveCategoryTranslationPage@__invoke')->name('saveCategoryTranslation');
    Route::post( '/admin/tag/create/{site_id}', '\Sexodome\Shared\Infrastructure\Rest\Api\CreateTagPage@__invoke')->name('createTag');
    Route::post( '/admin/addSite/', '\Sexodome\Shared\Infrastructure\Rest\Api\CreateSitePage@__invoke')->name('addSite');
    Route::post( '/check_domain/', '\Sexodome\Shared\Infrastructure\Rest\Api\CheckDomainPage@__invoke')->name('checkDomain');
    Route::post( '/uploadCategoryThumbnail/{category_id}', '\Sexodome\Shared\Infrastructure\Rest\Api\UpdateCategoryThumbnailPage@__invoke')->name('uploadCategoryThumbnail');
    Route::post( '/updateGoogleData/{site_id}', '\Sexodome\Shared\Infrastructure\Rest\Api\UpdateGoogleDataPage@__invoke')->name('updateGoogleData');
    Route::post( '/updateIframeData/{site_id}', '\Sexodome\Shared\Infrastructure\Rest\Api\UpdateIframeDataPage@__invoke')->name('updateIframeData');
    Route::post( '/updateLogo/{site_id}', '\Sexodome\Shared\Infrastructure\Rest\Api\UpdateLogoPage@__invoke')->name('updateLogo');
    Route::post( '/updateColors/{site_id}', '\Sexodome\Shared\Infrastructure\Rest\Api\UpdateColorsPage@__invoke')->name('updateColors');

    Route::delete('/deleteCronJob/{cronjob_id}', '\Sexodome\Shared\Infrastructure\Rest\Api\DeleteCronjobPage@__invoke')->name('deleteCronJob');
    Route::delete('/ajax/deletePopunder/{popunder_id}', '\Sexodome\Shared\Infrastructure\Rest\Api\DeletePopunderPage@__invoke')->name('ajaxDeletePopunder');
    Route::delete('/ajax/category/delete/{category_id}', '\Sexodome\Shared\Infrastructure\Rest\Api\DeleteCategoryPage@__invoke')->name('ajaxDeleteCategory');
    Route::delete('/ajax/tag/delete/{tag_id}', '\Sexodome\Shared\Infrastructure\Rest\Api\DeleteTagPage@__invoke')->name('ajaxDeleteTag');
    Route::delete('/ajax/scene/delete/{scene_id}', '\Sexodome\Shared\Infrastructure\Rest\Api\DeleteScenePage@__invoke')->name('ajaxDeleteScene');
    Route::delete('/delete/{site_id}/', '\Sexodome\Shared\Infrastructure\Rest\Api\DeleteSitePage@__invoke')->name('deleteSite');
    //Route::match(['get', 'post'], '/admin/translateTag/{tag_id}', 'AdminController@translateTag')->name('translateTag');

    Auth::routes();
});
