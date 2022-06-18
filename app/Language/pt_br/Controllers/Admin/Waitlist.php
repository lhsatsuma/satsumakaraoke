<?php
$lbls = [];
$base = APPPATH . 'Language/'.$GLOBALS['locale'].'/Controllers/Users.php';
if(file_exists($base)){
    $lbls = require($base);
}

return array_merge($lbls, [
    'LBL_ACTION_CTRL_INDEX' => 'Lista de Músicas na Fila',
    'LBL_ACTION_CTRL_DETAIL' => 'Detalhes da Músicas na Fila',
    'LBL_ACTION_CTRL_NEW' => 'Criar Música na Fila',
    'LBL_ACTION_CTRL_EDIT' => 'Editar Música na Fila',
]);