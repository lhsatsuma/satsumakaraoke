<?php
$lbls = [];
$base = APPPATH . 'Language/'.$GLOBALS['locale'].'/Controllers/Users.php';
if(file_exists($base)){
    $lbls = require($base);
}

return array_merge($lbls, [
    'LBL_ACTION_CTRL_INDEX' => 'Lista de Usuários',
    'LBL_ACTION_CTRL_DETAIL' => 'Detalhes do Usuário',
    'LBL_ACTION_CTRL_NEW' => 'Criar Usuário',
    'LBL_ACTION_CTRL_EDIT' => 'Editar Usuário',
]);