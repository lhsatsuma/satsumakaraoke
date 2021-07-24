<?php
ini_set('max_execution_time', 0);
define('ROOTPATH', __DIR__ . '/');
$conn = mysqli_connect("localhost", "root", "@lh1024lh@", "satsumakaraoke") or die('connection error');


$query = mysqli_query($conn, "SELECT id, link, origem, md5, nome as title FROM musicas_import WHERE deletado = 0 ORDER BY data_criacao ASC") or die(mysqli_error());

require_once('app/Config/Version.php');
require_once('app/Libraries/YoutubeLib.php');
require_once('app/Helpers/Sys_helper.php');


$ytLib = new \App\Libraries\YoutubeLib();

$songs = [];
while($result = mysqli_fetch_assoc($query)){
    $result['title'] = strtolower($result['title']);
    $songs[$result['title']][] = $result['id'];
}
$del_arr = [];
foreach($songs as $name => $ids){
    if(count($ids) > 1){
        $contagem = count($ids) - 1;
        foreach($ids as $key => $id){
            if($key >= $contagem){
                break;
            }
            $del_arr[] = $id;
        }
    }
}
foreach($del_arr as $id){
    mysqli_query($conn, "UPDATE musicas_import SET deletado = 1 WHERE id = '{$id}'") or die(mysqli_error());
    mysqli_query($conn, "UPDATE musicas_yt SET deletado = 1 WHERE id = '{$id}'") or die(mysqli_error());
}
echo "\nTotal Deletados: ".count($del_arr);
?>