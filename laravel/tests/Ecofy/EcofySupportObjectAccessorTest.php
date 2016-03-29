<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Ecofy\Support\ObjectAccessor;

class EcofySupportObjectAccessorTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testObjectGet()
    {
        $obj = new stdClass();
        $obj->foo = 'foo';
        $result = ObjectAccessor::get($obj, 'foo');

        $this->assertTrue($result == $obj->foo);
    }
    public function testObjectGetNested()
    {
        $obj = new stdClass();
        $obj->foo = new stdClass();
        $obj->foo->bar = 'bar';
        $result = ObjectAccessor::get($obj, 'foo.bar');

        $this->assertTrue($result == $obj->foo->bar);
    }

    public function testObjectGetDefault()
    {
        $obj = new stdClass();
        $obj->foo = 'b';

        $foo = ObjectAccessor::get($obj, 'ga', 'hello');

        $this->assertTrue($foo == 'hello');
    }
    public function testObjectGetDefault2()
    {
        $obj = new stdClass();
        $obj->foo = 'b';

        $foo = ObjectAccessor::get($obj, 'ga.bo', 'hello');

        $this->assertTrue($foo == 'hello');
    }

    // Arrays

    public function testArrGet()
    {
        $obj = [];
        $obj['foo'] = 'foo';
        $result = ObjectAccessor::get($obj, 'foo');

        $this->assertTrue($result == $obj['foo']);
    }
    public function testArrGetNested()
    {
        $obj = [];
        $obj['foo'] = [];
        $obj['foo']['bar'] = 'bar';
        $result = ObjectAccessor::get($obj, 'foo.bar');

        $this->assertTrue($result == $obj['foo']['bar']);
    }

    public function testArrGetDefault()
    {
        $obj = [];
        $obj['foo'] = 'b';

        $foo = ObjectAccessor::get($obj, 'ga', 'hello');

        $this->assertTrue($foo == 'hello');
    }
    public function testArrGetDefault2()
    {
        $obj = [];
        $obj['foo'] = 'b';

        $foo = ObjectAccessor::get($obj, 'ga.bo', 'hello');

        $this->assertTrue($foo == 'hello');
    }

    // Test mixed
    public function testMixedGetNested()
    {
        $obj = [];
        $obj['foo'] = new stdClass();
        $obj['foo']->bar = 'bar';
        $result = ObjectAccessor::getFromMixed($obj, 'foo.bar');

        $this->assertTrue($result == 'bar');
    }

    public function testMixedGetNested2()
    {
        $obj = new stdClass();
        $obj->foo = [];
        $obj->foo['bar'] = 'bar';
        $result = ObjectAccessor::getFromMixed($obj, 'foo.bar');

        $this->assertTrue($result == 'bar');
    }


}
