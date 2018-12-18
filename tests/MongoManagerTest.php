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
        $doc=$manager->get_doc_by_id('Entity_person_sheet','5bf015a4b96e4020ec3f3984');
        // $manager->debug();
    }

    public function test_RetrieveDocument_ExistingDocumentById_JSONStringOfTheBSONObject(){
        $manager=new MongoManager();
        $this->assertEquals(
            '{ "_id" : { "$oid" : "5bf015a4b96e4020ec3f3983" }, "name" : "John" }',
            $manager->get_doc_by_id("Entity_person_sheet","5bf015a4b96e4020ec3f3983"));
        // $manager->debug();
    }

    public function test_RetrieveDocument_ExistingDocumentByMail_JSONStringOfTheBSONObject(){
        $manager=new MongoManager();
        $this->assertEquals(
            '{ "_id" : { "$oid" : "5c18e4529d01a864bce4be3a" }, "name" : "machin", "emails" : [ "1@gmail.com", "2@gmail.com", "3@gmail.com" ] }',
            $manager->get_doc_by_mail("Entity_person_sheet","1@gmail.com"));
        // $manager->debug();
    }

    public function test_RetrieveDocument_ExistingDocumentByPhone_JSONStringOfTheBSONObject(){
        $manager=new MongoManager();
        $this->assertEquals(
            '{ "_id" : { "$oid" : "5c18eddd9d01a864bce4be3e" }, "name" : "truc", "phones" : [ "0698995173", "0605487915", "0739425487" ] }',
            $manager->get_doc_by_phone("Entity_person_sheet","0739425487"));
        // $manager->debug();
    }

}