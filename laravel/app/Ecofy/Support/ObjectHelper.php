<?php
namespace App\Ecofy\Support;

class ObjectHelper
{
    public static function checkAssoc($array){
        return  ctype_digit( implode('', array_keys($array) ) );
    }
    /**
     * Creates a stdClass object from array
     */
    public static function createObject($arr)
    {
        return json_decode(json_encode($arr));
    }

    /**
    * Converts object with nested structure into flat object with one level property.
    * The property names follows the dot notation.
    *
    * Opposite of hydrate operation.
    * @param {array} $arr  - the object to traverse and produce dehydrated
    *                        (flattened) object.
    * @param {string} pathPrefix - the prefix (dot notation) for the property names
    * @param {string} $pathSeparator - the character for the dot (separator) by default
    *                              is '.'
    */
    public static function dehydrate($array, $pathPrefix = '', $pathSeparator = '.')
    {
        // Laravel's array_dot($arr);

        $results = [];
        foreach ($array as $key => $value) {
            if (is_array($value) && ! empty($value)) {
                $results = array_merge($results, static::dehydrate($value, $pathPrefix.$key.$pathSeparator, $pathSeparator));
            } else {
                $results[$pathPrefix.$key] = $value;
            }
        }
        return $results;
    }

    /**
     * Converts flat object into nested structure based on dot notation.
     *
     * E.g.
     * {
     *   "foo.bar.baz1": "baz-val1",
     *   "foo.bar.baz2": "baz-val2"
     * }
     * Will return
     * {
     *   "foo": {
     *     "bar": {
     *       "baz1": "baz-val1",
     *       "baz1": "baz-val2"
     *     }
     *   }
     * }
     */
    public static function hydrate ($arr)
    {
        $hydratedObj = [];
        foreach ($arr as $prop => $val) {
            if (strpos($prop, '.') !== false) {
                // create nested property
                ObjectAccessor::set($hydratedObj, $prop, $val);
                //delete obj[prop];
            }
        }
        return $hydratedObj;
    }
}
