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
            new \Twig_SimpleFilter('someFilter', [$this, 'someInternalFunction']),
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
     * @return string
     */
    public function someInternalFunction($text = null)
    {
        $result = $text . " in the way";

        return $result;
    }

    public function getHomerunJobs($text = null)
    {
        //Get homerun slugs from craft and use that to fire request to homerun and return

        // {% set homerunSlugs = [] %}
        // {% for company in craft.entries.section('companies') %}
        //     {% if company.companyHomerunSlug is not empty %}
        //         {% set homerunSlugs = homerunSlugs|merge([company.companyHomerunSlug]) %}
        //     {% endif %}
        // {% endfor %}


        $entries = Entry::find()
            ->section('companies')
            // ->limit(10)
             // ->select('companyHomerunSlug') //Somehow doesnt work
            // ->column();
            ->all();
        $slugs=[];

        foreach ($entries as $item){
            if ($item->companyHomerunSlug){
                $slugs[]=$item->companyHomerunSlug;
            }
        }

        // return phpinfo();

        $baseurl="https://api.homerun.co/v1/tq-jobs?companies=".join(',',$slugs);// $_GET['companies'];
        //Call with: http://localhost:8002/api/homerun?companies=fashiontradecom,patientjourneyapp
        $apiKey = getenv('HOMERUN_APIKEY');

        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $baseurl,
            CURLOPT_USERAGENT => 'TQ Site',
            CURLOPT_USERPWD => $apiKey.':'
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
        return $resp;
        // return json_encode($resp);
        // $result = $text . " in the way";

        // return $result;
    }
}
