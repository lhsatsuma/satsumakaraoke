<?php

$lbls = [];
$base = APPPATH . 'Language/'.$GLOBALS['locale'].'/Controllers/Files.php';
if(file_exists($base)){
    $lbls = require($base);
}

return array_merge($lbls, [
    'LBL_ACTION_CTRL_INDEX' => 'Lista de Arquivos',
    'LBL_ACTION_CTRL_DETAIL' => 'Detalhes do Arquivo',
    'LBL_ACTION_CTRL_NEW' => 'Criar Arquivo',
    'LBL_ACTION_CTRL_EDIT' => 'Editar Arquivo',
]);