<?php
require "assets/mongo.php";

if (!empty($_GET['_id']) && isset($_GET['_id'])) {      
    $mongo = new mongo();
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->delete(['_id' => new MongoDB\BSON\ObjectId($_GET['_id'])], ['limit' => 1]);
    $mongo->Conn->executeBulkWrite($_SESSION['crm'].'.messages', $bulk);   

    if (!$mongo) {
        echo "<div class=\"not_found\">
        <p class=\"not_found_icon icon-link-broken icon-notext\"></p>
        <h4>Oops, não foi encontrado!</h4>
        <p>Você tentou acessar uma APP ou Widget que não existe ou não está disponível. Favor use o menu para navegar no sistema</p>
    </div>";
    redirect(url('/dash.php?app=mensagens/home'));
    }
} 
redirect(url('/dash.php'));