<?php

$template_diretorio = get_template_directory();

// Incluindo os metodos da API
require_once($template_diretorio . "/custom-post-type/produto.php");
require_once($template_diretorio . "/custom-post-type/transacao.php");

// Incluindo endpoints
require_once($template_diretorio . "/endpoints/usuario_get.php");
require_once($template_diretorio . "/endpoints/usuario_post.php");

// Definindo tempo de expiração do token gerado
function expire_auth_token() {
    // Tempo atual do servidor + tempo desejado de expiração (60 = 1 min, 60 * 60 = 1 hr, 1hr * 24 = 1 dia)
    return time() + (60 * 60 * 24);
}
add_action('jwt_auth_expire', 'expire_auth_token');

?>