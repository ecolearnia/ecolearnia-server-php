<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Ecofy\Support\SRQLParser;
use App\Ecofy\Support\QueryBuilderEloquent;

class EcofySupportQueryBuilderEloquentTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBuildQueryBoolops()
    {
        $srqlParser = new SRQLParser();
        $criteria = $srqlParser->parse('(a=11 AND b=22) OR (c=33 AND d=44)');

        $qbuilder = new QueryBuilderEloquent();

        $query = \DB::table('accounts');
        $query = $qbuilder->buildQuery($criteria->text, $query);
        $sql = $query->toSql();

        //print_r($sql);

        //$this->assertTrue(false);
    }

    public function testBuildQueryWhereIn()
    {
        $srqlParser = new SRQLParser();
        $criteria = $srqlParser->parse('a=11 AND b IN (22, 33)');

        $qbuilder = new QueryBuilderEloquent();

        $query = \DB::table('accounts');
        $query = $qbuilder->buildQuery($criteria->text, $query);
        $sql = $query->toSql();

        //print_r($sql);

        //$this->assertTrue(false);
    }

    public function testBuildQueryBetween()
    {
        $srqlParser = new SRQLParser();
        $criteria = $srqlParser->parse('a=11 AND b BETWEEN 22 AND 33');

        $qbuilder = new QueryBuilderEloquent();

        $query = \DB::table('accounts');
        $query = $qbuilder->buildQuery($criteria->text, $query);
        $sql = $query->toSql();

        //print_r($sql);

        //$this->assertTrue(false);
    }
}
