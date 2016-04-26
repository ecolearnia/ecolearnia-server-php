<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Ecofy\Support\ObjectHelper;

use App\EcoLearnia\Modules\Assignment\Evaluation\WhenHandler;

class WhenHandlerTest extends TestCase
{

    public function testName()
    {
        $whenHandler = new WhenHandler();
        $this->assertEquals('when', $whenHandler->name, 'Handler name is not "when"');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testForCorrect()
    {
        $filedata = file_get_contents('tests/mock/whenstatement.json');
        $json = json_decode($filedata, true);

        $whenHandler = new WhenHandler();

        $submData = [
			'field1' => 2,
			'field2' => 4
		];

        $expected = [
            "question1" => ["score" =>1, "feedback" => "Correct"],
			"question2" => ["score" =>1, "feedback" => "Correct"]
        ];

        $result = $whenHandler->evaluate($json['when'], $submData);

        $this->assertEquals($expected, $result, 'Result is wrong');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testForIncorrect()
    {
        $filedata = file_get_contents('tests/mock/whenstatement.json');
        $json = json_decode($filedata, true);

        //var_dump($json);

        $whenHandler = new WhenHandler();

        $submData = [
            'field1' => 3,
            'field2' => 3
        ];

        $expected = [
            "question1" => ["score" =>0, "feedback" =>"Fields cannot be same"],
            "question2" => ["score" =>0]
        ];

        $result = $whenHandler->evaluate($json['when'], $submData);

        $this->assertEquals($expected, $result, 'Result is wrong');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testForPartialCorrect()
    {
        $filedata = file_get_contents('tests/mock/whenstatement.json');
        $json = json_decode($filedata, true);

        //var_dump($json);

        $whenHandler = new WhenHandler();

        $submData = [
            'field1' => 3,
            'field2' => 4
        ];

        $expected = [
            "question1" => ["score" =>0, "feedback" =>"Number too large"],
            "question2" => ["score" =>1, "feedback" => "Correct"]
        ];

        $result = $whenHandler->evaluate($json['when'], $submData);

        $this->assertEquals($expected, $result, 'Result is wrong');
    }

}
