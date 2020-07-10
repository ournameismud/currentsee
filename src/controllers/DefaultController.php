<?php
/**
 * Current See plugin for Craft CMS 3.x
 *
 * Plugin to generate externally accessible feed of versions
 *
 * @link      https://ournameismud.co.uk/
 * @copyright Copyright (c) 2020 @cole007
 */

namespace ournameismud\currentsee\controllers;

use ournameismud\currentsee\CurrentSee;
use ournameismud\currentsee\jobs\CurrentSeeTask;
use ournameismud\currentsee\records\Plugin AS PluginRecord;

use yii\web\Response;
use DOMDocument;

use Craft;
use craft\web\Controller;

/**
 * @author    @cole007
 * @package   CurrentSee
 * @since     1.0.0
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['check','feed'];

    // Public Methods
    // =========================================================================

    /**
     * @return mixed
     */
    public function actionFeed()
    {
        $request = Craft::$app->getRequest();
        $keyRemote = $request->getParam('key');
        
        $keyLocal = CurrentSee::$plugin->getSettings()->apiKey;
        // validation service?
        if (!$keyRemote OR $keyRemote !== $keyLocal) {
            Craft::dd('failed to authenticate');
        }

        $records = PluginRecord::find()->all();
        
        Craft::$app->response->format = Response::FORMAT_RAW;
        $headers = Craft::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $plugins = $dom->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'plugins');
        $plugins->setAttributeNS(
            'http://www.w3.org/2000/xmlns/',
            'xmlns:xhtml',
            'http://www.w3.org/1999/xhtml'
        );
        $dom->appendChild( $plugins );

        foreach ($records as $record) {
            // $loc = $this->getUrl($item['uri'], $item['siteId']);
            // if ($loc === null) {
            //     continue;
            // }

            $plugin = $dom->createElement('plugin');
            $plugins->appendChild($plugin);
            $plugin->appendChild($dom->createElement('package', $record->namespace));
            $plugin->appendChild($dom->createElement('local', $record->current));
            $plugin->appendChild($dom->createElement('remote', $record->latest));
            $date = new \DateTime( $record->dateUpdated );
            $plugin->appendChild($dom->createElement('updated', $date->format('c')));
            // $url->appendChild($dom->createElement('priority', $item['priority']));
            // $url->appendChild($dom->createElement('changefreq', $item['changefreq']));
            // $dateUpdated = strtotime($item['dateUpdated']);
            // $url->appendChild($dom->createElement('lastmod', date('Y-m-d\TH:i:sP', $dateUpdated)));
            // if ($item['alternateLinkCount'] > 1) {
            //     $alternateLinks = $this->_createAlternateSectionQuery($item['elementId'])->all();
            //     if (count($alternateLinks) > 0) {
            //         foreach ($alternateLinks as $alternateItem) {
            //             $alternateLoc = $this->getUrl($alternateItem['uri'], $alternateItem['siteId']);
            //             if ($alternateLoc === null) {
            //                 continue;
            //             }

            //             $alternateLink = $dom->createElementNS('http://www.w3.org/1999/xhtml', 'xhtml:link');
            //             $alternateLink->setAttribute('rel', 'alternate');
            //             $alternateLink->setAttribute('hreflang', strtolower($alternateItem['siteLanguate']));
            //             $alternateLink->setAttribute('href', $alternateLoc);
            //             $url->appendChild($alternateLink);
            //         }
            //     }
            // }
        }

        // foreach ($this->_createEntryCategoryQuery()->all() as $item) {
        //     $loc = $this->getUrl($item['uri'], $item['siteId']);
        //     if ($loc === null) {
        //         continue;
        //     }

        //     $url = $dom->createElement('url');
        //     $urlset->appendChild($url);
        //     $url->appendChild($dom->createElement('loc', $loc));
        //     $url->appendChild($dom->createElement('priority', $item['priority']));
        //     $url->appendChild($dom->createElement('changefreq', $item['changefreq']));
        //     $dateUpdated = strtotime($item['dateUpdated']);
        //     $url->appendChild($dom->createElement('lastmod', date('Y-m-d\TH:i:sP', $dateUpdated)));


        // }
        return $dom->saveXML();


    }
    
    public function actionCheck()
    {
        $request = Craft::$app->getRequest();
        $keyRemote = $request->getParam('key');
        
        $keyLocal = CurrentSee::$plugin->getSettings()->apiKey;
        // validation service?
        if (!$keyRemote OR $keyRemote !== $keyLocal) {
            Craft::dd('failed to authenticate');
        }
        // check craft version here
        // https://api.craftcms.com/v1/package/putyourlightson/craft-blitz
        $id = Craft::$app->queue->push( new CurrentSeeTask() );
        return 'Running CRON';        
    }
    
}
