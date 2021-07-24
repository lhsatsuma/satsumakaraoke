<?php
ini_set('max_execution_time', 0);
define('ROOTPATH', __DIR__ . '/');
$conn = mysqli_connect("localhost", "root", "@lh1024lh@", "satsumakaraoke") or die('connection error');
$query = mysqli_query($conn, "SELECT * FROM musicas WHERE deletado = 0 AND origem LIKE '%ponto%'") or die(mysqli_error());

$count = 0;
echo "\nTOTAL ROWS: ".$query->num_rows;
while($result = mysqli_fetch_assoc($query)){
    $exploded = explode(' - ', $result['nome']);
    if(count($exploded) < 2){
        continue;
    }
    $count++;
    $novo_nome = str_replace("'", "''", $exploded[1].' - '.$exploded[0]);

    $exists = mysqli_query($conn, "UPDATE musicas SET nome = '{$novo_nome}' WHERE id = '{$result['id']}'") or die(mysqli_error());

    echo "\nFINISHED ".$count." OF ".$query->num_rows;
}
echo "\nFINISHED SUCCESS ".$count;
?>