<?php
/**
 * Yii Application Config
 *
 * Edit this file at your own risk!
 *
 * The array returned by this file will get merged with
 * vendor/craftcms/cms/src/config/app/main.php and [web|console].php, when
 * Craft's bootstrap script is defining the configuration for the entire
 * application.
 *
 * You can define custom modules and system components, and even override the
 * built-in system components.
 */
return [
    'modules' => [
        'campaign-bounce-handler-module' => [
            'class' => \modules\campaignbouncehandlermodule\CampaignBounceHandlerModule::class,
            'components' => [
                'bounceHandler' => [
                    'class' => 'modules\campaignbouncehandlermodule\services\BounceHandler',
                ],
            ],
            'params' => [
                'mailhost' => getenv('CRAFTENV_BOUNCE_MAILHOST'),
                'mailboxUserName' => getenv('CRAFTENV_BOUNCE_USERNAME'),
                'mailboxPassword' => getenv('CRAFTENV_BOUNCE_PASSWORD'),
                'testMode' => getenv('CRAFTENV_BOUNCE_TESTMODE') ?? false,
                'disableDelete' => true,
                'moveSoft' => true,
                'moveHard' => true,
              ],
        ],
    ],
    'bootstrap' => ['campaign-bounce-handler-module'],
];
