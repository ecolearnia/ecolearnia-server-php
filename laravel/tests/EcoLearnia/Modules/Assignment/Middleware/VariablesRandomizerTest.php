<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Ecofy\Support\ObjectHelper;
use App\Ecofy\Util\JsonUtil;

use App\EcoLearnia\Modules\Assignment\Middleware\VariablesRandomizer;

class VariablesRandomizerTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testApply()
    {
        $variablesRandomizer = new VariablesRandomizer();

        $content = JsonUtil::loadFromFile('tests/mock/numbergame.testitem.json');

        $params = [];
        //var_dump($content);

        $result = $variablesRandomizer->apply($content, $params);
        //print_r($content->variableDeclarations);
        //print_r($result->variableDeclarations);

        $sameCount = 0;
        if ($result->variableDeclarations->num1->value === $content->variableDeclarations->num1->value) {
            $sameCount++;
        }
        if ($result->variableDeclarations->num2->value === $content->variableDeclarations->num2->value) {
            $sameCount++;
        }
        if ($result->variableDeclarations->num3->value === $content->variableDeclarations->num3->value) {
            $sameCount++;
        }

        // There is little chance that more than one varible happen to randomly be same number
        $this->assertTrue($sameCount <= 1, 'More than one variable produced same random as original');
    }


}
