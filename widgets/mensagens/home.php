<?php 
require __DIR__ . "/sidebar.php";
require __DIR__ . "/actions.php";
?>

<section class="dash_content_app">
    <header class="dash_content_app_header ">
        <h2 class="icon-comments-o mb-2">Mensagens Configuradas</h2>
        <?php 
        $comm = [  
            'aggregate' => 'messages', 
            'pipeline' => [
            ],
            'cursor' => new stdClass
        ];
        $documents['doc'] = aggregate($comm);
       //var_dump($documents['doc']);
       //exit;
        ?>
        
    </header>

    <div class="dash_content_app_box ">
        
             <section class="app_dash_home_trafic mt-1">
                
                <div class="app_dash_home_trafic_list">
                    <?php if (!$documents['doc']): ?>
                        <div class="message info icon-info">
                            Não existem mensagens castradas neste momento. Quando tiver, você
                            poderá monitoriar todas por aqui.
                        </div>
                    <?php else: ?>
                        <div class="">
                                <table>                                
                                    <thead>
                                        <tr>
                                            <th><h4>TIPO</h4></th>
                                            <th><h4>ASSUNTO</h4></th>
                                            <th><p class=""><b>MENSAGEM</b></p></th>
                                            <th colspan="2">OPÇÕES<p class=""></th>
                                        </tr>
                                    </thead>
                                
                                    <tbody>
                                        <tr>
                                        <?php foreach( $documents['doc'] as $col ): ?>
                                            <td class="desc"><?= str_studly_case($col->type); ?></td>                                            
                                            <td class="desc"><?= str_limit_words($col->subject, 3); ?></td>
                                            <td class="tb_msg"><?= mb_strtolower($col->body); ?></td>
                                            <td ><a class="btn" href="<?= url("/dash.php?app=mensagens/config-edit&_id={$col->_id}"); ?>" target="_blank">editar</a></td>
                                            <td ><a class="btn btn-red" href="<?= url("/dash.php?app=mensagens/delete&_id={$col->_id}"); ?>" target="_blank">excluir</a></td>
                                        </tr>
                                    </tbody>
									<?php endforeach; ?>
                                </table>					
								
                            </div>
                        
                    <?php endif; ?>
                </div>
            </section>
        

            
    </div>
</section>