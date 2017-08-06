<?php

namespace App\Providers;


use App\Services\Admin\categoryUnlockService;
use App\Services\Admin\checkDomainService;
use App\Services\Admin\importScenesService;
use App\Services\Admin\saveOrderCategoriesService;
use App\Services\Admin\saveSiteColorsService;
use App\Services\Admin\saveSiteConfigService;
use App\Services\Admin\saveSiteGoogleUAService;
use App\Services\Admin\saveSiteIframeService;
use App\Services\Admin\showCategoryTagsService;
use App\Services\Admin\showCategoryThumbsService;
use App\Services\Admin\showOrderCategoriesService;
use App\Services\Admin\showScenePreviewService;
use App\Services\Admin\showSceneThumbsService;
use App\Services\Admin\showSiteCategoriesService;
use App\Services\Admin\showSiteCronjobsService;
use App\Services\Admin\showSitePornstarsService;
use App\Services\Admin\showSiteTagsService;
use App\Services\Admin\uploadCategoryThumbnailService;
use App\Services\Admin\uploadSiteLogoService;
use App\Services\Model\addCategoryService;
use App\Services\Model\addCronjobService;
use App\Services\Model\addPopunderService;
use App\Services\Model\addSiteService;
use App\Services\Model\addTagService;
use App\Services\Model\deleteCategoryService;
use App\Services\Model\deleteCronJobService;
use App\Services\Model\deletePopunderService;
use App\Services\Model\deleteSceneService;
use App\Services\Model\deleteSiteService;
use App\Services\Model\deleteTagService;
use App\Services\Model\saveCategoryTagsService;
use App\Services\Model\saveCategoryTranslationService;
use App\Services\Admin\getSiteScenesService;
use App\Services\Admin\getSiteService;
use App\Services\Admin\unverifiedUserService;
use App\Services\Admin\getSitesService;
use App\Services\Model\saveSceneTranslationService;
use App\Services\Model\saveTagTranslationService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

use App\rZeBot\sexodomeKernel;
use App\Services\Front\getCategoriesService;
use App\Services\Front\getSearchService;
use App\Services\Front\getCategoryService;
use App\Services\Front\getPornstarsService;
use App\Services\Front\getPornstarService;
use App\Services\Front\getVideoService;
use App\Services\Front\getSceneIframeService;
use App\Services\Front\getSiteAdsService;
use App\Services\Front\getSitemapService;
use App\Services\Front\runOutService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // sexodomeKernel instance
        App::instance('sexodomeKernel', new sexodomeKernel());

        // Front mapping services
        $this->app->bind('getCategoriesService', function ($app) { return new getCategoriesService(); });
        $this->app->bind('getSearchService', function ($app) { return new getSearchService(); });
        $this->app->bind('getCategoryService', function ($app) { return new getCategoryService(); });
        $this->app->bind('getPornstarsService', function ($app) { return new getPornstarsService(); });
        $this->app->bind('getPornstarService', function ($app) { return new getPornstarService(); });
        $this->app->bind('getVideoService', function ($app) { return new getVideoService(); });
        $this->app->bind('getSceneIframeService', function ($app) { return new getSceneIframeService(); });
        $this->app->bind('getSiteAdsService', function ($app) { return new getSiteAdsService(); });
        $this->app->bind('runOutService', function ($app) { return new runOutService(); });
        $this->app->bind('getSitemapService', function ($app) { return new getSitemapService(); });

        // Admin mapping services
        $this->app->bind('AdminGetSitesService', function ($app) { return new getSitesService(); });
        $this->app->bind('AdminUnverifiedUserService', function ($app) { return new unverifiedUserService(); });
        $this->app->bind('AdminGetSiteService', function ($app) { return new getSiteService(); });
        $this->app->bind('AdminGetSiteScenesService', function ($app) { return new getSiteScenesService(); });
        $this->app->bind('AdminSaveTagTranslationService', function ($app) { return new saveTagTranslationService(); });
        $this->app->bind('AdminSaveCategoryTranslationService', function ($app) { return new saveCategoryTranslationService(); });
        $this->app->bind('AdminSaveSceneTranslationService', function ($app) { return new saveSceneTranslationService(); });
        $this->app->bind('AdminShowScenePreviewService', function ($app) { return new showScenePreviewService(); });
        $this->app->bind('AdminImportScenesService', function ($app) { return new importScenesService(); });
        $this->app->bind('AdminShowSceneThumbsService', function ($app) { return new showSceneThumbsService(); });
        $this->app->bind('AdminAddSiteService', function ($app) { return new addSiteService(); });
        $this->app->bind('AdminCheckDomainService', function ($app) { return new checkDomainService(); });
        $this->app->bind('AdminDeleteCronJobService', function ($app) { return new deleteCronJobService(); });
        $this->app->bind('AdminAddCronjobService', function ($app) { return new addCronjobService(); });
        $this->app->bind('AdminShowSiteCronjobsService', function ($app) { return new showSiteCronjobsService(); });
        $this->app->bind('AdminDeleteSiteService', function ($app) { return new deleteSiteService(); });
        $this->app->bind('AdminShowCategoryThumbsService', function ($app) { return new showCategoryThumbsService(); });
        $this->app->bind('AdminCategoryUnlockService', function ($app) { return new categoryUnlockService(); });
        $this->app->bind('AdminShowOrderCategoriesService', function ($app) { return new showOrderCategoriesService(); });
        $this->app->bind('AdminSaveOrderCategoriesService', function ($app) { return new saveOrderCategoriesService(); });
        $this->app->bind('AdminSaveCategoryTagsService', function ($app) { return new saveCategoryTagsService(); });
        $this->app->bind('AdminShowCategoryTagsService', function ($app) { return new showCategoryTagsService(); });
        $this->app->bind('AdminAddCategoryService', function ($app) { return new addCategoryService(); });
        $this->app->bind('AdminAddTagService', function ($app) { return new addTagService(); });
        $this->app->bind('AdminSaveSiteConfigService', function ($app) { return new saveSiteConfigService(); });
        $this->app->bind('AdminSaveSiteGoogleUAService', function ($app) { return new saveSiteGoogleUAService(); });
        $this->app->bind('AdminSaveSiteIframeService', function ($app) { return new saveSiteIframeService(); });
        $this->app->bind('AdminUploadCategoryThumbnailService', function ($app) { return new uploadCategoryThumbnailService(); });
        $this->app->bind('AdminUploadSiteLogoService', function ($app) { return new uploadSiteLogoService(); });
        $this->app->bind('AdminSaveSiteColorsService', function ($app) { return new saveSiteColorsService(); });
        $this->app->bind('AdminDeleteCategoryService', function ($app) { return new deleteCategoryService(); });
        $this->app->bind('AdminDeleteTagService', function ($app) { return new deleteTagService(); });
        $this->app->bind('AdminDeleteSceneService', function ($app) { return new deleteSceneService(); });
        $this->app->bind('AdminAddPopunderService', function ($app) { return new addPopunderService(); });
        $this->app->bind('AdminDeletePopunderService', function ($app) { return new deletePopunderService(); });
        $this->app->bind('AdminShowSiteTagsService', function ($app) { return new showSiteTagsService(); });
        $this->app->bind('AdminShowSiteCategoriesService', function ($app) { return new showSiteCategoriesService(); });
        $this->app->bind('AdminShowSitePornstarsService', function ($app) { return new showSitePornstarsService(); });


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
