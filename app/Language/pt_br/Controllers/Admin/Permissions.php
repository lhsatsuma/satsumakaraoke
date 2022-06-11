<?php

$lbls = [];
$base = APPPATH . 'Language/'.$GLOBALS['locale'].'/Controllers/Permissions.php';
if(file_exists($base)){
    $lbls = require($base);
}

return array_merge($lbls, [
    'LBL_ACTION_CTRL_INDEX' => 'Lista de Permissões',
    'LBL_ACTION_CTRL_DETAIL' => 'Detalhes da Permissão',
    'LBL_ACTION_CTRL_NEW' => 'Criar Permissão',
    'LBL_ACTION_CTRL_EDIT' => 'Editar Permissão',
]);