<?php
namespace App\Ecofy\Support;

class ObjectAccessor
{
    /**
     * Gets the object's property value using dot notation.
     * E.g. given object:
     * $obj = ['foo' => ['bar' => 'mydata']]
     * ObjectAccessor::get($obj, 'foo.bar')
     * will return 'mydata'.
     */
    public static function get($obj, $propertyPath, $default = null)
    {
        return ObjectAccessor::getFromMixed($obj, $propertyPath, $default);
    }

    public static function getFromMixed($obj, $propertyPath, $default = null)
    {
        $propPathArr = explode('.', $propertyPath);
        $currNode = $obj;
        foreach($propPathArr as $prop) {
            if (is_object($currNode) && property_exists($currNode, $prop)) {
                $currNode = $currNode->$prop;
            } else if (is_array($currNode) && array_key_exists($prop, $currNode)) {
                $currNode = $currNode[$prop];
            } else {
                $currNode = $default;
                break;
            }
        }
        return $currNode;
    }

    /**
     * deprecated, use the method above
     */
    public static function getFromObject($obj, $propertyPath, $default = null)
    {
        $propPathArr = explode('.', $propertyPath);
        $currNode = $obj;
        foreach($propPathArr as $prop) {
            if (property_exists($currNode, $prop)) {
                $currNode = $currNode->$prop;
            } else {
                $currNode = $default;
                break;
            }
        }
        return $currNode;
    }

    /**
     * This only works for arrays
     */
    public static function set(&$array, $propertyPath, $value)
    {
        if (is_null($propertyPath)) {
            return $array = $value;
        }
        $keys = explode('.', $propertyPath);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (! isset($array[$key]) || ! is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;
        return $array;
    }
}
