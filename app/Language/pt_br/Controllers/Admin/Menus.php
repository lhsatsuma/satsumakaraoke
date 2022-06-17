<?php

$lbls = [];
$base = APPPATH . 'Language/'.$GLOBALS['locale'].'/Controllers/Menus.php';
if(file_exists($base)){
    $lbls = require($base);
}

return array_merge($lbls, [
    'LBL_ACTION_CTRL_INDEX' => 'Lista de Menus',
    'LBL_ACTION_CTRL_DETAIL' => 'Detalhes do Menu',
    'LBL_ACTION_CTRL_NEW' => 'Criar Menu',
    'LBL_ACTION_CTRL_EDIT' => 'Editar Menu',
]);