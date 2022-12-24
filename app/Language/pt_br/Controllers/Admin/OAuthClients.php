<?php
$lbls = [];
$base = APPPATH . 'Language/'.$GLOBALS['locale'].'/Controllers/OAuthClients.php';
if(file_exists($base)){
    $lbls = require($base);
}

return array_merge($lbls, [
    'LBL_ACTION_CTRL_INDEX' => 'Lista de OAuth Clients',
    'LBL_ACTION_CTRL_DETAIL' => 'Detalhes do OAuth Clients',
    'LBL_ACTION_CTRL_NEW' => 'Criar OAuth Client',
    'LBL_ACTION_CTRL_EDIT' => 'Editar OAuth Client',
    'LBL_GEN_CLIENT_SECRET' => 'Gerar Client Secret',
    'EMPTY_CLIENT_ID' => 'Preencha o campo Client ID!',
    'ALREADY_EXISTS' => 'Este Client ID já existe!',
    'LBL_SUBPANEL_MENU_OAUTH_TOKENS' => 'Access Tokens',
]);