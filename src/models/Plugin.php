<?php
/**
 * Current See plugin for Craft CMS 3.x
 *
 * Plugin to generate externally accessible feed of versions
 *
 * @link      https://ournameismud.co.uk/
 * @copyright Copyright (c) 2020 @cole007
 */

namespace ournameismud\currentsee\models;

use ournameismud\currentsee\CurrentSee;

use Craft;
use craft\base\Model;

/**
 * @author    @cole007
 * @package   CurrentSee
 * @since     1.0.0
 */
class Plugin extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $name = '',
        $handle = '',
        $namespace = '',
        $current = '',
        $latest = '';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','handle','namespace','current','latest'], 'string']
        ];
    }
}
