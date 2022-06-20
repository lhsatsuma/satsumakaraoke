<?php
$lbls = [];
$base = APPPATH . 'Language/'.$GLOBALS['locale'].'/Controllers/ProfilePermissions.php';
if(file_exists($base)){
    $lbls = require($base);
}

return array_merge($lbls, [
    'LBL_ACTION_CTRL_INDEX' => 'Lista de Permissão por Perfil',
    'LBL_SELECT_PROFILE' => 'Selecione o Perfil',
    'LBL_DEFAULT_PROFILE_ALL' => 'Todas as permissões para este perfil são permitidas automaticamente.',
]);