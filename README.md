# Dynamic Form Plugin

This is a grav plugin to generate form fields according to content set in other pages.

## Usage

1. copy (fork, clone, etc.) this package to the plugin folder;
2. enable the plugin, if not already enabled;
3. create another plugin to map the content to have the field format (see example below);
4. use it in form (see example below).

## Example of third party plugin integration:

This plugin needs a function that receives the content and returns the data fields. For example, you can receive a list of categories, each one with competitors, and map the categories to radio fields and the competitors to radio options. It can be a theme function or it can be done inside another plugin. This is what the example below does.

```php
namespace Grav\Plugin;

use Grav\Common\Plugin;

/**
 * Class SitePlugin
 * @package Grav\Plugin
 */
class SitePlugin extends Plugin
{
  /**
   * $categories = [
   *   [
   *      name => 'First category',
   *      competitors => ['Ahlan', 'Bob'],
   *   ],
   *   [
   *      name => 'Second category',
   *      competitors => ['Diana', 'Francisco'],
   *   ]
   * ]
   *
   * Obs: I didn't test the example below, consider it as a pseudo code.
   * It should generate two radio fields, each one with two radiobox.
   * */
    public static function mapCompetitorsToVotingRadioField($categories) {
        $fields = array_reduce($categories, function($arr, $category){
          return array_merge(
            $arr,
            [
              $category['name'] => [
                'name' => $category['name'],
                'label' => $category['name'],
                'type' => 'radio',
                'default' => -1,
                'options' => $category['competitors'],
              ],
            ],
          );
        }, []);

        return $fields;
    }
}

```

## Example of usage in a form:

After creating the function (in my case a static method), you should pass the function name as a string to the `data-fields@` arguments:
- [0] page_path: path to the page markdown, where we can find the frontmatter and the content.
- [1] property_path: path to the property inside the page markdown, e.g. "header.title".
- [2] map_func_path: path to the function that converts the property to a form attribute.


```yaml
fields:
  - name: fieldset
    type: fieldset
    label: Fieldset
    data-fields@:
      - '\Grav\Plugin\DynamicFormPlugin::mapPropertyToFormAttribute'
      - - 01.home/06._nomeados
        - header.categories
        - '\Grav\Plugin\SitePlugin::mapCompetitorsToVotingRadioField'
```
