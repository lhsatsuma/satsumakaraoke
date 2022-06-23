<?php
$lbls = [];
$base = APPPATH . 'Language/'.$GLOBALS['locale'].'/Public/Dropdown.php';
if(file_exists($base)){
    $lbls = require($base);
}

return array_merge($lbls, [
    'tipo_musica' => [
        'N/A' => 'N/A',
        'INT' => 'INT',
        'BRL' => 'BRL',
        'ESP' => 'ESP',
        'JPN' => 'JPN',
        'OTR' => 'OTR',
    ],
    'status_musicas_fila_list' => [
        'pendente' => 'Pendente',
        'encerrado' => 'Encerrado',
    ],
]);