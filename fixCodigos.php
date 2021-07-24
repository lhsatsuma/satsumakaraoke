<?php
ini_set('max_execution_time', 0);
define('ROOTPATH', __DIR__ . '/');
$conn = mysqli_connect("localhost", "root", "@lh1024lh@", "satsumakaraoke") or die('connection error');


$query = mysqli_query($conn, "SELECT id
FROM musicas WHERE deletado = 0 ORDER BY nome ASC") or die(mysqli_error());

$codigo = 1;
$count = 0;
echo "\nTOTAL ROWS: ".$query->num_rows;
while($result = mysqli_fetch_assoc($query)){
    $count++;
    $exists = mysqli_query($conn, "UPDATE musicas SET codigo = '{$codigo}' WHERE id = '{$result['id']}'") or die(mysqli_error());

    echo "\nFINISHED ".$count." OF ".$query->num_rows;
    $codigo++;
}
echo "\nFINISHED SUCCESS ".$codigo;
?>