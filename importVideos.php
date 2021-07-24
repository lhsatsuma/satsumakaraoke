<?php
ini_set('max_execution_time', 0);
define('ROOTPATH', __DIR__ . '/');
$conn = mysqli_connect("localhost", "root", "@lh1024lh@", "satsumakaraoke") or die('connection error');


$query = mysqli_query($conn, "SELECT id, link, origem, md5, nome as title
FROM musicas_import WHERE deletado = 0
ORDER BY data_criacao ASC
LIMIT 30000") or die(mysqli_error());

require_once('app/Config/Version.php');
require_once('app/Libraries/YoutubeLib.php');
require_once('app/Helpers/Sys_helper.php');


$ytLib = new \App\Libraries\YoutubeLib();

$count = 0;
$count_success = 0;
echo "\nTOTAL ROWS: ".$query->num_rows;
while($result = mysqli_fetch_assoc($query)){
    $count++;

    $exists = mysqli_query($conn, "SELECT id FROM musicas WHERE md5 = '{$result['md5']}'") or die(mysqli_error());
    if($exists->num_rows < 1){
        $result['title'] = str_replace("'", "''", $result['title']);
		
		$result['title'] = $ytLib->__clear_title($result['title']);
        $downloaded = $ytLib->importUrl($result['link'], $result['md5'], $result['title']);

        if($downloaded){
            $id = create_guid();
            $ult_cod_q = mysqli_query($conn, "SELECT MAX(CAST(codigo as UNSIGNED))+1 as codigo_ult FROM musicas") or die(mysqli_error());
            $codigo = mysqli_fetch_assoc($ult_cod_q)['codigo_ult'];
            if(is_null($codigo)){
                $codigo = 1;
            }
            $dataHoje = date("Y-m-d H:i:s");

            mysqli_query($conn, "INSERT INTO musicas (
            id,
            data_criacao,
            data_modificacao,
            nome,
            link,
            origem,
            codigo,
            md5) VALUES (
            '{$id}',
            '{$dataHoje}',
            '{$dataHoje}',
            '{$result['title']}',
            '{$result['link']}',
            '{$result['origem']}',
            '{$codigo}',
            '{$result['md5']}'
            )") or die(mysqli_error($conn));
            $count_success++;
        }else{
            mysqli_query($conn, "UPDATE musicas_import SET deletado = 1 WHERE id = '{$result['id']}'") or die(mysqli_error($conn));
            mysqli_query($conn, "UPDATE musicas_yt SET deletado = 1 WHERE id = '{$result['id']}'") or die(mysqli_error($conn));
        }
    }
    echo "\nFINISHED ".$count." OF ".$query->num_rows;
}
echo "\nFINISHED SUCCESS ".$count_success." OF ".$query->num_rows;
?>