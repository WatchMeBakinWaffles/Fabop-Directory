<?php

use PHPUnit\Framework\TestCase;
use App\Utils\MongoManager;
use App\Exceptions\DocumentNotFoundException;

class MongoManagerTest extends TestCase
{

    public function test_Constructor_none_NoException(){
        $manager=new MongoManager();
        $this->addToAssertionCount(1);
        // $manager->debug();
    }

    public function test_RetrieveDocument_NotExistingDocument_Exception(){
        $this->expectException(DocumentNotFoundException::class);
        $manager=new MongoManager();
        $doc=$manager->get_doc('Entity_person_sheet','5bf015a4b96e4020ec3f3984');
        // $manager->debug();
    }

    public function test_RetrieveDocument_ExistingDocument_DocumentBSONObject(){
        $manager=new MongoManager();
        $this->assertEquals('{ "_id" : { "$oid" : "5bf015a4b96e4020ec3f3983" }, "name" : "John" }',$manager->get_doc("Entity_person_sheet","5bf015a4b96e4020ec3f3983"));
        // $manager->debug();
    }

    // public function test_ConvertToRoman_0_nulla(){
    //     $this->expectException(InvalidNumberException::class);
    //     $conv=new Converter();
    //     $conv->convert_toRomans("5000");
    // }

    // public function test_ConvertToRoman_RoundNumber_RoundNumberInRomanNumeral(){
    //     $conv=new Converter();
    //     $this->assertEquals("I",$conv->convert_toRomans("1"));
    //     $this->assertEquals("X",$conv->convert_toRomans("10"));
    //     $this->assertEquals("L",$conv->convert_toRomans("50"));
    //     $this->assertEquals("C",$conv->convert_toRomans("100"));
    //     $this->assertEquals("D",$conv->convert_toRomans("500"));
    //     $this->assertEquals("M",$conv->convert_toRomans("1000"));
    // }

    // public function test_ConvertToRoman_NegativeRoundNumber_NegativeRoundNumberInRomanNumeral(){
    //     $conv=new Converter();
    //     $this->assertEquals("-I",$conv->convert_toRomans("-1"));
    //     $this->assertEquals("-X",$conv->convert_toRomans("-10"));
    //     $this->assertEquals("-L",$conv->convert_toRomans("-50"));
    //     $this->assertEquals("-C",$conv->convert_toRomans("-100"));
    //     $this->assertEquals("-D",$conv->convert_toRomans("-500"));
    //     $this->assertEquals("-M",$conv->convert_toRomans("-1000"));
    // }

    // public function test_ConvertToRoman_Number_NumberInRomanNumeral(){
    //     $conv=new Converter();
    //     $this->assertEquals("VII",$conv->convert_toRomans("7"));
    //     $this->assertEquals("XIII",$conv->convert_toRomans("13"));
    //     $this->assertEquals("LXVII",$conv->convert_toRomans("67"));
    //     $this->assertEquals("CXLIII",$conv->convert_toRomans("143"));
    //     $this->assertEquals("DCLVIII",$conv->convert_toRomans("658"));
    //     $this->assertEquals("MMMMCDLXXXIX",$conv->convert_toRomans("4489"));
    // }

    // public function test_ConvertToRoman_NegativeNumber_NegativeNumberInRomanNumeral(){
    //     $conv=new Converter();
    //     $this->assertEquals("-VII",$conv->convert_toRomans("-7"));
    //     $this->assertEquals("-XIII",$conv->convert_toRomans("-13"));
    //     $this->assertEquals("-LXVII",$conv->convert_toRomans("-67"));
    //     $this->assertEquals("-CXLIII",$conv->convert_toRomans("-143"));
    //     $this->assertEquals("-DCLVIII",$conv->convert_toRomans("-658"));
    //     $this->assertEquals("-MMMMCDLXXXIX",$conv->convert_toRomans("-4489"));
    // }
}