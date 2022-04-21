<div class="dash_content_sidebar">
    <h3 class="icon-asterisk">dashboard/configurações</h3>
    <p class="dash_content_sidebar_desc">Aqui você gerencia desparos de mensagens...</p>

    <nav>
        <?php
        $nav = function ($icon, $href, $title) use ($getApp) {
            $active = ($getApp == $href ? "active" : null);
            return "<a class=\"icon-{$icon} radius {$active}\" href=\"dash.php?app={$href}\">{$title}</a>";
        };

        echo $nav("pencil-square-o", "mensagens/home", "Mensagens");
        echo $nav("cog", "mensagens/config-create", "Nova Configuração");
        ?>
    </nav>
</div>