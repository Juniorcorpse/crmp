<?php 
//require_once __DIR__."/assets/hellpers.php";
require "assets/mongo.php";

//$response = [null];

function insertConfig(?string $colletion, ?array $document){
    $mongo = new mongo();
    //$insert = new stdClass;     
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert($document);
    return $mongo->Conn->executeBulkWrite($_SESSION['crm'].".{$colletion}", $bulk); 
}

//TA DUPLICANDO 
function updateConfigF(?string $colletion, $oid, ?array $document){
    $mongo = new mongo();
    //$insert = new stdClass;     
    $bulk = new MongoDB\Driver\BulkWrite;
    $find = findConfigId($oid);
    $bulk->update(['_id' => $find], $document, ['upsert' => false]);
    return $mongo->Conn->executeBulkWrite($_SESSION['crm'].".{$colletion}", $bulk); 
}

function updateConfig(?string $colletion, $oid, ?array $document){
    $mongo = new mongo();
    //$insert = new stdClass;     
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->update(['_id' => new MongoDB\BSON\ObjectId($oid)], $document, ['upsert' => false]);
    return $mongo->Conn->executeBulkWrite($_SESSION['crm'].".{$colletion}", $bulk); 
}
function findConfigId($oid){
    $_id = new MongoDB\BSON\ObjectId($oid);
    $mongo = new mongo();
    $stmt = new \MongoDB\Driver\Query(['_id' => $_id]);
    $res =  $mongo->Conn->executeQuery($_SESSION['crm'].'.messages', $stmt)->toArray()[0];
    return $res;    
}

function findConfig($oid){
    $_id = new MongoDB\BSON\ObjectId($oid);
    $mongo = new mongo();
    $stmt = new \MongoDB\Driver\Query(['_id' => $_id]);
    return  $mongo->Conn->executeQuery($_SESSION['crm'].'.messages', $stmt)->toArray()[0];
}

function find(){
    $stmt = new \MongoDB\Driver\Query([]);
    $response['all'] = $mongo->Conn->executeQuery($_SESSION['crm'].'.customers', $stmt)->toArray()[0]->_id;
}



function aggregate($comm){
    $mongo = new mongo();
    $command = new MongoDB\Driver\Command($comm);       
    return $mongo->Conn->executeCommand($_SESSION['crm'], $command)->toArray();
    
    //echo json_encode($res);
}


