<?php
/**
 * CampaignBounceHandler module for Craft CMS 3.x
 *
 * Handle bounces for Craft Campaign plugin
 *
 * @link      https://dfo.no
 * @copyright Copyright (c) 2018 Peter Holme Obrestad
 */

namespace modules\campaignbouncehandlermodule\services;

use modules\campaignbouncehandlermodule\CampaignBounceHandlerModule;

use Craft;
use craft\base\Component;

use BounceMailHandler\BounceMailHandler;
use putyourlightson\campaign\Campaign;
use putyourlightson\campaign\models\ContactCampaignModel;
use putyourlightson\campaign\records\ContactCampaignRecord;
use putyourlightson\campaign\controllers\WebhookController;

/**
 * @author    Peter Holme Obrestad
 * @package   CampaignBounceHandlerModule
 * @since     1.0.0
 */
class BounceHandler extends Component
{
    private $module;
    
    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();

        $this->module = CampaignBounceHandlerModule::getInstance();
    }

    /*
     * @return mixed
     */
    public function handleBounces()
    {
        $bmh = new BounceMailHandler();
        $bmh->actionFunction = [$this, 'callbackAction'];
        $bmh->verbose = BounceMailHandler::VERBOSE_QUIET;

        // Put options in params app.php to override the defaults in BounceMailHandler\BounceMailHandler
        foreach( $this->module->params as $k => $v) $bmh->$k = $v;
        
        $bmh->openMailbox();
        $bmh->processMailbox();

        return true;
    }

    public function callbackAction($msgnum, $bounceType, $email, $subject, $xheader, $remove, $ruleNo = false, $ruleCat = false, $totalFetched = 0, $body = '', $headerFull = '', $bodyFull = '')
    {
        // Extract sendout id from message body
        preg_match("/" . WebhookController::HEADER_NAME . ": (?<sid>\S\\*)/", $bodyFull, $matches);
        $sid = $matches['sid'] ?? null;

        if ($sid === null) {
            return ['success' => false, 'error' => Craft::t('campaign', 'Sendout ID not found.')];
        }

        $contact = Campaign::$plugin->contacts->getContactByEmail($email);
        if ($contact === null) {
            return ['success' => false, 'error' => Craft::t('campaign', 'Contact not found.')];
        }
        
        $sendout = Campaign::$plugin->sendouts->getSendoutBySid($sid);
        if ($sendout === null) {
            return ['success' => false, 'error' => Craft::t('campaign', 'Sendout not found.')];
        }

        /** @var ContactCampaignRecord $contactCampaignRecord */
        $contactCampaignRecord = ContactCampaignRecord::findOne([
            'contactId' => $contact->id,
            'sendoutId' => $sendout->id,
        ]);
        if ($contactCampaignRecord === null) {
            return ['success' => false, 'error' => Craft::t('campaign', 'Contact campaign record not found.')];
        }

        /** @var ContactCampaignModel $contactCampaign */
        $contactCampaign = ContactCampaignModel::populateModel($contactCampaignRecord, false);

        $mailingList = $contactCampaign->getMailingList();
        
        if ($bounceType == 'hard') {
            Campaign::$plugin->webhook->bounce($contact, $mailingList, $sendout);
        }

        return ['success' => true];
    }

}
