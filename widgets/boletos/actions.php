<?php 
//require_once __DIR__."/assets/hellpers.php";
require "assets/mongo.php";

//$response = [null];

function insert($type = 'Boleto'){
    $mongo = new mongo();
    $insert = new stdClass;
    $insert->started = date("Y-m-d h:i:s");
    $started = new MongoDB\BSON\UTCDateTime(new DateTime($insert->started));
    $started->toDateTime()->format('Y-m-d');

    $day =  $started->toDateTime()->format("d");
    $nexdue = null;
    if ($day <= 28) {
        $insert->due_day = $day;
        $insert->next_due = date("Y-m-d", strtotime("+1month"));
        $nexdue =  new MongoDB\BSON\UTCDateTime(new DateTime($insert->next_due));    
        $nexdue->toDateTime()->format('Y-m-d');      
        //$insert->next_due = date("Y-m-d", strtotime("+1month"));
        //$nexdue = (new \DateTime($insert->next_due))->getTimestamp();
    } else {
        $due_day = 5;
        $next_due = date("Y-m-{$due_day}", strtotime("+1month"));         
        $insert->due_day = $due_day;
        $insert->next_due = date("Y-m-d", strtotime($next_due));
        $nexdue = new MongoDB\BSON\UTCDateTime(new DateTime($insert->next_due));
    }
    $insert->last_charge = date("Y-m-d");
    $last_charge = new MongoDB\BSON\UTCDateTime(new DateTime($insert->last_charge));
    $last_charge->toDateTime()->format('Y-m-d'); 
    
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert([
        '_id_customer' => new MongoDB\BSON\ObjectID("5f7f33effc6e0000b1002317"),
        'type' => $type,
        'started' => $started,
        'due_day' => $insert->due_day,
        'next_due' =>$nexdue,
        'last_charge' => $last_charge,
        'status' => 'past_due'
    ]);  

    return $mongo->Conn->executeBulkWrite($_SESSION['crm'].'.boletos', $bulk);
}

function find(){
    $stmt = new \MongoDB\Driver\Query([]);
    $response['all'] = $mongo->Conn->executeQuery($_SESSION['crm'].'.boletos', $stmt)->toArray()[0]->_id;
}