<?php
/**
 * Homerun Jobs plugin for Craft CMS 3.x
 *
 * Get jobs from homerun
 *
 * @link      http://panman.nl
 * @copyright Copyright (c) 2017 PanMan
 */

namespace panman\homerunjobs\twigextensions;

use panman\homerunjobs\HomerunJobs;

use Craft;
use craft\elements\Entry;



/**
 * Twig can be extended in many ways; you can add extra tags, filters, tests, operators,
 * global variables, and functions. You can even extend the parser itself with
 * node visitors.
 *
 * http://twig.sensiolabs.org/doc/advanced.html
 *
 * @author    PanMan
 * @package   HomerunJobs
 * @since     1
 */
class HomerunJobsTwigExtension extends \Twig_Extension
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'HomerunJobs';
    }

    /**
     * Returns an array of Twig filters, used in Twig templates via:
     *
     *      {{ 'something' | someFilter }}
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            // new \Twig_SimpleFilter('someFilter', [$this, 'someInternalFunction']),
        ];
    }

    /**
     * Returns an array of Twig functions, used in Twig templates via:
     *
     *      {% set this = someFunction('something') %}
     *
    * @return array
     */
    public function getFunctions()
    {
        return [
            // new \Twig_SimpleFunction('someFunction', [$this, 'someInternalFunction']),
            new \Twig_SimpleFunction('homerunJobs', [$this, 'getHomerunJobs']),
        ];
    }

    /**
     * Our function called via Twig; it can do anything you want
     *
     * @param null $text
     *
     * @return object
     */
    public function getHomerunJobs($text = null)
    {
        //Get homerun slugs from craft and use that to fire request to homerun and return

        $entries = Entry::find()
            ->section('companies')
            // ->select('companyHomerunSlug') //Somehow doesnt work
            ->all();

        $slugs=[];
        foreach ($entries as $item){
            if ($item->companyHomerunSlug){
                $slugs[]=$item->companyHomerunSlug;
            }
        }

        $baseurl="https://api.homerun.co/v1/tq-jobs?companies=".join(',',$slugs);
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $baseurl,
            CURLOPT_USERAGENT => 'Craft 3 Homerun Plugin',
            CURLOPT_USERPWD => getenv('HOMERUN_APIKEY').':'
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
        return $resp;
    }
}
