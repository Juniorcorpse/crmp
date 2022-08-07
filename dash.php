<?php
require_once __DIR__."/assets/hellpers.php";
session_start();
if (!isset($_SESSION['crm'])) {
    $_SESSION['crm'] = 'crm';
    
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="stylesheet" href="<?= url("/assets/css/boot.css"); ?>"/>
    <link rel="stylesheet" href="<?= url("/assets/css/styles.css"); ?>"/>
    <link rel="stylesheet" href="<?= url("/assets/css/style.css"); ?>"/>
    <link rel="stylesheet" href="<?= url("/assets/css/table.css"); ?>"/>
    <!-- <link rel="icon" type="image/png" href="assets/images/favicon.png"/>-->

    <title>ArthAdmin - Site Control</title>
</head>
<body>

<div class="ajax_load">
    <div class="ajax_load_box">
        <div class="ajax_load_box_circle"></div>
        <p class="ajax_load_box_title">Aguarde, carregando...</p>
    </div>
</div>

<div class="ajax_response"></div>

<div class="mce_upload">
    <div class="mce_upload_box">
        <form class="app_form" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="mce_uplaod" value="true"/>
            <label>
                <label class="legend">Selecione uma imagem JPG ou PNG:</label>
                <input accept="image/*" type="file" name="image" required/>
            </label>
            <button class="btn btn-blue icon-upload">Enviar Imagem</button>
        </form>
    </div>
</div>
<div class="dash">
    <aside class="dash_sidebar">
        <article class="dash_sidebar_user">
            <div><img class="dash_sidebar_user_thumb" src="<?= url("/assets/images/avatar.jpg"); ?>" alt="" title=""/></div>
            <h3 class="dash_sidebar_user_name"><a href="">Jr. Souza</a></h3>
        </article>

        <ul class="dash_sidebar_nav">
            <?php
            $getApp = filter_input(INPUT_GET, "app", FILTER_SANITIZE_STRIPPED);
            $nav = function ($icon, $href, $title) use ($getApp) {
                $active = (explode("/", $getApp)[0] == explode("/", $href)[0] ? "active" : null);
                return "<li class=\"dash_sidebar_nav_li {$active}\"><a class=\"icon-{$icon}\" href=\"dash.php?app={$href}\">{$title}</a></li>";
            };

            echo $nav("home", "dash/home", "Dashboard");
            echo $nav("pencil-square-o", "mensagens/home", "Mensagens");
            echo $nav("sign-out on_mobile", "logoff", "Sair");
            ?>
        </ul>
    </aside>
    <section class="dash_content">
        <div class="dash_userbar">
            <div class="dash_userbar_box">
                <div class="dash_content_box">
                    <h1 class="icon-cog transition"><a href="<?= url("/dash.php?app=dash/home"); ?>">Arth<b>Admin</b></a></h1>
                    <div class="dash_userbar_box_bar">
                        
                        <span class="no_mobile icon-clock-o"><?= date("d/m H\hi"); ?></span>
                        <a class="no_mobile icon-sign-out" title="Sair" href="dash.php?app=logoff">Sair</a>
                        <span class="icon-menu icon-notext mobile_menu transition"></span>
                    </div>
                </div>
            </div>

            

        </div>

        <div class="dash_content_box">
            <?php
            $getApp = filter_input(INPUT_GET, "app", FILTER_SANITIZE_STRIPPED);
           
            if (!$getApp) {
                
                require __DIR__ . "/widgets/dash/home.php";
            } elseif (file_exists(__DIR__ . "/widgets/{$getApp}.php")) {
                
                require __DIR__ . "/widgets/{$getApp}.php";
                
            } else {
                echo "<div class=\"not_found\">
                    <p class=\"not_found_icon icon-link-broken icon-notext\"></p>
                    <h4>Oops, não foi encontrado!</h4>
                    <p>Você tentou acessar uma APP ou Widget que não existe ou não está disponível. Favor use o menu para navegar no sistema</p>
                </div>";
            }
            
            ?>
        </div>
    </section>
</div>
<script src="assets/js/scripts.js"></script>

</body>
</html>