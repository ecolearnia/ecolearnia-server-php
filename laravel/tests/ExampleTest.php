<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->visit('/')
             ->see('Laravel 5');
    }

    public function test()
    {
        //print ("----TEST----");
        $tmp = new stdClass();
        $tmp->foo = 'foo';
        $tmp->bar = new stdClass();
        $tmp->bar->baz = 'baz-data';
        $tmp->arr = [ 'prop1' => [1,2,3]];

        $json = json_encode($tmp);

        //print($json);
        $a = new stdClass();
        $a->temp = 'ggg';
        if (property_exists($a, 'temp'))
        {
            //print('property temp exists');
        }
        if (property_exists($a, 'temp2'))
        {
            //print('property temp2 exists');
        }
    }
}
