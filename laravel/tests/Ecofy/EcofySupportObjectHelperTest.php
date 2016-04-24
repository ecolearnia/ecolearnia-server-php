<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Ecofy\Support\ObjectHelper;

class EcofySupportObjectHelperTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testObject()
    {
        $arr = [
            'fields' => [ 'data' => 'mydata']
        ];
        $expected = new \stdClass();
        $expected->fields = new \stdClass();
        $expected->fields->data = 'mydata';
        $result = ObjectHelper::createObject($arr);

        $this->assertEquals($expected, $result);
    }

    public function testHydrate()
    {
        $data = [
           "foo.bar.baz1" => "baz-val1",
           "foo.bar.baz2" => "baz-val2"
        ];
		$result = ObjectHelper::hydrate($data);
		$expected = [
            "foo" => [
                "bar" => [
                    "baz1" => "baz-val1",
                    "baz2" => "baz-val2"
                ]
            ]
        ];

        $this->assertEquals($expected, $result, 'Hydrated does not match');
    }

    public function testDehydrate()
    {
        $data = [
            "foo" => [
                "bar" => [
                    "baz1" => "baz-val1",
                    "baz2" => "baz-val2"
                ]
            ]
        ];
		$result = ObjectHelper::dehydrate($data);
		$expected = [
           "foo.bar.baz1" => "baz-val1",
           "foo.bar.baz2" => "baz-val2"
        ];
        $this->assertEquals($expected, $result, 'Dehydrated does not match');
    }
}
