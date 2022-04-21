<?php
require __DIR__ . "/sidebar.php";
require __DIR__ . "/actions.php";

//Se [$_GET['_id']] for vazio retorna pra home
if (empty($_GET['_id']) && !isset($_GET['_id'])) {   
    redirect(url('/dash.php?app=mensagens/home'));    
} 

//retorna o doumento para a edição home
$doc['msgconig']  = findConfig($_GET['_id']);

//action tem que existir, ser difetente de vazio e ser igual a update
if (!empty($_POST['action']) && $_POST['action'] == 'update') {
    
    if(!empty($_POST['type']) && !empty($_POST['body']) && !empty($_POST['_id'])){
        $_id = filter_input(INPUT_POST, '_id', FILTER_SANITIZE_SPECIAL_CHARS);

        $sms = (!empty($_POST['sms']) && $_POST['sms'] = true ? true : false);
        $email = (!empty($_POST['email']) && $_POST['email'] = true ? true : false);
        
        $qt_send = filter_input(INPUT_POST, 'qt_send', FILTER_VALIDATE_INT); 
        if (empty($_POST['qt_send']) && $_POST['qt_send'] > 9) {
            $qt_send = null;
        }

        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS);
        $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS);
        $body = filter_input(INPUT_POST, 'body', FILTER_DEFAULT);
        $frequencia = filter_input(INPUT_POST, 'frequencia', FILTER_SANITIZE_SPECIAL_CHARS);
        $repeat = filter_input(INPUT_POST, 'repeat', FILTER_SANITIZE_SPECIAL_CHARS);
        $now = date("Y-m-d");

        $dt_start = filter_input(INPUT_POST, 'dt_start', FILTER_SANITIZE_SPECIAL_CHARS);   
        if (!empty($_POST['dt_start']) && $_POST['dt_start'] != null && $_POST['dt_start'] >=  $now) {         
            $dt_start =  new MongoDB\BSON\UTCDateTime(new DateTime($_POST['dt_start'])); 
            $dt_start->toDateTime()->format('Y-m-d');             
        }else {
            $_SESSION['ERROR'] = "<h3 style='color:red;'>ERRO NA DATA: {$dt_start}</h3>";
            $dt_start =  new MongoDB\BSON\UTCDateTime(new DateTime($now)); 
            $dt_start->toDateTime()->format('Y-m-d'); 
            //return $_SESSION['ERROR'];
        }
    
        $dt_end = filter_input(INPUT_POST, 'dt_end', FILTER_SANITIZE_SPECIAL_CHARS);  
        if (!empty($_POST['dt_end']) && $_POST['dt_end'] != null && $_POST['dt_end'] >=  $dt_start) {
            
            $dt_end =  new MongoDB\BSON\UTCDateTime(new DateTime($_POST['dt_end']));    
            $dt_end->toDateTime()->format('Y-m-d');       
        }else {
            $_SESSION['ERROR'] = "<h3 style='color:red;'>ERRO NA DATA: {$dt_end}</h3> "; 
            //return $_SESSION['ERROR'];       
        }
        //UP
        $update = updateConfig(
            'messages',
            $_id,
            [
                'type' => $type,            
                'sms' => $sms,
                'email' => $email,
                'qt_send' => $qt_send,
                'subject' => $subject,
                'frequencia' => $frequencia,
                'repeat' => $repeat,
                'dt_start' => $dt_start,
                'dt_end' => $dt_end,
                'body'=> $body        
            ]
        );
    
        if ($update) {
            redirect(url("/dash.php?app=mensagens/config-edit&_id={$_GET['_id']}"));
        }else{
            redirect(url('/dash.php?app=mensagens/home'));
        }
    }
}

?>

<section class="dash_content_app">
    <header class="dash_content_app_header">
        <h2 class="icon-cog"> Editar Configurações</h2>
    </header>

    <div class="dash_content_app_box">
          
        <form class="app_form"  action="<?= url("/dash.php?app=mensagens/config-edit&_id={$_GET['_id']}"); ?>" method="post">
        <input type="hidden" name="action"  value="update"/>
        <input type="hidden" name="_id"  value="<?= $_GET['_id'];?>"/>
            <div class=" label_group-3 ds-flex mb">
                    
                <label class="ds-flex flex">      
                    <span class="legend_check">Enviar por SMS:</span>              
                    <input class="checkbox" type="checkbox" name="sms"  <?=( $doc['msgconig']->sms == true ? 'checked' : (!empty($sms) && $sms == true ? 'checked' : ''));?> /> 
                                            
                </label>
                <label class="ds-flex flex">          
                    <span class="legend_check">Enviar por E-mail:</span>                  
                    <input class="checkbox" type="checkbox" name="email"  <?=( $doc['msgconig']->email == true ? 'checked' : (!empty($email) && $email == true ? 'checked' : ''));?>/> 
                        
                </label>          
                    <label class="label">
                    <span class="legend">Mensagens por minuto:</span>
                    <input type="number" name="qt_send" value="<?= $doc['msgconig']->qt_send ?>" placeholder="Quantidade de envio" min="1" max="10" size="5" maxlength="5" data-action="nunberLengthMask" required/>
                </label>      
            </div>
            <div class="label_g2">
                    <label class="label">
                        <span class="legend">*Tipo de mensagen:</span>
                        <select name="type" required>
                            <option value="cadastro" <?=( $doc['msgconig']->type == 'cadastro' ? 'selected' : (!empty($type) && $type == 'cadastro' ? 'selected' : ''));?>>Cadastro</option>
                            <option value="boleto" <?=( $doc['msgconig']->type == 'boleto' ? 'selected' : (!empty($type) && $type == 'boleto' ? 'selected' : ''));?>>Boleto</option>                            
                        </select>
                    </label>

                    <label class="label">
                        <span class="legend">Assunto:</span>
                        <input type="text" name="subject" value="<?=$_id ?? $doc['msgconig']->subject?>" placeholder="Assunto da mensagen" required/>
                    </label>
                </div>
                
            <div class=" label_g2">    
                <label class="label">
                    <span class="legend">Frequência do envio:</span>    
                        <select name="frequencia" required>
                            <option value="">selecione a frequência do envio</option>
                            <option value="1day" <?=( $doc['msgconig']->frequencia == '1day' ? 'selected' : '');?>>Dia</option>
                            <option value="7days" <?=( $doc['msgconig']->frequencia == '7days' ? 'selected' : '');?>>Semanal</option>
                            <option value="1month" <?=( $doc['msgconig']->frequencia == '1month' ? 'selected' : '');?>>Mensal</option>
                            <option value="1year" <?=( $doc['msgconig']->frequencia == '1year' ? 'selected' : '');?>>Anual</option>                            
                        </select>
                </label>        
                <label class="label">    
                    <span class="legend">Repetições por destinatário:</span>    
                    <select name="repeat" required>    
                        <option value="">selecione quantos envios por destinatário</option>
                        <option value="1" <?=( $doc['msgconig']->repeat == '1' ? 'selected' : '');?>>1</option>
                        <option value="2" <?=( $doc['msgconig']->repeat == '2' ? 'selected' : '');?>>2</option>
                        <option value="3" <?=( $doc['msgconig']->repeat == '3' ? 'selected' : '');?>>3</option>                            
                    </select>
                </label>
            </div>

            <div class=" label_g2">
                <label class="label">
                    <span class="legend">Data início de evio de mensagens</span>
                    <input type="date" name="dt_start" value="<?= $doc['msgconig']->dt_start->toDateTime()->format('Y-m-d');?>" required/>
                </label>
                <label class="label">
                    <span class="legend">Data fim de evio de mensagens:</span>
                    <input type="date" name="dt_end" value="<?= $dt_end ?? $doc['msgconig']->dt_end->toDateTime()->format('Y-m-d');?>" required/>
                </label>
            </div>
            <label class="label">
                <span class="legend">Mensagem:</span>
                <textarea name="body" placeholder="Defina uma mensagem" required><?= $doc['msgconig']->body;?></textarea>
            </label>
            
            <div class="al-right">
                <button class="btn btn-green icon-check-square-o">Reconfigurar Mensagem</button>
            </div>
        </form>
        
    </div>
</section>
