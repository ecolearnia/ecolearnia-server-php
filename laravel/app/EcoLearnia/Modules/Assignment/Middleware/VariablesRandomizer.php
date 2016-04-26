<?php
namespace App\EcoLearnia\Modules\Assignment\Middleware;

use App\Ecofy\Support\ObjectAccessor;
use App\Ecofy\Support\ObjectHelper;

class VariablesRandomizer //  implements ContentInstantiationMiddleware
{
    public function __construct() {

    }

    /**
     * Returns a new object with intantiated variables
     * @param {player.ContentDefinition} $content
     * @param {player.assingment.AssignmentContext} $params
     */
    public function apply($content, $params = [])
    {
        $multiplier = 1;
        $baseNum = 0;
        $difficultyFactor = 0;
        if (!empty($params)) {
            //multiplier = (params.stats.score) ? params.stats.score : 1;
            $corrects = ObjectAccessor::get($params, 'stats.corrects', 0);
            $incorrects = ObjectAccessor::get($params, 'stats.incorrects', 0);
            $difficultyFactor = ObjectAccessor::get($params, 'stats.score', 0.0);
            $baseNum = $corrects - $incorrects;
        }
        $contentClone = unserialize(serialize($content));
        //var_dump($contentClone);
        $vars = ObjectAccessor::get($contentClone, 'variableDeclarations');
        foreach($vars as $varName => $varDecl)
        {
            // recalc for each variables
            $tmpMultiplier = $multiplier;
            $tmpBaseNum = $baseNum;
            if ( array_key_exists('variability', $varDecl) && $varDecl->variability === 'strict') {
                // Constant 1x multiplier
                $tmpMultiplier = 1;
                $tmpBaseNum = 0;
            }
            $newVal = $varDecl->value;
            if (strtolower($varDecl->baseType) == 'number') {
                $minVal = ObjectAccessor::get($vars, $varName . '.minVal', 0); //($vars[$varName]['minVal']) ? $vars[$varName]['minVal'] : 0;
                $minVal += $tmpBaseNum;
                $maxVal = ObjectAccessor::get($vars, $varName . '.maxVal', 100); //($vars[$varName]['maxVal']) ? $vars[$varName]['maxVal'] : 100;
                $maxVal += $tmpBaseNum + $difficultyFactor;

                $newVal = $this->randomFloat($minVal, $maxVal) * $tmpMultiplier;
                $contentClone->variableDeclarations->{$varName}->value = $newVal;
            } else if (strtolower($varDecl->baseType) == 'boolean') {
                $newVal = (mt_rand(0, 1) == 1) ? true : false ;
            }
            $contentClone->variableDeclarations->{$varName}->value = $newVal;
            //ObjectAccessor::set($contentClone, 'variableDeclarations.' . $varName . '.value', $newVal);
        }

        // Return
        return $contentClone;
    }

    public function randomFloat($min = 0, $max = 1, $precision = 0) {
        return round($min + mt_rand() / mt_getrandmax() * ($max - $min), $precision);
    }
}
