# Homerun Jobs plugin for Craft CMS 3.x

Get jobs from homerun

![Screenshot](resources/img/plugin-logo.png)

## Requirements

This plugin requires Craft CMS 3.0.0-beta.19 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require PanMan/homerun-jobs

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Homerun Jobs.

## Homerun Jobs Overview

-Insert text here-

## Configuring Homerun Jobs
It looks for an enviroment variable named `HOMERUN_APIKEY`

## Using Homerun Jobs
In a twig template, place `<script>
var window.homerun= {{homerunJobs() | raw }};
</script>`
This will create a JS var with the jobs

## Homerun Jobs Roadmap

Some things to do, and ideas for potential features:
* Add some caching
* Release it

Brought to you by [PanMan](http://panman.nl)
