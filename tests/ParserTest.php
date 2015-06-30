<?php

use EDI\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function testMessageUnwrap()
    {
        $p = new Parser();
        $string = "LOC+9+VNSGN'LOC+11+ITGOA'MEA+WT++KGM:9040'";
        $test = $p->unwrap($string);

        $expected = array("LOC+9+VNSGN'", "LOC+11+ITGOA'", "MEA+WT++KGM:9040'");
        $this->assertEquals($expected, $test);
    }

    public function testParseSimple()
    {
        $p = new Parser();
        $array = array("LOC+9+VNSGN'", "LOC+11+ITGOA'", "MEA+WT++KGM:9040'");
        $expected = [["LOC", "9", "VNSGN"], ["LOC", "11", "ITGOA"], ["MEA", "WT", "", ["KGM", "9040"]]];
        $p->parse($array);
        $result = $p->get();
        $this->assertEquals($expected, $result);
    }

    public function testEscapedSegment()
    {
        $p = new Parser();
        $string = "EQD+CX??DU12?+3456+2?:0'";
        $expected = ["EQD", "CX?DU12+3456", "2:0"];
        $result = $p->splitSegment($string);
        $this->assertEquals($expected, $result);
    }

    public function testNotEscapedSegment()
    {
        $p = new Parser();
        $string = "EQD+CX?DU12?+3456+2?:0'";
        $expected = ["EQD", "CX?DU12+3456", "2:0"];
        $result = $p->splitSegment($string);
        $expectedError = "There's a character not escaped with ? in the data; string CX?DU12?+3456";
        $error = $p->errors();
        $this->assertEquals($expected, $result);
        $this->assertContains($expectedError, $error);
    }

    public function testNoErrors()
    {
        $p = new Parser();
        $string = "LOC+9+VNSGN'\nLOC+11+ITGOA'";
        $p->parse($string);
        $result = $p->errors();
        $this->assertEmpty($result);
    }
}