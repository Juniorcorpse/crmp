<?php
require __DIR__ . "/sidebar.php";
require __DIR__ . "/actions.php";

if (!empty($_POST['action']) && $_POST['action'] == 'create' && !empty($_POST['dt_start']) && !empty($_POST['dt_end'])) {
    if(!empty($_POST['type']) && !empty($_POST['body'])){
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
        if ($_POST['dt_start'] != null && $_POST['dt_start'] >=  $now) {         
            $dt_start =  new MongoDB\BSON\UTCDateTime(new DateTime($_POST['dt_start'])); 
            $dt_start->toDateTime()->format('Y-m-d');             
        }else {
            $_SESSION['ERROR'] = "<h3 style='color:red;'>ERRO NA DATA: {$dt_start}</h3>";
            //return $_SESSION['ERROR'];
        }

        $dt_end = filter_input(INPUT_POST, 'dt_end', FILTER_SANITIZE_SPECIAL_CHARS);  
        if ($_POST['dt_end'] != null && $_POST['dt_end'] >=  $dt_start) {
            
            $dt_end =  new MongoDB\BSON\UTCDateTime(new DateTime($_POST['dt_end']));    
            $dt_end->toDateTime()->format('Y-m-d');       
        }else {
            $_SESSION['ERROR'] = "<h3 style='color:red;'>ERRO NA DATA: {$dt_end}</h3> "; 
            //return $_SESSION['ERROR'];       
        }

        $insert = insertConfig(
            'messages',
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

        if ($insert) {
            redirect(url('/dash.php?app=mensagens/home'));
        }else{
            redirect(url('/dash.php?app=mensagens/config-create'));
            echo json_encode($_SESSION['ERROR']);
        }

        
}  
}

?>

<section class="dash_content_app">
    <header class="dash_content_app_header">
        <h2 class="icon-cog">Configurações de Mensagens</h2>
    </header>

    <div class="dash_content_app_box">
        
        <form class="app_form validatorjs" action="<?= url("/dash.php?app=mensagens/config-create"); ?>" method="post">
            <input type="hidden" name="action"  value="create"/>        
            <div class=" label_group-3 ds-flex mb">
                <label class="ds-flex flex">      
                    <span class="legend_check">Enviar por SMS:</span>              
                    <input class="checkbox" type="checkbox" name="sms" <?=(!empty($sms) && $sms == true ? 'checked' : '');?>/> 
                </label>
                <label class="ds-flex flex">          
                    <span class="legend_check">Enviar por E-mail:</span>                  
                    <input class="checkbox" type="checkbox" name="email" <?=(!empty($email) && $email == true ? 'checked' : '');?>/> 
                        
                </label>          
                <label class="label">
                    <span class="legend">Mensagens por minuto:</span>
                    <input 
                    type="number" 
                    name="qt_send" value="<?= $qt_send ?? null ?>"
                    placeholder="Quantidade de envio" 
                    min="1" max="10" size="5" maxlength="5" 
                    data-action="nunberLengthMask" data-rules="required|min=1" />
                </label>      
            </div>
            <div class="label_g2">
                <label class="label">
                    <span class="legend">*Tipo de mensagen:</span>
                    <select name="type" data-rules="required">
                        <option value="cadastro" >Cadastro</option>
                        <option value="boleto" >Boleto</option>                            
                    </select>
                </label>

                <label class="label">
                    <span class="legend">Assunto:</span>
                    <input type="text" name="subject"  placeholder="Assunto da mensagen" data-rules="required|min=10" />
                </label>
            </div>
            
            <div class=" label_g2">
                <label class="label">
                    <span class="legend">Frequência do envio:</span>
                    <select name="frequencia" data-rules="required">
                        <option value="">selecione a frequência do envio</option>
                        <option value="1day" >Dia</option>
                        <option value="7days" >Semanal</option>
                        <option value="1month" >Mensal</option>
                        <option value="1year" >Anual</option>                            
                    </select>
                </label>
                <label class="label">
                    <span class="legend">Repetições por destinatário:</span>
                    <select name="repeat" data-rules="required">
                        <option value="">selecione quantos envios por destinatário</option>
                        <option value="1" >1</option>
                        <option value="2" >2</option>
                        <option value="3" >3</option>                            
                    </select>
                </label>
            </div>

            <div class=" label_g2">
                <label class="label">
                    <span class="legend">Data início de evio de mensagens</span>
                    <input type="date" name="dt_start"  data-rules="required"/>
                    </label>
                    <label class="label">
                    <span class="legend">Data fim de evio de mensagens:</span>
                    <input type="date" name="dt_end"data-rules="required"/>
                </label>
            </div>
            
            <label class="label">
                <span class="legend">Mensagem:</span>
                <textarea name="body" placeholder="Defina uma mensagem" data-rules="required|min=10"></textarea>
            </label>
            <div class="al-right">
            <button type="submit" class="btn btn-green icon-check-square-o"> Configurar Mensagem</button>
                
            </div>
        </form>
        
    </div>
</section>
