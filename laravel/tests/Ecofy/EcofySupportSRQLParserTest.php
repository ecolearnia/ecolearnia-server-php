<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Ecofy\Support\SRQLParser;

class EcofySupportSRQLParserTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBuildParse()
    {
        $srqlParser = new SRQLParser();
        $criteria = $srqlParser->parse('(a=11 AND b=22) OR (c=33 AND d=44)');

        $expected = [
            'op' => 'or',
            'args' => [
                [
                    'op' => 'and',
                    'args' => [
                        [
                            'var' => 'a',
                            'op' => '=',
                            'val' => '11'
                        ],
                        [
                            'var' => 'b',
                            'op' => '=',
                            'val' => '22'
                        ],
                    ]
                ],
                [
                    'op' => 'and',
                    'args' => [
                        [
                            'var' => 'c',
                            'op' => '=',
                            'val' => '33'
                        ],
                        [
                            'var' => 'd',
                            'op' => '=',
                            'val' => '44'
                        ],
                    ]
                ],
            ]
        ];
        /*
        print_r($criteria->text);
        print ('---');
        print_r($expected);
        */
        //$data = array_diff($criteria, $expected);
        //$this->assertTrue(false);
    }

}
