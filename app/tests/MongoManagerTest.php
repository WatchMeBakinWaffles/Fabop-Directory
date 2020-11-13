<?php

namespace App\Tests;

use App\Exception\DocumentNotFoundException;
use App\Utils\MongoManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Tests\Fixtures\ToString;

class MongoManagerTest extends TestCase
{

    public function test_Constructor_none_noException(){
        $manager = new MongoManager();
        $this->addToAssertionCount(1);
        // $manager->debug();
    }

    public function test_RetrieveDocumentByID_NotExistingDocument_Exception(){
        $this->expectException(DocumentNotFoundException::class);
        $manager=new MongoManager();
        $doc=$manager->getDocById('Entity_person_sheet','5fa41615c7827056697a1ea3');
        // $manager->debug();
    }

    public function test_RetrieveDocumentByID_ExistingDocumentById_JSONStringOfTheBSONObject(){
        $manager=new MongoManager();
        $this->assertEquals(
            '{ "_id" : { "$oid" : "5fa41615c7827056697a1ea3" }, "name" : "John" }',
            $manager->getDocById("Entity_person_sheet","5fa41615c7827056697a1ea3"));
        // $manager->var_dump();
        // $manager->debug();
    }

    public function test_RetrieveDocumentByIDWithProjection_ExistingDocumentById_JSONStringOfTheBSONObject(){
        $manager=new MongoManager();
        $this->assertEquals(
            '{ "_id" : { "$oid" : "5fa41615c7827056697a1ea3" } }',
            $manager->getDocById("Entity_person_sheet","5fa41615c7827056697a1ea3",['_id'=>1]));
        // $manager->var_dump();
        // $manager->debug();
    }

    public function test_RetrieveDocumentByFilter_ExistingDocumentByMail_JSONStringOfTheBSONObject(){
        $manager=new MongoManager();
        $this->assertEquals(
            ['{ "_id" : { "$oid" : "5fa41615c7827056697a1ea3" }, "name" : "machin", "emails" : [ "1@gmail.com", "2@gmail.com", "3@gmail.com", "sample@mail.com" ] }'],
            $manager->getDocByFilter("Entity_person_sheet",["emails"=>["1@gmail.com"]]));
        // $manager->debug();
    }

    public function test_RetrieveDocumentByFilterWithProjection_ExistingDocumentByMail_JSONStringOfTheBSONObject(){
        $manager=new MongoManager();
        $this->assertEquals(
            ['{ "_id" : { "$oid" : "5fa41615c7827056697a1ea3" }, "emails" : [ "1@gmail.com", "2@gmail.com", "3@gmail.com", "sample@mail.com" ] }'],
            $manager->getDocByFilter("Entity_person_sheet",["emails"=>["1@gmail.com"]],['emails'=>1]));
        // $manager->debug();
    }

    public function test_RetrieveDocumentByFilter_ExistingDocumentByPhone_JSONStringOfTheBSONObject(){
        $manager=new MongoManager();
        $this->assertEquals(
            ['{ "_id" : { "$oid" : "5fa41615c7827056697a1ea3" }, "name" : "truc", "phones" : [ "0698995173", "0605487915", "0739425487" ] }'],
            $manager->getDocByFilter("Entity_person_sheet",["phones"=>["0739425487"]]));
        // $manager->debug();
    }

    public function test_RetrieveDocumentByFilter_ExistingMultipleDocumentsByMail_JSONStringOfTheBSONObject(){
        $manager=new MongoManager();
        $this->assertEquals(
            ['{ "_id" : { "$oid" : "5fa41615c7827056697a1ea3" }, "name" : "machin", "emails" : [ "1@gmail.com", "2@gmail.com", "3@gmail.com", "sample@mail.com" ] }',
            '{ "_id" : { "$oid" : "5fa41615c7827056697a1ea3" }, "name" : "sample", "emails" : "sample@mail.com" }'],
            $manager->getDocByFilter("Entity_person_sheet",["emails"=>["sample@mail.com"]]));
        // $manager->debug();
    }

    public function test_InsertDocument_InexistentDocument_newDocId(){
        $manager= new MongoManager();
        $this->assertInternalType('string',
            (string) $manager->insertSingle("Entity_person_sheet",
                [
                    "name"=>"test",
                    "emails"=>["sample1@mail.com","sample2@mail.com"],
                    "phones"=>"0254678943"
                ]
            )
        );
    }

    public function test_DeleteDocument_ExistentDocument_DocumentDeleted(){
        $manager= new MongoManager();
        $this->assertEquals(1,
            $manager->deleteSingleById("Entity_person_sheet","5fa41615c7827056697a1ea3")
        );
    }

    public function test_DeleteDocument_InexistentDocument_Exception(){
        $this->expectException(DocumentNotFoundException::class);
        $manager= new MongoManager();
        $manager->deleteSingleById("Entity_person_sheet","5fa41615c7827056697a1ea3");
    }

    public function test_UpdateDocument_ExistentDocument_DocumentUpdated(){
        $manager= new MongoManager();
        $this->assertEquals(1,
            $manager->updateSingleValueById("Entity_person_sheet","5fa41615c7827056697a1ea3","emails.0","sample3@mail.com")
        );
    }

    public function test_UpdateDocument_InexistentDocument_Exception(){
        $this->expectException(DocumentNotFoundException::class);
        $manager= new MongoManager();
        $manager->updateSingleValueById("Entity_person_sheet","5fa41615c7827056697a1ea3","emails.0","sample3@mail.com");
    }

    public function test_UnsetSingleValue_ExistentDocument_Exception(){
        $manager= new MongoManager();
        $manager->unsetSingleValueById("Entity_person_sheet","5fa41615c7827056697a1ea3","name");
        $this->assertTrue(true);
    }

    public function test_UnsetSingleValue_InexistentDocument_Exception(){
        $manager= new MongoManager();
        $manager->unsetSingleValueById("Entity_person_sheet","5fa41615c7827056697a1ea3","name");
        $this->assertTrue(true);
    }

}