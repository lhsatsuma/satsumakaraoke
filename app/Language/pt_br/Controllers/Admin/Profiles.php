<?php

$lbls = [];
$base = APPPATH . 'Language/'.$GLOBALS['locale'].'/Controllers/Profiles.php';
if(file_exists($base)){
    $lbls = require($base);
}

return array_merge($lbls, [
    'LBL_ACTION_CTRL_INDEX' => 'Lista de Perfis',
    'LBL_ACTION_CTRL_DETAIL' => 'Detalhes do Perfil',
    'LBL_ACTION_CTRL_NEW' => 'Criar Perfil',
    'LBL_ACTION_CTRL_EDIT' => 'Editar Perfil',
]);