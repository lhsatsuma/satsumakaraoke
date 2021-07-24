<?php
ini_set('max_execution_time', 0);
define('ROOTPATH', __DIR__ . '/');
$conn = mysqli_connect("localhost", "root", "@lh1024lh@", "satsumakaraoke") or die('connection error');

$total_deleted = 0;
$scanned = scandir('public/uploads/VIDEOSKARAOKE/');
foreach($scanned as $file){
    if(strpos($file, '.mp4') !== false){
        $md5 = str_replace('.mp4', '', $file);
        $query = mysqli_query($conn, "SELECT * FROM musicas WHERE deletado = 0 AND md5 = '{$md5}'") or die(mysqli_error($conn));
        if($query->num_rows < 1){
            unlink('public/uploads/VIDEOSKARAOKE/'.$file);
            echo "\nDeletado: ".$file;
            $total_deleted++;
        }
    }
}
echo "\nTotal Deletados: ".$total_deleted;
?>