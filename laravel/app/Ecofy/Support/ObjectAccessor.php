<?php
namespace App\Ecofy\Support;

class ObjectAccessor
{
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

}
