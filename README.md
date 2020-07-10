# Current See plugin for Craft CMS 3.x

Plugin to generate externally accessible feed of versions

![Screenshot](resources/img/plugin-logo.png)

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require ournameismud/current-see

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Current See.

## Current See Overview

This plugin is designed to scan your Craft build to see what updates are needed and generate an XML feed for those that are out of date. This can ideally be used with services like [IFTTT](https://ifttt.com/) eg to post to Slack or send email notifications.

## Configuring Current See

Before using you will need to generate an Api Key for use by the plugin.  
Go to Plugin > Settings and save an Api Key. One will automatically be generated for you or add your own.

## Using Current See

There are two controller actions currently employed by the plugin. One to scan the Craft installation and the other to provide the feed.  

### Scan Craft

Because checking Craft versions requires several curl requests to the [Craftnet API](https://docs.api.craftcms.com/) this service is set to run as background tasks. 

The best way to trigger this controller action would be to set a CRON job to call it periodically. This is done by hitting the following action: `http://[domain.com]/actions/current-see/default/check?key=[XXX]`. Obviously you want to replace `[domain.com]` with your domain name and `[XXX]` with the API key saved in your plugin settings. If there is a discrepancy with the local plugin version and that reported by the Craftnet API then a record of this will be saved locally.

### Feed Output

You can monitor the output of the feed by using the following controller action: `http://[domain.com]]/actions/current-see/default/feed?key=[XXX]`. This will be populated with those plugins (and the Craft CMS) that require updating.

## Current See Roadmap

Some things to do, and ideas for potential features:

* Release it

Brought to you by [@cole007](https://ournameismud.co.uk/)  
Logo by [Andrejs Kirma @ Noun Project](https://thenounproject.com/andrejs/collection/non-edible-products-filled/)