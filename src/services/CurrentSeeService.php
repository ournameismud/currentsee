<?php
/**
 * Current See plugin for Craft CMS 3.x
 *
 * Plugin to generate externally accessible feed of versions
 *
 * @link      https://ournameismud.co.uk/
 * @copyright Copyright (c) 2020 @cole007
 */

namespace ournameismud\currentsee\services;

use ournameismud\currentsee\CurrentSee;
use ournameismud\currentsee\models\Plugin AS PluginModel;
use ournameismud\currentsee\records\Plugin AS PluginRecord;

use Craft;
use craft\base\Component;

/**
 * @author    @cole007
 * @package   CurrentSee
 * @since     1.0.0
 */
class CurrentSeeService extends Component
{
    // Public Methods
    // =========================================================================

    /*
     * @return mixed
     */
    public function _log($message, $context) {
        $file = Craft::getAlias('@storage/logs/MM-'.$context.'.log');
        $log = date('Y-m-d H:i:s').' '.$message."\n";
        \craft\helpers\FileHelper::writeToFile($file, $log, ['append' => true]);
    }

    public function compPlugin( $plugin ) {
        
        $endpoint = 'https://api.craftcms.com/v1/package/' . $plugin->packageName;
        $response = $this->fetch( $endpoint );
        if (property_exists($response,'latestRelease') && isset($response->latestRelease)) {
            $latestVersion = $response->latestRelease;
            $comp = version_compare($plugin->version, $latestVersion->version);
            if( $comp ) {
                // save as record
                $record = PluginRecord::find()->where( array('namespace' => $plugin->packageName) )->one();
                if (!$record) {
                    $record = new PluginRecord;
                }
                $record->setAttribute('name', $plugin->name);
                $record->setAttribute('handle', $plugin->id);
                $record->setAttribute('namespace', $plugin->packageName);
                $record->setAttribute('current', $plugin->version);
                $record->setAttribute('latest', $latestVersion->version);
                $record->save();                
            } else {

                PluginRecord::model()->deleteAll('user_id = :user_id', array(':user_id' => $user->id));
                $record = PluginRecord::find()->where(['namespace' => $plugin->packageName])->one();
                $record->delete();

            }
        }
    }


    public function fetch($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $response = json_decode(curl_exec($curl));
        curl_close($curl);
        return $response;
    }

}
