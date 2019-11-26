<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use Grav\Common\Grav;

/**
 * Class DynamicFormPlugin
 * @package Grav\Plugin
 */
class DynamicFormPlugin extends Plugin
{
    public static function mapPropertyToFormAttribute($info) {
        /**
         * Get the parameters as an ordered array.
         * [0] page_path: path to the page markdown, where we can find the frontmatter and the content.
         * [1] property_path: path to the property inside the page markdown, e.g. "header.title".
         * [2] map_func_path: path to the function that converts the property to a form attribute.
         */
        [$page_path, $property_path, $map_func_path] = $info;
        $page_path = PAGES_DIR . $page_path;

        /**
         * Get the selected property from the selected page in plain php.
         */
        $grav = Grav::instance();
        $pages = $grav->get('pages');
        $page = $pages->get($page_path);
        $page_properties = $page->toArray();
        $selected_property = DynamicFormPlugin::getKey($page_properties, $property_path);

        /**
         * Call the function that converts the property to a form attribute,
         * passing the property as a parameter. The function needs to return the attribute info.
         */
        return call_user_func($map_func_path, $selected_property);
    }

    public static function getKey($arr, $path, $separator = '.') {
        $keys = explode($separator, $path);

        foreach ($keys as $key) {
            $arr = $arr[$key];
        }

        return $arr;
    }
}
