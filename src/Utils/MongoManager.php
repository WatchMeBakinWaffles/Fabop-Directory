<?php

namespace App\Utils;

use MongoDB;
use App\Exceptions\DocumentNotFoundException;

class MongoManager
{

    private $client;
    private $db;
    private $collection;
    private $doc;

    /*
    Entity_person_sheet
    Entity_model_sheet
    Entity_person_sheet
    Entity_show_sheet
    Permissions_sheet
    */

    function __construct()
    {
        $this->client= new MongoDB\Client(getenv('MONGODB_URL'));
        $this->db= $this->client->fabop_directory;
    }

    function get_doc_by_id($collection,$id){
        $this->collection= $this->db->selectCollection($collection);
        $this->doc= $this->collection->findOne(['_id'=>new MongoDB\BSON\ObjectId($id)]);
        if($this->doc==NULL){
            throw new DocumentNotFoundException;
        }else{
            $this->doc= MongoDB\BSON\toJSON(MongoDB\BSON\fromPHP($this->doc));
            return $this->doc;
        }
    }

    function get_doc_by_mail($collection,$mail){
        $this->collection= $this->db->selectCollection($collection);
        $this->doc= $this->collection->findOne(['emails'=>['$in'=>[$mail]]]);
        if($this->doc==NULL){
            throw new DocumentNotFoundException;
        }else{
            $this->doc= MongoDB\BSON\toJSON(MongoDB\BSON\fromPHP($this->doc));
            return $this->doc;
        }
    }

    function get_doc_by_phone($collection,$phone){
        $this->collection= $this->db->selectCollection($collection);
        $this->doc= $this->collection->findOne(['phones'=>['$in'=>[$phone]]]);
        if($this->doc==NULL){
            throw new DocumentNotFoundException;
        }else{
            $this->doc= MongoDB\BSON\toJSON(MongoDB\BSON\fromPHP($this->doc));
            return $this->doc;
        }
    }

    function debug(){
        var_dump($this->client);
        var_dump($this->collection);
        var_dump($this->doc);
    }
}