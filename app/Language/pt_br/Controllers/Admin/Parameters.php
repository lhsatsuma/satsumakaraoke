<?php

$lbls = [];
$base = APPPATH . 'Language/'.$GLOBALS['locale'].'/Controllers/Parameters.php';
if(file_exists($base)){
    $lbls = require($base);
}

return array_merge($lbls, [
    'LBL_ACTION_CTRL_INDEX' => 'Lista de Parâmetros',
    'LBL_ACTION_CTRL_DETAIL' => 'Detalhes do Parâmetro',
    'LBL_ACTION_CTRL_NEW' => 'Criar Parâmetro',
    'LBL_ACTION_CTRL_EDIT' => 'Editar Parâmetro',
]);