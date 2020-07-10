<?php
/**
 * Current See plugin for Craft CMS 3.x
 *
 * Plugin to generate externally accessible feed of versions
 *
 * @link      https://ournameismud.co.uk/
 * @copyright Copyright (c) 2020 @cole007
 */

namespace ournameismud\currentsee\records;

use ournameismud\currentsee\CurrentSee;

use Craft;
use craft\db\ActiveRecord;

/**
 * @author    @cole007
 * @package   CurrentSee
 * @since     1.0.0
 */
class Plugin extends ActiveRecord
{
    // Public Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currentsee_plugin}}';
    }
}
