<?php
/**
 * CampaignBounceHandler module for Craft CMS 3.x
 *
 * Handle bounces for Craft Campaign plugin
 *
 * @link      https://dfo.no
 * @copyright Copyright (c) 2018 Peter Holme Obrestad
 */

namespace modules\campaignbouncehandlermodule\controllers;

use modules\campaignbouncehandlermodule\CampaignBounceHandlerModule;

use Craft;
use craft\web\Controller;

/**
 * @author    Peter Holme Obrestad
 * @package   CampaignBounceHandlerModule
 * @since     1.0.0
 */
class HandleBouncesController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = []; // ['index'];

    // Public Methods
    // =========================================================================

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        return CampaignBounceHandlerModule::getInstance()
            ->get('bounceHandler')
            ->handleBounces();
    }

}
