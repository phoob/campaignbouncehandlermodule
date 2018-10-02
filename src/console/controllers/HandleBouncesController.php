<?php
/**
 * CampaignBounceHandler module for Craft CMS 3.x
 *
 * Handle bounces for Craft Campaign plugin
 *
 * @link      https://dfo.no
 * @copyright Copyright (c) 2018 Peter Holme Obrestad
 */

namespace modules\campaignbouncehandlermodule\console\controllers;

use modules\campaignbouncehandlermodule\CampaignBounceHandlerModule;

use Craft;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * HandleBounces Command
 *
 * @author    Peter Holme Obrestad
 * @package   CampaignBounceHandlerModule
 * @since     1.0.0
 */
class HandleBouncesController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * Handle campaign-bounce-handler-module/handle-bounces console commands
     *
     * @return mixed
     */
    public function actionIndex()
    {
        CampaignBounceHandlerModule::getInstance()
            ->get('bounceHandler')
            ->handleBounces();
    }

}
