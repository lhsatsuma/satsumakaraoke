<?php

$lbls = [];
$base = APPPATH . 'Language/'.$GLOBALS['locale'].'/Controllers/Musics.php';
if(file_exists($base)){
    $lbls = require($base);
}

return array_merge($lbls, [
    'LBL_ACTION_CTRL_INDEX' => 'Lista de Músicas',
    'LBL_ACTION_CTRL_DETAIL' => 'Detalhes da Música',
    'LBL_ACTION_CTRL_NEW' => 'Criar Música',
    'LBL_ACTION_CTRL_EDIT' => 'Editar Música',
]);