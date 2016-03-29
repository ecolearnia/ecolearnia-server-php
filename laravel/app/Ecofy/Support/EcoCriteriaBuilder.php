<?php
namespace App\Ecofy\Support;

class EcoCriteriaBuilder
{
    // @todo check for valid operator
    /**
     * logical comparision
     * @param {string} $var -  the name of the property (i.e. column name)
     * @param {string} $op  -  the boolean opeartor
     * @param {mixed}  $rhs - the right-hand-side value or statement to compare
     */
    public static function comparison($var, $op, $rhs)
    {
        return [
            'var' => $var,
            'op' => $op,
            'val' => $rhs
        ];
    }

    public static function equals($var, $rhs)
    {
        return self::comparison($var, '=', $rhs);
    }

    public static function like($var, string $rhs)
    {
        return self::comparison($var, 'like', $rhs);
    }

    public static function between($var, $from, $to)
    {
        return self::node_($var, 'between', [ 'from' => $from, 'to' => $to]);
    }

    public static function in($var, array $args)
    {
        return self::node_($var, 'in', $args);
    }
    public static function notIn($var, array $args)
    {
        return self::node_($var, 'nin', $args);
    }

    /**
     * Disjunction (OR operator)
     */
    public static function disj(array $args)
    {
        return self::node_(null, 'or', $args);
    }

    /**
     * Conjunction (AND operator)
     */
    public static function conj(array $args)
    {
        return self::node_(null, 'and', $args);
    }

    protected static function node_($var, $op, $args)
    {
        $node = [
            'op' => $op,
            'args' => $args
        ];
        if (!empty($var)) {
            $node['var'] = $var;
        }
        return $node;
    }
}
