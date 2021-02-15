<?php

// Função de callback ativa após o acesso da rota
function api_usuario_post($request){
    // Captura dos valores enviados no corpo da requisição, com métodos de sanitização
    $nome = sanitize_text_field($request['nome']);
    $email = sanitize_email($request['email']);
    $senha = $request['senha'];
    $rua = sanitize_text_field($request['rua']);
    $numero = sanitize_text_field($request['numero']);
    $cep = sanitize_text_field($request['cep']);
    $bairro = sanitize_text_field($request['bairro']);
    $cidade = sanitize_text_field($request['cidade']);
    $estado = sanitize_text_field($request['estado']);

    //verificando se usuário ou email já estão cadastrados
    $user_exists = username_exists($email);
    $email_exists = email_exists($email);

    // Validação de usuários previamente cadastrados
    if(!$user_exists && !$email_exists && $email && $senha){
        // Criando um novo usuário com um ID único, correspondente ao retorno da função
        $user_id = wp_create_user($email, $senha, $email);

        $response = array(
            'ID' => $user_id,
            'display_name' => $nome,
            'first_name' => $nome,
            'role' => 'subscriber'
        );
        // Atualizando o usuário cadastrado com informações específicas

        wp_update_user($response);

        // Adicionando 'Meta fields', ou seja, informações adicionais para este usuário
        update_user_meta($user_id, 'rua', $rua);
        update_user_meta($user_id, 'numero', $numero);
        update_user_meta($user_id, 'cep', $cep);
        update_user_meta($user_id, 'bairro', $bairro);
        update_user_meta($user_id, 'cidade', $cidade);
        update_user_meta($user_id, 'estado', $estado);
    } else {
        $response = new WP_Error('email', 'Email já cadastrado.', array('status' => 403));
    }

    return rest_ensure_response($response);
}

// Registro da rota de acesso da API
function registrar_api_usuario_post(){
    register_rest_route('api', '/usuario', array(
        array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => 'api_usuario_post'
        ),
    ));
}

add_action('rest_api_init', 'registrar_api_usuario_post');
?>