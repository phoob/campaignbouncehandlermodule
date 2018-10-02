<?php
/**
 * CampaignBounceHandler module for Craft CMS 3.x
 *
 * Handle bounces for Craft Campaign plugin
 *
 * @link      https://dfo.no
 * @copyright Copyright (c) 2018 Peter Holme Obrestad
 */

namespace modules\campaignbouncehandlermodule;

use modules\campaignbouncehandlermodule\assetbundles\campaignbouncehandlermodule\CampaignBounceHandlerModuleAsset;
use modules\campaignbouncehandlermodule\services\BounceHandler as BounceHandlerService;

use Craft;
use craft\events\RegisterTemplateRootsEvent;
use craft\events\TemplateEvent;
use craft\i18n\PhpMessageSource;
use craft\web\View;
use craft\console\Application as ConsoleApplication;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\base\Module;

/**
 * Class CampaignBounceHandlerModule
 *
 * @author    Peter Holme Obrestad
 * @package   CampaignBounceHandlerModule
 * @since     1.0.0
 *
 * @property  BounceHandlerService $bounceHandler
 */
class CampaignBounceHandlerModule extends Module
{
    // Static Properties
    // =========================================================================

    /**
     * @var CampaignBounceHandlerModule
     */
    public static $instance;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, array $config = [])
    {
        Craft::setAlias('@modules/campaignbouncehandlermodule', $this->getBasePath());
        $this->controllerNamespace = 'modules\campaignbouncehandlermodule\controllers';

        // Translation category
        $i18n = Craft::$app->getI18n();
        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (!isset($i18n->translations[$id]) && !isset($i18n->translations[$id.'*'])) {
            $i18n->translations[$id] = [
                'class' => PhpMessageSource::class,
                'sourceLanguage' => 'en-US',
                'basePath' => '@modules/campaignbouncehandlermodule/translations',
                'forceTranslation' => true,
                'allowOverrides' => true,
            ];
        }

        // Base template directory
        Event::on(View::class, View::EVENT_REGISTER_CP_TEMPLATE_ROOTS, function (RegisterTemplateRootsEvent $e) {
            if (is_dir($baseDir = $this->getBasePath().DIRECTORY_SEPARATOR.'templates')) {
                $e->roots[$this->id] = $baseDir;
            }
        });

        // Set this as the global instance of this module class
        static::setInstance($this);

        parent::__construct($id, $parent, $config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$instance = $this;

        if (Craft::$app->getRequest()->getIsCpRequest()) {
            Event::on(
                View::class,
                View::EVENT_BEFORE_RENDER_TEMPLATE,
                function (TemplateEvent $event) {
                    try {
                        Craft::$app->getView()->registerAssetBundle(CampaignBounceHandlerModuleAsset::class);
                    } catch (InvalidConfigException $e) {
                        Craft::error(
                            'Error registering AssetBundle - '.$e->getMessage(),
                            __METHOD__
                        );
                    }
                }
            );
        }

        if (Craft::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'modules\campaignbouncehandlermodule\console\controllers';
        }

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['siteActionTrigger1'] = 'modules/campaign-bounce-handler-module/handle-bounces';
            }
        );

        Craft::info(
            Craft::t(
                'campaign-bounce-handler-module',
                '{name} module loaded',
                ['name' => 'CampaignBounceHandler']
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================
}
