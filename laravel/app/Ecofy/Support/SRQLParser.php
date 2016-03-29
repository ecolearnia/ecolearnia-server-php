<?php
/* Jison generated parser */
namespace App\Ecofy\Support;
use Exception;

/**
 * Simple Resource Query Language (SRQL) parser
 * Usage:
 * $srqlParser = new SRQLParser();
 * $criteriaAst = $srqlParser->parse('a=3 AND b=4');
 * $criteriaAst->text; // Contains the critiera in EcoCriteria AST 
 */
class SRQLParser
{
    public $symbols = array();
    public $terminals = array();
    public $productions = array();
    public $table = array();
    public $defaultActions = array();
    public $version = '0.3.12';
    public $debug = false;
    public $none = 0;
    public $shift = 1;
    public $reduce = 2;
    public $accept = 3;

    function trace()
    {

    }

    function __construct()
    {
        //Setup Parser

			$symbol0 = new ParserSymbol("accept", 0);
			$symbol1 = new ParserSymbol("end", 1);
			$symbol2 = new ParserSymbol("error", 2);
			$symbol3 = new ParserSymbol("start", 3);
			$symbol4 = new ParserSymbol("query_expression", 4);
			$symbol5 = new ParserSymbol("EOF", 5);
			$symbol6 = new ParserSymbol("OR", 6);
			$symbol7 = new ParserSymbol("boolean_term", 7);
			$symbol8 = new ParserSymbol("boolean_factor", 8);
			$symbol9 = new ParserSymbol("AND", 9);
			$symbol10 = new ParserSymbol("boolean_test", 10);
			$symbol11 = new ParserSymbol("boolean_primary", 11);
			$symbol12 = new ParserSymbol("predicate", 12);
			$symbol13 = new ParserSymbol("LPAREN", 13);
			$symbol14 = new ParserSymbol("RPAREN", 14);
			$symbol15 = new ParserSymbol("comparison_predicate", 15);
			$symbol16 = new ParserSymbol("in_predicate", 16);
			$symbol17 = new ParserSymbol("nin_predicate", 17);
			$symbol18 = new ParserSymbol("like_predicate", 18);
			$symbol19 = new ParserSymbol("between_predicate", 19);
			$symbol20 = new ParserSymbol("IDEN", 20);
			$symbol21 = new ParserSymbol("comp_op", 21);
			$symbol22 = new ParserSymbol("value_expression", 22);
			$symbol23 = new ParserSymbol("NUMBER", 23);
			$symbol24 = new ParserSymbol("STRING_LITERAL", 24);
			$symbol25 = new ParserSymbol("EQ", 25);
			$symbol26 = new ParserSymbol("NEQ", 26);
			$symbol27 = new ParserSymbol("GT", 27);
			$symbol28 = new ParserSymbol("GE", 28);
			$symbol29 = new ParserSymbol("LT", 29);
			$symbol30 = new ParserSymbol("LE", 30);
			$symbol31 = new ParserSymbol("IN", 31);
			$symbol32 = new ParserSymbol("in_predicate_value", 32);
			$symbol33 = new ParserSymbol("NIN", 33);
			$symbol34 = new ParserSymbol("in_value_list", 34);
			$symbol35 = new ParserSymbol("in_value_list_element", 35);
			$symbol36 = new ParserSymbol("COMMA", 36);
			$symbol37 = new ParserSymbol("LIKE", 37);
			$symbol38 = new ParserSymbol("BETWEEN", 38);
			$this->symbols[0] = $symbol0;
			$this->symbols["accept"] = $symbol0;
			$this->symbols[1] = $symbol1;
			$this->symbols["end"] = $symbol1;
			$this->symbols[2] = $symbol2;
			$this->symbols["error"] = $symbol2;
			$this->symbols[3] = $symbol3;
			$this->symbols["start"] = $symbol3;
			$this->symbols[4] = $symbol4;
			$this->symbols["query_expression"] = $symbol4;
			$this->symbols[5] = $symbol5;
			$this->symbols["EOF"] = $symbol5;
			$this->symbols[6] = $symbol6;
			$this->symbols["OR"] = $symbol6;
			$this->symbols[7] = $symbol7;
			$this->symbols["boolean_term"] = $symbol7;
			$this->symbols[8] = $symbol8;
			$this->symbols["boolean_factor"] = $symbol8;
			$this->symbols[9] = $symbol9;
			$this->symbols["AND"] = $symbol9;
			$this->symbols[10] = $symbol10;
			$this->symbols["boolean_test"] = $symbol10;
			$this->symbols[11] = $symbol11;
			$this->symbols["boolean_primary"] = $symbol11;
			$this->symbols[12] = $symbol12;
			$this->symbols["predicate"] = $symbol12;
			$this->symbols[13] = $symbol13;
			$this->symbols["LPAREN"] = $symbol13;
			$this->symbols[14] = $symbol14;
			$this->symbols["RPAREN"] = $symbol14;
			$this->symbols[15] = $symbol15;
			$this->symbols["comparison_predicate"] = $symbol15;
			$this->symbols[16] = $symbol16;
			$this->symbols["in_predicate"] = $symbol16;
			$this->symbols[17] = $symbol17;
			$this->symbols["nin_predicate"] = $symbol17;
			$this->symbols[18] = $symbol18;
			$this->symbols["like_predicate"] = $symbol18;
			$this->symbols[19] = $symbol19;
			$this->symbols["between_predicate"] = $symbol19;
			$this->symbols[20] = $symbol20;
			$this->symbols["IDEN"] = $symbol20;
			$this->symbols[21] = $symbol21;
			$this->symbols["comp_op"] = $symbol21;
			$this->symbols[22] = $symbol22;
			$this->symbols["value_expression"] = $symbol22;
			$this->symbols[23] = $symbol23;
			$this->symbols["NUMBER"] = $symbol23;
			$this->symbols[24] = $symbol24;
			$this->symbols["STRING_LITERAL"] = $symbol24;
			$this->symbols[25] = $symbol25;
			$this->symbols["EQ"] = $symbol25;
			$this->symbols[26] = $symbol26;
			$this->symbols["NEQ"] = $symbol26;
			$this->symbols[27] = $symbol27;
			$this->symbols["GT"] = $symbol27;
			$this->symbols[28] = $symbol28;
			$this->symbols["GE"] = $symbol28;
			$this->symbols[29] = $symbol29;
			$this->symbols["LT"] = $symbol29;
			$this->symbols[30] = $symbol30;
			$this->symbols["LE"] = $symbol30;
			$this->symbols[31] = $symbol31;
			$this->symbols["IN"] = $symbol31;
			$this->symbols[32] = $symbol32;
			$this->symbols["in_predicate_value"] = $symbol32;
			$this->symbols[33] = $symbol33;
			$this->symbols["NIN"] = $symbol33;
			$this->symbols[34] = $symbol34;
			$this->symbols["in_value_list"] = $symbol34;
			$this->symbols[35] = $symbol35;
			$this->symbols["in_value_list_element"] = $symbol35;
			$this->symbols[36] = $symbol36;
			$this->symbols["COMMA"] = $symbol36;
			$this->symbols[37] = $symbol37;
			$this->symbols["LIKE"] = $symbol37;
			$this->symbols[38] = $symbol38;
			$this->symbols["BETWEEN"] = $symbol38;

			$this->terminals = array(
					2=>&$symbol2,
					5=>&$symbol5,
					6=>&$symbol6,
					9=>&$symbol9,
					13=>&$symbol13,
					14=>&$symbol14,
					20=>&$symbol20,
					23=>&$symbol23,
					24=>&$symbol24,
					25=>&$symbol25,
					26=>&$symbol26,
					27=>&$symbol27,
					28=>&$symbol28,
					29=>&$symbol29,
					30=>&$symbol30,
					31=>&$symbol31,
					33=>&$symbol33,
					36=>&$symbol36,
					37=>&$symbol37,
					38=>&$symbol38
				);

			$table0 = new ParserState(0);
			$table1 = new ParserState(1);
			$table2 = new ParserState(2);
			$table3 = new ParserState(3);
			$table4 = new ParserState(4);
			$table5 = new ParserState(5);
			$table6 = new ParserState(6);
			$table7 = new ParserState(7);
			$table8 = new ParserState(8);
			$table9 = new ParserState(9);
			$table10 = new ParserState(10);
			$table11 = new ParserState(11);
			$table12 = new ParserState(12);
			$table13 = new ParserState(13);
			$table14 = new ParserState(14);
			$table15 = new ParserState(15);
			$table16 = new ParserState(16);
			$table17 = new ParserState(17);
			$table18 = new ParserState(18);
			$table19 = new ParserState(19);
			$table20 = new ParserState(20);
			$table21 = new ParserState(21);
			$table22 = new ParserState(22);
			$table23 = new ParserState(23);
			$table24 = new ParserState(24);
			$table25 = new ParserState(25);
			$table26 = new ParserState(26);
			$table27 = new ParserState(27);
			$table28 = new ParserState(28);
			$table29 = new ParserState(29);
			$table30 = new ParserState(30);
			$table31 = new ParserState(31);
			$table32 = new ParserState(32);
			$table33 = new ParserState(33);
			$table34 = new ParserState(34);
			$table35 = new ParserState(35);
			$table36 = new ParserState(36);
			$table37 = new ParserState(37);
			$table38 = new ParserState(38);
			$table39 = new ParserState(39);
			$table40 = new ParserState(40);
			$table41 = new ParserState(41);
			$table42 = new ParserState(42);
			$table43 = new ParserState(43);
			$table44 = new ParserState(44);
			$table45 = new ParserState(45);
			$table46 = new ParserState(46);
			$table47 = new ParserState(47);
			$table48 = new ParserState(48);

			$tableDefinition0 = array(

					3=>new ParserAction($this->none, $table1),
					4=>new ParserAction($this->none, $table2),
					7=>new ParserAction($this->none, $table3),
					8=>new ParserAction($this->none, $table4),
					10=>new ParserAction($this->none, $table5),
					11=>new ParserAction($this->none, $table6),
					12=>new ParserAction($this->none, $table7),
					13=>new ParserAction($this->shift, $table8),
					15=>new ParserAction($this->none, $table9),
					16=>new ParserAction($this->none, $table10),
					17=>new ParserAction($this->none, $table11),
					18=>new ParserAction($this->none, $table12),
					19=>new ParserAction($this->none, $table13),
					20=>new ParserAction($this->shift, $table14)
				);

			$tableDefinition1 = array(

					1=>new ParserAction($this->accept)
				);

			$tableDefinition2 = array(

					5=>new ParserAction($this->shift, $table15),
					6=>new ParserAction($this->shift, $table16)
				);

			$tableDefinition3 = array(

					5=>new ParserAction($this->reduce, $table3),
					6=>new ParserAction($this->reduce, $table3),
					9=>new ParserAction($this->shift, $table17),
					14=>new ParserAction($this->reduce, $table3)
				);

			$tableDefinition4 = array(

					5=>new ParserAction($this->reduce, $table4),
					6=>new ParserAction($this->reduce, $table4),
					9=>new ParserAction($this->reduce, $table4),
					14=>new ParserAction($this->reduce, $table4)
				);

			$tableDefinition5 = array(

					5=>new ParserAction($this->reduce, $table6),
					6=>new ParserAction($this->reduce, $table6),
					9=>new ParserAction($this->reduce, $table6),
					14=>new ParserAction($this->reduce, $table6)
				);

			$tableDefinition6 = array(

					5=>new ParserAction($this->reduce, $table7),
					6=>new ParserAction($this->reduce, $table7),
					9=>new ParserAction($this->reduce, $table7),
					14=>new ParserAction($this->reduce, $table7)
				);

			$tableDefinition7 = array(

					5=>new ParserAction($this->reduce, $table8),
					6=>new ParserAction($this->reduce, $table8),
					9=>new ParserAction($this->reduce, $table8),
					14=>new ParserAction($this->reduce, $table8)
				);

			$tableDefinition8 = array(

					4=>new ParserAction($this->none, $table18),
					7=>new ParserAction($this->none, $table3),
					8=>new ParserAction($this->none, $table4),
					10=>new ParserAction($this->none, $table5),
					11=>new ParserAction($this->none, $table6),
					12=>new ParserAction($this->none, $table7),
					13=>new ParserAction($this->shift, $table8),
					15=>new ParserAction($this->none, $table9),
					16=>new ParserAction($this->none, $table10),
					17=>new ParserAction($this->none, $table11),
					18=>new ParserAction($this->none, $table12),
					19=>new ParserAction($this->none, $table13),
					20=>new ParserAction($this->shift, $table14)
				);

			$tableDefinition9 = array(

					5=>new ParserAction($this->reduce, $table10),
					6=>new ParserAction($this->reduce, $table10),
					9=>new ParserAction($this->reduce, $table10),
					14=>new ParserAction($this->reduce, $table10)
				);

			$tableDefinition10 = array(

					5=>new ParserAction($this->reduce, $table11),
					6=>new ParserAction($this->reduce, $table11),
					9=>new ParserAction($this->reduce, $table11),
					14=>new ParserAction($this->reduce, $table11)
				);

			$tableDefinition11 = array(

					5=>new ParserAction($this->reduce, $table12),
					6=>new ParserAction($this->reduce, $table12),
					9=>new ParserAction($this->reduce, $table12),
					14=>new ParserAction($this->reduce, $table12)
				);

			$tableDefinition12 = array(

					5=>new ParserAction($this->reduce, $table13),
					6=>new ParserAction($this->reduce, $table13),
					9=>new ParserAction($this->reduce, $table13),
					14=>new ParserAction($this->reduce, $table13)
				);

			$tableDefinition13 = array(

					5=>new ParserAction($this->reduce, $table14),
					6=>new ParserAction($this->reduce, $table14),
					9=>new ParserAction($this->reduce, $table14),
					14=>new ParserAction($this->reduce, $table14)
				);

			$tableDefinition14 = array(

					21=>new ParserAction($this->none, $table19),
					25=>new ParserAction($this->shift, $table24),
					26=>new ParserAction($this->shift, $table25),
					27=>new ParserAction($this->shift, $table26),
					28=>new ParserAction($this->shift, $table27),
					29=>new ParserAction($this->shift, $table28),
					30=>new ParserAction($this->shift, $table29),
					31=>new ParserAction($this->shift, $table20),
					33=>new ParserAction($this->shift, $table21),
					37=>new ParserAction($this->shift, $table22),
					38=>new ParserAction($this->shift, $table23)
				);

			$tableDefinition15 = array(

					1=>new ParserAction($this->reduce, $table1)
				);

			$tableDefinition16 = array(

					7=>new ParserAction($this->none, $table30),
					8=>new ParserAction($this->none, $table4),
					10=>new ParserAction($this->none, $table5),
					11=>new ParserAction($this->none, $table6),
					12=>new ParserAction($this->none, $table7),
					13=>new ParserAction($this->shift, $table8),
					15=>new ParserAction($this->none, $table9),
					16=>new ParserAction($this->none, $table10),
					17=>new ParserAction($this->none, $table11),
					18=>new ParserAction($this->none, $table12),
					19=>new ParserAction($this->none, $table13),
					20=>new ParserAction($this->shift, $table14)
				);

			$tableDefinition17 = array(

					8=>new ParserAction($this->none, $table31),
					10=>new ParserAction($this->none, $table5),
					11=>new ParserAction($this->none, $table6),
					12=>new ParserAction($this->none, $table7),
					13=>new ParserAction($this->shift, $table8),
					15=>new ParserAction($this->none, $table9),
					16=>new ParserAction($this->none, $table10),
					17=>new ParserAction($this->none, $table11),
					18=>new ParserAction($this->none, $table12),
					19=>new ParserAction($this->none, $table13),
					20=>new ParserAction($this->shift, $table14)
				);

			$tableDefinition18 = array(

					6=>new ParserAction($this->shift, $table16),
					14=>new ParserAction($this->shift, $table32)
				);

			$tableDefinition19 = array(

					22=>new ParserAction($this->none, $table33),
					23=>new ParserAction($this->shift, $table34),
					24=>new ParserAction($this->shift, $table35)
				);

			$tableDefinition20 = array(

					13=>new ParserAction($this->shift, $table37),
					32=>new ParserAction($this->none, $table36)
				);

			$tableDefinition21 = array(

					13=>new ParserAction($this->shift, $table37),
					32=>new ParserAction($this->none, $table38)
				);

			$tableDefinition22 = array(

					22=>new ParserAction($this->none, $table39),
					23=>new ParserAction($this->shift, $table34),
					24=>new ParserAction($this->shift, $table35)
				);

			$tableDefinition23 = array(

					22=>new ParserAction($this->none, $table40),
					23=>new ParserAction($this->shift, $table34),
					24=>new ParserAction($this->shift, $table35)
				);

			$tableDefinition24 = array(

					23=>new ParserAction($this->reduce, $table18),
					24=>new ParserAction($this->reduce, $table18)
				);

			$tableDefinition25 = array(

					23=>new ParserAction($this->reduce, $table19),
					24=>new ParserAction($this->reduce, $table19)
				);

			$tableDefinition26 = array(

					23=>new ParserAction($this->reduce, $table20),
					24=>new ParserAction($this->reduce, $table20)
				);

			$tableDefinition27 = array(

					23=>new ParserAction($this->reduce, $table21),
					24=>new ParserAction($this->reduce, $table21)
				);

			$tableDefinition28 = array(

					23=>new ParserAction($this->reduce, $table22),
					24=>new ParserAction($this->reduce, $table22)
				);

			$tableDefinition29 = array(

					23=>new ParserAction($this->reduce, $table23),
					24=>new ParserAction($this->reduce, $table23)
				);

			$tableDefinition30 = array(

					5=>new ParserAction($this->reduce, $table2),
					6=>new ParserAction($this->reduce, $table2),
					9=>new ParserAction($this->shift, $table17),
					14=>new ParserAction($this->reduce, $table2)
				);

			$tableDefinition31 = array(

					5=>new ParserAction($this->reduce, $table5),
					6=>new ParserAction($this->reduce, $table5),
					9=>new ParserAction($this->reduce, $table5),
					14=>new ParserAction($this->reduce, $table5)
				);

			$tableDefinition32 = array(

					5=>new ParserAction($this->reduce, $table9),
					6=>new ParserAction($this->reduce, $table9),
					9=>new ParserAction($this->reduce, $table9),
					14=>new ParserAction($this->reduce, $table9)
				);

			$tableDefinition33 = array(

					5=>new ParserAction($this->reduce, $table15),
					6=>new ParserAction($this->reduce, $table15),
					9=>new ParserAction($this->reduce, $table15),
					14=>new ParserAction($this->reduce, $table15)
				);

			$tableDefinition34 = array(

					5=>new ParserAction($this->reduce, $table16),
					6=>new ParserAction($this->reduce, $table16),
					9=>new ParserAction($this->reduce, $table16),
					14=>new ParserAction($this->reduce, $table16),
					36=>new ParserAction($this->reduce, $table16)
				);

			$tableDefinition35 = array(

					5=>new ParserAction($this->reduce, $table17),
					6=>new ParserAction($this->reduce, $table17),
					9=>new ParserAction($this->reduce, $table17),
					14=>new ParserAction($this->reduce, $table17),
					36=>new ParserAction($this->reduce, $table17)
				);

			$tableDefinition36 = array(

					5=>new ParserAction($this->reduce, $table24),
					6=>new ParserAction($this->reduce, $table24),
					9=>new ParserAction($this->reduce, $table24),
					14=>new ParserAction($this->reduce, $table24)
				);

			$tableDefinition37 = array(

					22=>new ParserAction($this->none, $table43),
					23=>new ParserAction($this->shift, $table34),
					24=>new ParserAction($this->shift, $table35),
					34=>new ParserAction($this->none, $table41),
					35=>new ParserAction($this->none, $table42)
				);

			$tableDefinition38 = array(

					5=>new ParserAction($this->reduce, $table25),
					6=>new ParserAction($this->reduce, $table25),
					9=>new ParserAction($this->reduce, $table25),
					14=>new ParserAction($this->reduce, $table25)
				);

			$tableDefinition39 = array(

					5=>new ParserAction($this->reduce, $table30),
					6=>new ParserAction($this->reduce, $table30),
					9=>new ParserAction($this->reduce, $table30),
					14=>new ParserAction($this->reduce, $table30)
				);

			$tableDefinition40 = array(

					9=>new ParserAction($this->shift, $table44)
				);

			$tableDefinition41 = array(

					14=>new ParserAction($this->shift, $table45),
					36=>new ParserAction($this->shift, $table46)
				);

			$tableDefinition42 = array(

					14=>new ParserAction($this->reduce, $table27),
					36=>new ParserAction($this->reduce, $table27)
				);

			$tableDefinition43 = array(

					14=>new ParserAction($this->reduce, $table29),
					36=>new ParserAction($this->reduce, $table29)
				);

			$tableDefinition44 = array(

					22=>new ParserAction($this->none, $table47),
					23=>new ParserAction($this->shift, $table34),
					24=>new ParserAction($this->shift, $table35)
				);

			$tableDefinition45 = array(

					5=>new ParserAction($this->reduce, $table26),
					6=>new ParserAction($this->reduce, $table26),
					9=>new ParserAction($this->reduce, $table26),
					14=>new ParserAction($this->reduce, $table26)
				);

			$tableDefinition46 = array(

					22=>new ParserAction($this->none, $table43),
					23=>new ParserAction($this->shift, $table34),
					24=>new ParserAction($this->shift, $table35),
					35=>new ParserAction($this->none, $table48)
				);

			$tableDefinition47 = array(

					5=>new ParserAction($this->reduce, $table31),
					6=>new ParserAction($this->reduce, $table31),
					9=>new ParserAction($this->reduce, $table31),
					14=>new ParserAction($this->reduce, $table31)
				);

			$tableDefinition48 = array(

					14=>new ParserAction($this->reduce, $table28),
					36=>new ParserAction($this->reduce, $table28)
				);

			$table0->setActions($tableDefinition0);
			$table1->setActions($tableDefinition1);
			$table2->setActions($tableDefinition2);
			$table3->setActions($tableDefinition3);
			$table4->setActions($tableDefinition4);
			$table5->setActions($tableDefinition5);
			$table6->setActions($tableDefinition6);
			$table7->setActions($tableDefinition7);
			$table8->setActions($tableDefinition8);
			$table9->setActions($tableDefinition9);
			$table10->setActions($tableDefinition10);
			$table11->setActions($tableDefinition11);
			$table12->setActions($tableDefinition12);
			$table13->setActions($tableDefinition13);
			$table14->setActions($tableDefinition14);
			$table15->setActions($tableDefinition15);
			$table16->setActions($tableDefinition16);
			$table17->setActions($tableDefinition17);
			$table18->setActions($tableDefinition18);
			$table19->setActions($tableDefinition19);
			$table20->setActions($tableDefinition20);
			$table21->setActions($tableDefinition21);
			$table22->setActions($tableDefinition22);
			$table23->setActions($tableDefinition23);
			$table24->setActions($tableDefinition24);
			$table25->setActions($tableDefinition25);
			$table26->setActions($tableDefinition26);
			$table27->setActions($tableDefinition27);
			$table28->setActions($tableDefinition28);
			$table29->setActions($tableDefinition29);
			$table30->setActions($tableDefinition30);
			$table31->setActions($tableDefinition31);
			$table32->setActions($tableDefinition32);
			$table33->setActions($tableDefinition33);
			$table34->setActions($tableDefinition34);
			$table35->setActions($tableDefinition35);
			$table36->setActions($tableDefinition36);
			$table37->setActions($tableDefinition37);
			$table38->setActions($tableDefinition38);
			$table39->setActions($tableDefinition39);
			$table40->setActions($tableDefinition40);
			$table41->setActions($tableDefinition41);
			$table42->setActions($tableDefinition42);
			$table43->setActions($tableDefinition43);
			$table44->setActions($tableDefinition44);
			$table45->setActions($tableDefinition45);
			$table46->setActions($tableDefinition46);
			$table47->setActions($tableDefinition47);
			$table48->setActions($tableDefinition48);

			$this->table = array(

					0=>$table0,
					1=>$table1,
					2=>$table2,
					3=>$table3,
					4=>$table4,
					5=>$table5,
					6=>$table6,
					7=>$table7,
					8=>$table8,
					9=>$table9,
					10=>$table10,
					11=>$table11,
					12=>$table12,
					13=>$table13,
					14=>$table14,
					15=>$table15,
					16=>$table16,
					17=>$table17,
					18=>$table18,
					19=>$table19,
					20=>$table20,
					21=>$table21,
					22=>$table22,
					23=>$table23,
					24=>$table24,
					25=>$table25,
					26=>$table26,
					27=>$table27,
					28=>$table28,
					29=>$table29,
					30=>$table30,
					31=>$table31,
					32=>$table32,
					33=>$table33,
					34=>$table34,
					35=>$table35,
					36=>$table36,
					37=>$table37,
					38=>$table38,
					39=>$table39,
					40=>$table40,
					41=>$table41,
					42=>$table42,
					43=>$table43,
					44=>$table44,
					45=>$table45,
					46=>$table46,
					47=>$table47,
					48=>$table48
				);

			$this->defaultActions = array(

					15=>new ParserAction($this->reduce, $table1)
				);

			$this->productions = array(

					0=>new ParserProduction($symbol0),
					1=>new ParserProduction($symbol3,2),
					2=>new ParserProduction($symbol4,3),
					3=>new ParserProduction($symbol4,1),
					4=>new ParserProduction($symbol7,1),
					5=>new ParserProduction($symbol7,3),
					6=>new ParserProduction($symbol8,1),
					7=>new ParserProduction($symbol10,1),
					8=>new ParserProduction($symbol11,1),
					9=>new ParserProduction($symbol11,3),
					10=>new ParserProduction($symbol12,1),
					11=>new ParserProduction($symbol12,1),
					12=>new ParserProduction($symbol12,1),
					13=>new ParserProduction($symbol12,1),
					14=>new ParserProduction($symbol12,1),
					15=>new ParserProduction($symbol15,3),
					16=>new ParserProduction($symbol22,1),
					17=>new ParserProduction($symbol22,1),
					18=>new ParserProduction($symbol21,1),
					19=>new ParserProduction($symbol21,1),
					20=>new ParserProduction($symbol21,1),
					21=>new ParserProduction($symbol21,1),
					22=>new ParserProduction($symbol21,1),
					23=>new ParserProduction($symbol21,1),
					24=>new ParserProduction($symbol16,3),
					25=>new ParserProduction($symbol17,3),
					26=>new ParserProduction($symbol32,3),
					27=>new ParserProduction($symbol34,1),
					28=>new ParserProduction($symbol34,3),
					29=>new ParserProduction($symbol35,1),
					30=>new ParserProduction($symbol18,3),
					31=>new ParserProduction($symbol19,5)
				);




        //Setup Lexer

			$this->rules = array(

					0=>"/^(?:\s+)/",
					1=>"/^(?:[0-9]+(\.[0-9]+)?\b)/",
					2=>"/^(?:AND\b)/",
					3=>"/^(?:OR\b)/",
					4=>"/^(?:NOT\b)/",
					5=>"/^(?:BETWEEN\b)/",
					6=>"/^(?:LIKE\b)/",
					7=>"/^(?:L?\"(\\.|[^\\\"])*\")/",
					8=>"/^(?:\()/",
					9=>"/^(?:\))/",
					10=>"/^(?:!=)/",
					11=>"/^(?:>=)/",
					12=>"/^(?:<=)/",
					13=>"/^(?:=)/",
					14=>"/^(?:>)/",
					15=>"/^(?:<)/",
					16=>"/^(?:IN\b)/",
					17=>"/^(?:NIN\b)/",
					18=>"/^(?:\+)/",
					19=>"/^(?:-)/",
					20=>"/^(?:,)/",
					21=>"/^(?:[_a-zA-Z][_\.a-zA-Z0-9]{0,30})/",
					22=>"/^(?:$)/",
					23=>"/^(?:.)/"
				);

			$this->conditions = array(

					"INITIAL"=>new LexerConditions(array( 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23), true)
				);


    }

    function parserPerformAction(&$thisS, &$yy, $yystate, &$s, $o)
    {

/* this == yyval */


switch ($yystate) {
case 1:

        return $s[$o-1];

break;
case 2:


        $thisS = [
        	'op' => 'or',
        	'args' => [ $s[$o-2]->text, $s[$o]->text ]
        ];


break;
case 5:


        $thisS = [
			'op' => 'and',
        	'args' => [ $s[$o-2]->text, $s[$o]->text ]
        ];


break;
case 9:

             $thisS = $s[$o-1]->text;

break;
case 15:


        $thisS = [
        	'var' => $s[$o-2]->text,
        	'op' => $s[$o-1]->text,
        	'val' => $s[$o]->text
        ];


break;
case 16:

             $thisS = floatval($yy->text);

break;
case 17:

             $thisS = substr($yy->text, 1, -1);

break;
case 24:


        $thisS = [
			'var' => $s[$o-2]->text,
			'op' => 'in',
        	'args' => $s[$o]->text
        ];


break;
case 25:


        $thisS = [
			'var' => $s[$o-2]->text,
			'op' => 'nin',
        	'args' => $s[$o]->text
        ];


break;
case 26:

         $thisS = $s[$o-1]->text;

break;
case 27:


        $thisS = [$s[$o]->text];


break;
case 28:


        array_push($s[$o-2]->text, $s[$o]->text); $thisS = $s[$o-2]->text;


break;
case 29:

             $thisS = $s[$o]->text;

break;
case 30:


        $thisS = [
			'var' => $s[$o-2]->text,
        	'op' => 'like',
    		'val' => $s[$o]->text,
        ];


break;
case 31:


        $thisS = [
			'var' => $s[$o-4]->text,
            'op' => 'between',
        	'args' => [
        		'from' => $s[$o-2]->text,
        		'to' => $s[$o]->text
        	]
        ];


break;
}

    }

    function parserLex()
    {
        $token = $this->lexerLex(); // $end = 1

        if (isset($token)) {
            return $token;
        }

        return $this->symbols["end"];
    }

    function parseError($str = "", ParserError $hash = null)
    {
        throw new Exception($str);
    }

    function lexerError($str = "", LexerError $hash = null)
    {
        throw new Exception($str);
    }

    function parse($input)
    {
        if (empty($this->table)) {
            throw new Exception("Empty Table");
        }
        $this->eof = new ParserSymbol("Eof", 1);
        $firstAction = new ParserAction(0, $this->table[0]);
        $firstCachedAction = new ParserCachedAction($firstAction);
        $stack = array($firstCachedAction);
        $stackCount = 1;
        $vstack = array(null);
        $vstackCount = 1;
        $yy = null;
        $_yy = null;
        $recovering = 0;
        $symbol = null;
        $action = null;
        $errStr = "";
        $preErrorSymbol = null;
        $state = null;

        $this->setInput($input);

        while (true) {
            // retrieve state number from top of stack
            $state = $stack[$stackCount - 1]->action->state;
            // use default actions if available
            if ($state != null && isset($this->defaultActions[$state->index])) {
                $action = $this->defaultActions[$state->index];
            } else {
                if (empty($symbol) == true) {
                    $symbol = $this->parserLex();
                }
                // read action for current state and first input
                if (isset($state) && isset($state->actions[$symbol->index])) {
                    //$action = $this->table[$state][$symbol];
                    $action = $state->actions[$symbol->index];
                } else {
                    $action = null;
                }
            }

            if ($action == null) {
                if ($recovering == 0) {
                    // Report error
                    $expected = array();
                    foreach($this->table[$state->index]->actions as $p => $item) {
                        if (!empty($this->terminals[$p]) && $p > 2) {
                            $expected[] = $this->terminals[$p]->name;
                        }
                    }

                    $errStr = "Parse error on line " . ($this->yy->lineNo + 1) . ":\n" . $this->showPosition() . "\nExpecting " . implode(", ", $expected) . ", got '" . (isset($this->terminals[$symbol->index]) ? $this->terminals[$symbol->index]->name : 'NOTHING') . "'";

                    $this->parseError($errStr, new ParserError($this->match, $state, $symbol, $this->yy->lineNo, $this->yy->loc, $expected));
                }
            }

            if ($state === null || $action === null) {
                break;
            }

            switch ($action->action) {
                case 1:
                    // shift
                    //$this->shiftCount++;
                    $stack[] = new ParserCachedAction($action, $symbol);
                    $stackCount++;

                    $vstack[] = clone($this->yy);
                    $vstackCount++;

                    $symbol = "";
                    if ($preErrorSymbol == null) { // normal execution/no error
                        $yy = clone($this->yy);
                        if ($recovering > 0) $recovering--;
                    } else { // error just occurred, resume old look ahead f/ before error
                        $symbol = $preErrorSymbol;
                        $preErrorSymbol = null;
                    }
                    break;

                case 2:
                    // reduce
                    $len = $this->productions[$action->state->index]->len;
                    // perform semantic action
                    $_yy = $vstack[$vstackCount - $len];// default to $S = $1
                    // default location, uses first token for firsts, last for lasts

                    if (isset($this->ranges)) {
                        //TODO: add ranges
                    }

                    $r = $this->parserPerformAction($_yy->text, $yy, $action->state->index, $vstack, $vstackCount - 1);

                    if (isset($r)) {
                        return $r;
                    }

                    // pop off stack
                    while ($len > 0) {
                        $len--;

                        array_pop($stack);
                        $stackCount--;

                        array_pop($vstack);
                        $vstackCount--;
                    }

                    if (is_null($_yy))
                    {
                        $vstack[] = new ParserValue();
                    }
                    else
                    {
                        $vstack[] = $_yy;
                    }
                    $vstackCount++;

                    $nextSymbol = $this->productions[$action->state->index]->symbol;
                    // goto new state = table[STATE][NONTERMINAL]
                    $nextState = $stack[$stackCount - 1]->action->state;
                    $nextAction = $nextState->actions[$nextSymbol->index];

                    $stack[] = new ParserCachedAction($nextAction, $nextSymbol);
                    $stackCount++;

                    break;

                case 3:
                    // accept
                    return true;
            }

        }

        return true;
    }


    /* Jison generated lexer */
    public $eof;
    public $yy = null;
    public $match = "";
    public $matched = "";
    public $conditionStack = array();
    public $conditionStackCount = 0;
    public $rules = array();
    public $conditions = array();
    public $done = false;
    public $less;
    public $more;
    public $input;
    public $offset;
    public $ranges;
    public $flex = false;

    function setInput($input)
    {
        $this->input = $input;
        $this->more = $this->less = $this->done = false;
        $this->yy = new ParserValue();
        $this->conditionStack = array('INITIAL');
        $this->conditionStackCount = 1;

        if (isset($this->ranges)) {
            $loc = $this->yy->loc = new ParserLocation();
            $loc->Range(new ParserRange(0, 0));
        } else {
            $this->yy->loc = new ParserLocation();
        }
        $this->offset = 0;
    }

    function input()
    {
        $ch = $this->input[0];
        $this->yy->text .= $ch;
        $this->yy->leng++;
        $this->offset++;
        $this->match .= $ch;
        $this->matched .= $ch;
        $lines = preg_match("/(?:\r\n?|\n).*/", $ch);
        if (count($lines) > 0) {
            $this->yy->lineNo++;
            $this->yy->lastLine++;
        } else {
            $this->yy->loc->lastColumn++;
        }
        if (isset($this->ranges)) {
            $this->yy->loc->range->y++;
        }

        $this->input = array_slice($this->input, 1);
        return $ch;
    }

    function unput($ch)
    {
        $len = strlen($ch);
        $lines = explode("/(?:\r\n?|\n)/", $ch);
        $linesCount = count($lines);

        $this->input = $ch . $this->input;
        $this->yy->text = substr($this->yy->text, 0, $len - 1);
        //$this->yylen -= $len;
        $this->offset -= $len;
        $oldLines = explode("/(?:\r\n?|\n)/", $this->match);
        $oldLinesCount = count($oldLines);
        $this->match = substr($this->match, 0, strlen($this->match) - 1);
        $this->matched = substr($this->matched, 0, strlen($this->matched) - 1);

        if (($linesCount - 1) > 0) $this->yy->lineNo -= $linesCount - 1;
        $r = $this->yy->loc->range;
        $oldLinesLength = (isset($oldLines[$oldLinesCount - $linesCount]) ? strlen($oldLines[$oldLinesCount - $linesCount]) : 0);

        $this->yy->loc = new ParserLocation(
            $this->yy->loc->firstLine,
            $this->yy->lineNo,
            $this->yy->loc->firstColumn,
            $this->yy->loc->firstLine,
            (empty($lines) ?
                ($linesCount == $oldLinesCount ? $this->yy->loc->firstColumn : 0) + $oldLinesLength :
                $this->yy->loc->firstColumn - $len)
        );

        if (isset($this->ranges)) {
            $this->yy->loc->range = array($r[0], $r[0] + $this->yy->leng - $len);
        }
    }

    function more()
    {
        $this->more = true;
    }

    function pastInput()
    {
        $past = substr($this->matched, 0, strlen($this->matched) - strlen($this->match));
        return (strlen($past) > 20 ? '...' : '') . preg_replace("/\n/", "", substr($past, -20));
    }

    function upcomingInput()
    {
        $next = $this->match;
        if (strlen($next) < 20) {
            $next .= substr($this->input, 0, 20 - strlen($next));
        }
        return preg_replace("/\n/", "", substr($next, 0, 20) . (strlen($next) > 20 ? '...' : ''));
    }

    function showPosition()
    {
        $pre = $this->pastInput();

        $c = '';
        for($i = 0, $preLength = strlen($pre); $i < $preLength; $i++) {
            $c .= '-';
        }

        return $pre . $this->upcomingInput() . "\n" . $c . "^";
    }

    function next()
    {
        if ($this->done == true) {
            return $this->eof;
        }

        if (empty($this->input)) {
            $this->done = true;
        }

        if ($this->more == false) {
            $this->yy->text = '';
            $this->match = '';
        }

        $rules = $this->currentRules();
        for ($i = 0, $j = count($rules); $i < $j; $i++) {
            preg_match($this->rules[$rules[$i]], $this->input, $tempMatch);
            if ($tempMatch && (empty($match) || count($tempMatch[0]) > count($match[0]))) {
                $match = $tempMatch;
                $index = $i;
                if (isset($this->flex) && $this->flex == false) {
                    break;
                }
            }
        }
        if ( $match ) {
            $matchCount = strlen($match[0]);
            $lineCount = preg_match("/(?:\r\n?|\n).*/", $match[0], $lines);
            $line = ($lines ? $lines[$lineCount - 1] : false);
            $this->yy->lineNo += $lineCount;

            $this->yy->loc = new ParserLocation(
                $this->yy->loc->lastLine,
                $this->yy->lineNo + 1,
                $this->yy->loc->lastColumn,
                ($line ?
                    count($line) - preg_match("/\r?\n?/", $line, $na) :
                    $this->yy->loc->lastColumn + $matchCount
                )
            );


            $this->yy->text .= $match[0];
            $this->match .= $match[0];
            $this->matches = $match;
            $this->matched .= $match[0];

            $this->yy->leng = strlen($this->yy->text);
            if (isset($this->ranges)) {
                $this->yy->loc->range = new ParserRange($this->offset, $this->offset += $this->yy->leng);
            }
            $this->more = false;
            $this->input = substr($this->input, $matchCount, strlen($this->input));
            $ruleIndex = $rules[$index];
            $nextCondition = $this->conditionStack[$this->conditionStackCount - 1];

            $token = $this->lexerPerformAction($ruleIndex, $nextCondition);

            if ($this->done == true && empty($this->input) == false) {
                $this->done = false;
            }

            if (empty($token) == false) {
                return $this->symbols[
                $token
                ];
            } else {
                return null;
            }
        }

        if (empty($this->input)) {
            return $this->eof;
        } else {
            $this->lexerError("Lexical error on line " . ($this->yy->lineNo + 1) . ". Unrecognized text.\n" . $this->showPosition(), new LexerError("", -1, $this->yy->lineNo));
            return null;
        }
    }

    function lexerLex()
    {
        $r = $this->next();

        while (is_null($r) && !$this->done) {
            $r = $this->next();
        }

        return $r;
    }

    function begin($condition)
    {
        $this->conditionStackCount++;
        $this->conditionStack[] = $condition;
    }

    function popState()
    {
        $this->conditionStackCount--;
        return array_pop($this->conditionStack);
    }

    function currentRules()
    {
        $peek = $this->conditionStack[$this->conditionStackCount - 1];
        return $this->conditions[$peek]->rules;
    }

    function LexerPerformAction($avoidingNameCollisions, $YY_START = null)
    {

;
switch($avoidingNameCollisions) {
case 0:/* skip whitespace */
break;
case 1:return 23;
break;
case 2:return 9;
break;
case 3:return 6;
break;
case 4:return 'NOT';
break;
case 5:return 38;
break;
case 6:return 37;
break;
case 7:return 24;
break;
case 8:return 13;
break;
case 9:return 14;
break;
case 10:return 26;
break;
case 11:return 28;
break;
case 12:return 30;
break;
case 13:return 25;
break;
case 14:return 27;
break;
case 15:return 29;
break;
case 16:return 31;
break;
case 17:return 33;
break;
case 18:return 'PLUS';
break;
case 19:return 'MINUS';
break;
case 20:return 36;
break;
case 21:return 20;
break;
case 22:return 5;
break;
case 23:return 'INVALID';
break;
}

    }
}

class ParserLocation
{
    public $firstLine = 1;
    public $lastLine = 0;
    public $firstColumn = 1;
    public $lastColumn = 0;
    public $range;

    public function __construct($firstLine = 1, $lastLine = 0, $firstColumn = 1, $lastColumn = 0)
    {
        $this->firstLine = $firstLine;
        $this->lastLine = $lastLine;
        $this->firstColumn = $firstColumn;
        $this->lastColumn = $lastColumn;
    }

    public function Range($range)
    {
        $this->range = $range;
    }

    public function __clone()
    {
        return new ParserLocation($this->firstLine, $this->lastLine, $this->firstColumn, $this->lastColumn);
    }
}

class ParserValue
{
    public $leng = 0;
    public $loc;
    public $lineNo = 0;
    public $text;

    function __clone() {
        $clone = new ParserValue();
        $clone->leng = $this->leng;
        if (isset($this->loc)) {
            $clone->loc = clone $this->loc;
        }
        $clone->lineNo = $this->lineNo;
        $clone->text = $this->text;
        return $clone;
    }
}

class LexerConditions
{
    public $rules;
    public $inclusive;

    function __construct($rules, $inclusive)
    {
        $this->rules = $rules;
        $this->inclusive = $inclusive;
    }
}

class ParserProduction
{
    public $len = 0;
    public $symbol;

    public function __construct($symbol, $len = 0)
    {
        $this->symbol = $symbol;
        $this->len = $len;
    }
}

class ParserCachedAction
{
    public $action;
    public $symbol;

    function __construct($action, $symbol = null)
    {
        $this->action = $action;
        $this->symbol = $symbol;
    }
}

class ParserAction
{
    public $action;
    public $state;
    public $symbol;

    function __construct($action, &$state = null, &$symbol = null)
    {
        $this->action = $action;
        $this->state = $state;
        $this->symbol = $symbol;
    }
}

class ParserSymbol
{
    public $name;
    public $index = -1;
    public $symbols = array();
    public $symbolsByName = array();

    function __construct($name, $index)
    {
        $this->name = $name;
        $this->index = $index;
    }

    public function addAction($a)
    {
        $this->symbols[$a->index] = $this->symbolsByName[$a->name] = $a;
    }
}

class ParserError
{
    public $text;
    public $state;
    public $symbol;
    public $lineNo;
    public $loc;
    public $expected;

    function __construct($text, $state, $symbol, $lineNo, $loc, $expected)
    {
        $this->text = $text;
        $this->state = $state;
        $this->symbol = $symbol;
        $this->lineNo = $lineNo;
        $this->loc = $loc;
        $this->expected = $expected;
    }
}

class LexerError
{
    public $text;
    public $token;
    public $lineNo;

    public function __construct($text, $token, $lineNo)
    {
        $this->text = $text;
        $this->token = $token;
        $this->lineNo = $lineNo;
    }
}

class ParserState
{
    public $index;
    public $actions = array();

    function __construct($index)
    {
        $this->index = $index;
    }

    public function setActions(&$actions)
    {
        $this->actions = $actions;
    }
}

class ParserRange
{
    public $x;
    public $y;

    function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }
}
