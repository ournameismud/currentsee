<?php
/**
 * Current See plugin for Craft CMS 3.x
 *
 * Plugin to generate externally accessible feed of versions
 *
 * @link      https://ournameismud.co.uk/
 * @copyright Copyright (c) 2020 @cole007
 */

namespace ournameismud\currentsee\jobs;

use ournameismud\currentsee\CurrentSee;

use Craft;
use craft\queue\BaseJob;

/**
 * @author    @cole007
 * @package   CurrentSee
 * @since     1.0.0
 */
class CurrentSeeTask extends BaseJob
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function execute($queue)
    {
        // get all plugins
        $plugins = Craft::$app->getPlugins()->getAllPlugins();
        $total = count($plugins);
        $i = 0;
        // loop through plugins
        foreach ($plugins as $plugin) {
            $this->setProgress($queue, ($i + 1) / $total);
            // check plugin details
            $response = CurrentSee::getInstance()->currentSeeService->compPlugin( $plugin );
            // if ($response) $data[] = $response;
            $i++;
        }
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function defaultDescription(): string
    {
        return 'Indexing plugins';
    }
}
