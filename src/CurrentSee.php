<?php
/**
 * Current See plugin for Craft CMS 3.x
 *
 * Plugin to generate externally accessible feed of versions
 *
 * @link      https://ournameismud.co.uk/
 * @copyright Copyright (c) 2020 @cole007
 */

namespace ournameismud\currentsee;

use ournameismud\currentsee\services\CurrentSeeService as CurrentSeeServiceService;
use ournameismud\currentsee\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\services\Security;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;

/**
 * Class CurrentSee
 *
 * @author    @cole007
 * @package   CurrentSee
 * @since     1.0.0
 *
 * @property  CurrentSeeServiceService $currentSeeService
 */
class CurrentSee extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var CurrentSee
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    /**
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * @var bool
     */
    public $hasCpSection = false;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['siteActionTrigger1'] = 'current-see/default';
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['cpActionTrigger1'] = 'current-see/default/do-something';
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'current-see',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        $settings = $this->getSettings();
        if ($settings->apiKey == '') {
            $settings->apiKey = Craft::$app->getSecurity()->generateRandomString(32);
        }
        // Craft::dd($settings);
        return Craft::$app->view->renderTemplate(
            'current-see/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
