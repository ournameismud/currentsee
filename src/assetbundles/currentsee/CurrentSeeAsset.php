<?php
/**
 * Current See plugin for Craft CMS 3.x
 *
 * Plugin to generate externally accessible feed of versions
 *
 * @link      https://ournameismud.co.uk/
 * @copyright Copyright (c) 2020 @cole007
 */

namespace ournameismud\currentsee\assetbundles\currentsee;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    @cole007
 * @package   CurrentSee
 * @since     1.0.0
 */
class CurrentSeeAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@ournameismud/currentsee/assetbundles/currentsee/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/CurrentSee.js',
        ];

        $this->css = [
            'css/CurrentSee.css',
        ];

        parent::init();
    }
}
