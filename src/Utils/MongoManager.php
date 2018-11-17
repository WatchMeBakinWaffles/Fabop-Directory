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
    private $collection_list=[
    ];

    function __construct()
    {
        $this->client= new MongoDB\Client(getenv('MONGODB_URL'));
        $this->db= $this->client->fabop_directory;
    }

    function get_doc($collection,$id){
        $this->collection= $this->db->selectCollection($collection);
        $this->doc= $this->collection->findOne(['_id'=>new MongoDB\BSON\ObjectId($id)]);
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