<?php

// :::::::::::: boletos a vencer e vencidos :::::::::::::::::
//1 buscar todos os boletos com status em past_due or awaiting_payment -> ok!
//1.2 trazer info de boletos como [next_due e status]. -> ok!
//1.3 trazer info de customers como [email e fone]. -> ok!
//2 vericicar quantidades de dias em past_due
//ou perto de vencer.
//se next_due - INTERVAL 3 DAY = date(NOW() => awaiting_payment

// :::::::::::: aniversariante do mês :::::::::::::::::
//1 buscar todos os customers com datebirth + 1year = now.

//:::::::::::: configuração de email :::::::::::::::::
//1 obtenho todas as configurações
// if repeat > 1 foreach para repetir envio

//:::cases::::
//se boletos ate 3 dias em past_due
//se boletos em status de past_due
//"pay_status = :status AND next_due + INTERVAL 3 DAY = date(NOW())
// AND last_charge != DATE(NOW())", -> transformar em sql mongo
//"status=active"
require "assets/mongo.php";

$mongo = new mongo();

$max = 10;
$command = new MongoDB\Driver\Command([  // :::::::::::: boletos a vencer e vencidos :::::::::::::::::
        'aggregate' => 'boletos', 
        'pipeline' => [ //1 buscar todos os boletos com status em past_due or awaiting_payment -> ok!           
            ['$match' =>  [ 'status' => ['$in' => ['past_due', 'awaiting_payment'] ] ]],//para trazer 'status' => 'awaiting_payment' 
               [ //...ainda tenho que ver como fazer verificaçao de quantos dias faltam pra vencer baseado em next_due
                    '$group' => [ 
                    '_id' => [ 'cust' => '$_id_customer' ], //1.2 trazer info de boletos como [next_due e status]. -> ok!
                    'boleto' => [ '$push' => ['idboleto' => '$_id',
                    'next' => '$next_due', 
                    's' => '$status',],
               ],               
            ],            
            ],            
            ['$lookup' => [
                'from' => 'customers',
                'localField' => '_id.cust',
                'foreignField' => '_id',
                'as' => 'cliente'
               ]
            ],                  
           ['$sort' => ['boleto.status' => 1]],
           [ '$limit' => $max ],
           [ '$project' => [ //1.3 trazer info de customers como [email e fone]. -> ok!            
                    'cliente.name' => 1, 
                    'cliente.fone' => 1,                    
                    'cliente.email' => 1,
                    'boleto' => 1,
                ] 
           ],
        ],'cursor' => new stdClass,
]);       
$documents['doc'] = $mongo->Conn->executeCommand($_SESSION['crm'], $command)->toArray();
//echo json_encode($documents);
foreach( $documents['doc'] as $doc ) echo json_encode($doc)."<br><br>\n\n"; 
exit;

function insert($type, $subject, $body, $recipent_email, $from_email){
    $mongo = new mongo();
    $insert = new stdClass; 
    
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert([
        'type' => $type,
        'body'=> $body,
        'recipent_email' => $recipent_email,
        'from_email' => $from_email,
        'send_at' => null
    ]);  

   // return $mongo->Conn->executeBulkWrite($_SESSION['crm'].'.mail_queue', $bulk);
}

function sendQueue(int $perSecond = 5){
    
}