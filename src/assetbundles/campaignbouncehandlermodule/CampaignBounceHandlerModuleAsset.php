<?php
/**
 * CampaignBounceHandler module for Craft CMS 3.x
 *
 * Handle bounces for Craft Campaign plugin
 *
 * @link      https://dfo.no
 * @copyright Copyright (c) 2018 Peter Holme Obrestad
 */

namespace modules\campaignbouncehandlermodule\assetbundles\CampaignBounceHandlerModule;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Peter Holme Obrestad
 * @package   CampaignBounceHandlerModule
 * @since     1.0.0
 */
class CampaignBounceHandlerModuleAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@modules/campaignbouncehandlermodule/assetbundles/campaignbouncehandlermodule/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/CampaignBounceHandlerModule.js',
        ];

        $this->css = [
            'css/CampaignBounceHandlerModule.css',
        ];

        parent::init();
    }
}
