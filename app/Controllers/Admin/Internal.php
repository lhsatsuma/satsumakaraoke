<?php
namespace App\Controllers\Admin;

class Internal extends AdminBaseController
{
	public $module_name = 'Internal';
	public $data = array();
	public $generic_filter = true;
    private $microtime_start;
    private $microtime_end;

    public function __construct()
    {
        parent::__construct();
        hasPermission(6, 'r', true);
        $this->microtime_start = microtime(true);
    }

    private function calcExectime()
    {
        $this->microtime_end = microtime(true);
        return number_format($this->microtime_end - $this->microtime_start, 5, '.', ',');
    }
	
	public function ExtButtonsGenericFilters()
	{
		return array(
			'new' => '<a class="btn btn-outline-success btn-rounded" href="'.$this->base_url.'admin/usuarios/editar">'.translate('LBL_NEW_RECORD').'</a>',
		);
	}
	
	public function index()
	{
		$this->data['title'] = 'Configurações internas da aplicação';
		
		return $this->displayNew('pages/Admin/Internal/index');
	}

    public function clearCache()
    {
        /*
        Clear all cache files from writable/cache/, writable/logs/ and writable/uploads/
        */
        $files = scan_dir(WRITEPATH . 'cache');
        $total_files = count($files);
        $deleted = 0;
        foreach($files as $file){
            if(is_dir(WRITEPATH . 'cache/'.$file)){
                $files_dir = scan_dir(WRITEPATH . 'cache/'.$file);
                $total_files += count($files_dir);
                foreach($files_dir as $file_dir){
                    //Dont remove index.html and htaccess files
                    if(!in_array($file_dir, ['index.html', '.htaccess'])){
                        unlink(WRITEPATH . 'cache/'.$file.'/'.$file_dir);
                        $deleted++;
                    }
                }
            }
            //Dont remove index.html and htaccess files
            if(!in_array($file, ['index.html', '.htaccess'])){
                unlink(WRITEPATH . 'cache/'.$file);
                $deleted++;
            }
        }

        /* DELETE LOGS */
        $files = scan_dir(WRITEPATH . 'logs');
        $total_files += count($files);
        foreach($files as $file){
            //Dont remove index.html and htaccess files
            if(!in_array($file, ['index.html', '.htaccess'])
            && filemtime($file) <= strtotime("Y-m-d H:i:s")-172800){
                unlink(WRITEPATH . 'logs/'.$file);
                $deleted++;
            }
        }

        /* DELETE UPLOADS */
        $files = scan_dir(WRITEPATH . 'uploads');
        $total_files += count($files);
        foreach($files as $file){
            //Dont remove index.html and htaccess files
            if(!in_array($file, ['index.html', '.htaccess'])
            && filemtime($file) <= strtotime("Y-m-d H:i:s")-172800){
                unlink(WRITEPATH . 'uploads/'.$file);
                $deleted++;
            }
        }

        $msg_return = 'Arquivos cache deletados com sucesso! Tempo: '.$this->calcExectime().'ms';
        $msg_return .= "<br/>{$total_files} arquivo(s) encontrado(s) na pasta.";
        $msg_return .= "<br/>{$deleted} arquivo(s) foram deletado(s).";

        $this->setMsgData('success', $msg_return);
        rdct('/admin/internal/index');
    }

    public function clearWaitListThread()
    {
        /*
        Clear all cache files from writable/cache/, writable/logs/ and writable/uploads/
        */
        $files = [
            WRITEPATH . 'utils/thread.json',
            WRITEPATH . 'utils/threadCopy.json',
            WRITEPATH . 'utils/line_music.json',
        ];
        $total_files = count($files);
        $deleted = 0;
        foreach($files as $file){
            if(file_exists($file)){
                unlink($file);
                $deleted++;
            }
        }

        $msg_return = 'Arquivos deletados com sucesso! Tempo: '.$this->calcExectime().'ms';
        $msg_return .= "<br/>{$total_files} arquivo(s) a serem verificado(s).";
        $msg_return .= "<br/>{$deleted} arquivo(s) foram deletado(s).";

        $this->setMsgData('success', $msg_return);
        rdct('/admin/internal/index');
    }

    public function deleteSessions()
    {
        /*
        Clear all session files from writable/session/
        */
        $files = scan_dir(WRITEPATH . 'session');
        $total_files = count($files);
        $deleted = 0;
        foreach($files as $file){

            //Dont remove index.html and htaccess files | dont delete actual session
            if(!in_array($file, ['index.html', '.htaccess'])
            && !strstr($file, session_id())){
                unlink(WRITEPATH . 'session/'.$file);
                $deleted++;
            }
        }

        $msg_return = 'Sessões deletadas com sucesso! Tempo: '.$this->calcExectime().'ms';

        $msg_return .= "<br/>{$total_files} arquivo(s) encontrado(s) na pasta.";
        $msg_return .= "<br/>{$deleted} arquivo(s) foram deletado(s).";

        $this->setMsgData('success', $msg_return);
        rdct('/admin/internal/index');
    }

    public function pruneDatabase()
    {
        /*
        Delete records from database where deleted = 1
        */

        $model = new \CodeIgniter\Model();
        $model->setTable('usuarios');

        $tables = $model->listTables();

        $tables_msg = '';
        foreach($tables as $table){
            $tables_msg .= "<br/><br />Analisando {$table}...";

            /* Check if table has deleted field */
            $fields = $model->getFieldNames($table);
            if(in_array('deleted', $fields)){

                /* Get total records deleted */
                $countTotal = $model->query("SELECT count(*) as total FROM {$table} WHERE deleted = '1'");
                $result = (int)$countTotal->getResult()[0]->total;

                $tables_msg .= "<br/>Tabela {$table} possui {$result} registros deletados";

                if($result > 0){
                    $query = $model->query("DELETE FROM {$table} WHERE deleted = '1'");
                    if($query){
                        $affected = $model->affectedRows();
                        $tables_msg .= "<br/>{$affected} registros deletados da tabela {$table}";
                    }else{
                        $tables_msg .= "<br/>Ocorreu um erro ao deletar os registros da tabela {$table}";
                    }
                }
            }else{
                $tables_msg .= "<br/>Tabela {$table} não possui campo deleted";
            }
        }
        $msg_return = 'Registros deletados com sucesso! Tempo: '.$this->calcExectime().'ms';
        $msg_return .= $tables_msg;
        
        $this->setMsgData('success', $msg_return);
        rdct('/admin/internal/index');
    }

    public function deleteArquivos()
    {
        /*
        Delete files from upload where doenst exist on database
        */

        $model = new \App\Models\Files\Files();

        $files = scan_dir(ROOTPATH . 'public/uploads/');
        $total_files = count($files);
        $deleted = 0;
        foreach($files as $file){
            //Dont remove index.html and htaccess files
            if(!in_array($file, ['index.html', '.htaccess'])){
                $model->where['arquivo'] = $file;
                $result = $model->search(1);
                if(!$result){
                    unlink(ROOTPATH . 'public/uploads/'.$file);
                    $deleted++;
                }
            }
        }

        $msg_return = 'Arquivos uploads deletados com sucesso! Tempo: '.$this->calcExectime().'ms';
        $msg_return .= "<br/>{$total_files} arquivo(s) encontrado(s) na pasta.";
        $msg_return .= "<br/>{$deleted} arquivo(s) foram deletado(s).";
        
        $this->setMsgData('success', $msg_return);
        rdct('/admin/internal/index');
    }

    public function deleteMusics()
    {
        /*
        Delete files from upload where doenst exist on database
        */

        $model = new \App\Models\Musicas\Musicas();

        $files = scan_dir(ROOTPATH . 'public/uploads/');
        $total_files = count($files);
        $deleted = 0;
        $cmd_del = '';
        foreach($files as $file){
            //Dont remove index.html and htaccess files
            if(strlen($file) == 32){
                $md5 = str_replace('.mp4', '', $file);
                $model->where['md5'] = $md5;
                $result = $model->search(1);
                if(!$result){
                    if($cmd_del){
                        $cmd_del .= '<br />';
                    }
                    $cmd_del .= "del {$file};";
                    $deleted++;
                }
            }
        }
        $msg_return = 'Arquivos uploads deletados com sucesso! Tempo: '.$this->calcExectime().'ms';
        $msg_return .= "<br/>{$total_files} arquivo(s) encontrado(s) na pasta.";
        $msg_return .= "<br/>{$deleted} arquivo(s) irão ser deletado(s).";

        if($cmd_del){
            $msg_return .= "<br/>
            Execute o comando abaixo no CMD:
            <br />
            <hr />
            <p>
                <textarea id='sqlRepair' class='form-control' rows='20'>{$cmd_del}</textarea>
            </p>
            <p>
                <button type='button' class='btn btn-outline-success btn-rounded' onclick=\"$('#sqlRepair').select();document.execCommand('copy');\">Copiar</button>
            </p>
            <script type='text/javascript'>
                $('#sqlRepair').val($('#sqlRepair').val().replace(/<br *\\/?>/gi, '\\n'));
            </script><hr />";
        }
        
        $this->setMsgData('success', $msg_return);
        rdct('/admin/internal/index');
    }

    public function reconstructDB($complete = false)
    {
        /*
        Reconstruct database with models definitions
        */
        $model = new \CodeIgniter\Model();
        $model->setTable('usuarios');

        getModules();
        $models = getModules();
        $sqlRepair = '';
        $tablesList = (!$complete) ? $model->listTables() : [];

        foreach($models as $className => $name){
            $sqlRepairTable = '';
            $mdl = new $className();

            $tableExists = in_array($mdl->table, $tablesList);
            $previousField = null;

            /* Check if table already exists, if it does, just try to repair */
            if ($tableExists){
                $fieldsDB = (array)$mdl->getFieldData($mdl->table);
            }else{
                $fieldsDB = [];
                $sqlRepairTable .= (($sqlRepairTable) ? "<br />" : "") ."CREATE TABLE {$mdl->table} (";
            }

            /* Checking every field */
            foreach($mdl->fields_map as $field => $options){
                if($options['nondb']){
                    continue;
                }

                $fieldInDB = null;
                $needUpdate = false;
                $isAdd = false;

                if($tableExists){
                    /* Get metadata from database */
                    foreach($fieldsDB as $fieldDBSearch){
                        $fieldDBSearch = (array)$fieldDBSearch;
                        if($fieldDBSearch['name'] == $field){
                            $fieldInDB = $fieldDBSearch;
                            break;
                        }
                    }
                }

                /* Get the type of field for database */
                $typeDB = $this->getTypeFieldDB($options['type']);

                /* Sets the max_length property accordingly to model definition */
                if($options['type'] == 'int'){
                    $maxLength = ($options['max_length'] > $this->getMaxLengthFieldDB('int') || !isset($options['max_length'])) ? $this->getMaxLengthFieldDB('int') : $options['max_length'];
                }elseif($options['type'] == 'related'){
                    $maxLength = 36;
                }elseif($options['type'] == 'currency'){
                    $maxLength = 8;
                }elseif(isset($options['max_length'])){
                    $maxLength = $options['max_length'];
                }else{
                    $maxLength = $this->getMaxLengthFieldDB($typeDB);
                }
                
                if(!$tableExists){
                    //If table don't exists, just put field and type
                    $sqlRepairTable .= (($sqlRepairTable) ? "<br />" : "") ."{$field} {$typeDB}";
                    if($options['dont_generate']){
                        $sqlRepairTable .= " NOT NULL AUTO_INCREMENT";
                    }
                    $needUpdate = true;
                }elseif($tableExists && !$fieldInDB){
                    //If table exists but field don't, let's try an ADD COLUMN
                    $sqlRepairTable .= (($sqlRepairTable) ? "<br />" : "") ."ALTER TABLE {$mdl->table} ADD {$field} {$typeDB}";
                    if($options['dont_generate']){
                        $sqlRepairTable .= " NOT NULL AUTO_INCREMENT";
                    }
                    $needUpdate = true;
                    $isAdd = true;
                }elseif(
                    (
                        $tableExists &&
                        $fieldInDB['type'] !== $typeDB
                        || 
                        $fieldInDB['max_length'] !== $maxLength
                    )
                    ||
                    (
                        isset($options['default']) && !is_null($options['default'])
                        && $options['default'] != $fieldInDB['default']
                    )
                ){
                    //If table and field exists but there's something different, let's try an MODIFY COLUMN
                    $sqlRepairTable .= (($sqlRepairTable) ? "<br />" : "") ."ALTER TABLE {$mdl->table} MODIFY COLUMN {$field} {$typeDB}";
                    if($options['dont_generate']){
                        $sqlRepairTable .= " NOT NULL AUTO_INCREMENT";
                    }
                    $needUpdate = true;
                }

                if($needUpdate){
                    /* If field needs to update, let's make the definitions of */
                    if($maxLength){if($options['type'] == 'currency'){
                            $sqlRepairTable .= '('.$maxLength.','.$options['parameter']['precision'].')';
                        }elseif($options['type'] == 'float'){
                            $sqlRepairTable .= '('.$maxLength.','.$options['parameter']['precision'].')';
                        }elseif($typeDB !== 'datetime' && $typeDB !== 'bool'){
                            $sqlRepairTable .= '('.$maxLength.')';
                        }
                    }
                    if(isset($options['default']) && $typeDB == 'tinyint'){
                        $sqlRepairTable .= ' DEFAULT '. (($options['default']) ? "TRUE" : "FALSE");
                    }elseif(isset($options['default']) && !is_null($options['default'])){
                        $sqlRepairTable .= ' DEFAULT '.(is_string($options['default']) ? "'{$options['default']}'" : $options['default']);
                    }

                    if($tableExists && $isAdd && $previousField){
                        //Insert field after previousField in model definition if possibly
                        $sqlRepairTable .= " AFTER ".$previousField.";";
                    }elseif($tableExists && $isAdd){
                        //Insert field in first of the table
                        $sqlRepairTable .= " FIRST;";
                    }elseif(!$tableExists){
                        $sqlRepairTable .= ",";
                    }else{
                        $sqlRepairTable .= ";";
                    }
                }
                
                $previousField = $field;
            }
            if(!$tableExists){
                $pks_fields = ($mdl->pks_table) ? implode(', ',$mdl->pks_table) : 'id';
                $sqlRepairTable .= (($sqlRepairTable) ? "<br />" : "") ."PRIMARY KEY ({$pks_fields})<br />) ENGINE = InnoDB;";
            }
            $sqlRepairIdx = '';
            foreach($mdl->idx_table as $keyIdx => $fieldsIdx){
                $sqlIdx = $mdl->getIdxSQL($keyIdx, $complete);
                $sqlRepairIdx .= ($sqlIdx) ? "<br />".$sqlIdx : '';
            }
            if($sqlRepairIdx){
                $sqlRepairTable .= "<br /><br />-- SQL INDEX FOR TABLE {$mdl->table}".$sqlRepairIdx;
            }
            if($sqlRepairTable){
                if($sqlRepair){
                    $sqlRepair .= "<br /><br />";
                }
                $sqlRepair .= "/*<br />SQL FOR TABLE {$mdl->table}<br />*/<br />".$sqlRepairTable;
            }
        }
        $msg_return = 'Reconstrução do banco de dados verificado! Tempo: '.$this->calcExectime().'ms';
        if($sqlRepair){
            $msg_return .= "<br/>
                Execute o SQL abaixo para que as alterações sejam feitas:
                <br />
                <hr />
                <p>
                    <textarea id='sqlRepair' class='form-control' rows='20'>{$sqlRepair}</textarea>
                </p>
                <p>
                    <button type='button' class='btn btn-outline-success btn-rounded' onclick=\"$('#sqlRepair').select();document.execCommand('copy');\">Copiar</button>
                </p>
                <script type='text/javascript'>
                    $('#sqlRepair').val($('#sqlRepair').val().replace(/<br *\\/?>/gi, '\\n'));
                </script><hr />";
        }else{
            $msg_return .= "<br/>Nada a se fazer...";
        }
        $this->setMsgData('success', $msg_return);
        rdct('/admin/internal/index');
        exit;
    }

    public function reconstructDBComplete()
    {
        $this->reconstructDB(true);
    }

    public function reorderMusics()
    {
        ini_set('max_execution_time', 0);
        $model = new \App\Models\Musicas\Musicas();
        $model->select = "id, name";
        $model->order_by['TRIM(name)'] = 'ASC';

        $results = $model->search();
        $totalResults = count($results);
        $codigo = 1;

        foreach($results as $result){
            $modelUpdate = new \App\Models\Musicas\Musicas();
            $modelUpdate->f['id'] = $result['id'];
            $modelUpdate->f['codigo'] = $codigo;
            $modelUpdate->saveRecord();
            $codigo++;
        }

        $msg_return = 'Reordenação de códigos de músicas realizado com sucesso! Tempo: '.$this->calcExectime().'ms';
        $msg_return .= "<br/>{$totalResults} registro(s) encontrado(s) na tabela.";
        $msg_return .= "<br/>{$codigo} registro(s) foram afetados.";
        
        $this->setMsgData('success', $msg_return);
        rdct('/admin/internal/index');
    }

    public function reconstructTotalMusics()
    {
        $model = new \App\Models\Musicas\Musicas();
        $model->select = "count(*) as total, tipo";
        $model->group_by = 'tipo';

        $results = $model->search();
        $json = [
            'total' => 0
        ];
        foreach($results as $result){
            $result['tipo'] = str_replace("/", "", strtolower($result['tipo']));
            $json['total'] += $result['total'];
            $json[$result['tipo']] = $result['total'];
        }
        file_put_contents(WRITEPATH . 'utils/total_musics.json', json_encode($json));
        $msg_return = 'Reordenação de códigos de músicas realizado com sucesso! Tempo: '.$this->calcExectime().'ms';
        $msg_return .= "<br/>{$json['total']} registro(s) encontrado(s) na tabela.";
        
        $this->setMsgData('success', $msg_return);
        rdct('/admin/internal/index');
    }

    public function reconstructDurationVideos()
    {
        $model = new \App\Models\Musicas\Musicas();
        $model->select = "id, md5";
        $model->where['BEGINORWHERE_duration'] = 0;
        $model->where['ENDORWHERE_duration'] = ['IS_NULL'];

        $results = $model->search();
        $total = count($results);
        $total_updated = 0;
        $getID3 = new \getID3();
        foreach($results as $result){
            $file_path = FCPATH . 'uploads/'.$result['md5'];
            if(file_exists($file_path)){
                $analized = $getID3->analyze($file_path);
                if($analized){
                    $duration = (int)$analized['playtime_seconds'];
                    $model->f = [];
                    $model->f['duration'] = $duration;
                    $model->f['id'] = $result['id'];
                    $model->saveRecord();
                    $total_updated++;
                }
            }
        }


        $msg_return = 'Reordenação de códigos de músicas realizado com sucesso! Tempo: '.$this->calcExectime().'ms';
        $msg_return .= "<br/>{$total} registro(s) encontrado(s) na tabela.";
        $msg_return .= "<br/>{$total_updated} registro(s) atualizados.";
        
        $this->setMsgData('success', $msg_return);
        rdct('/admin/internal/index');
    }

    private function getTypeFieldDB(string $type)
    {
        $returnVal = $type;
        switch($type){
            case 'link':
            case 'file':
            case 'email':
            case 'telephone':
            case 'celphone':
            case 'cep':
            case 'related':
            case 'dropdown':
            case 'password':
                $returnVal = 'varchar';
                break;
            case 'bool':
                $returnVal = 'tinyint';
                break;
            case 'currency':
            case 'float':
                $returnVal = 'decimal';
                break;
            case 'html':
                $returnVal = 'text';
                break;
            default:
                break;
        }
        return $returnVal;
    }

    private function getMaxLengthFieldDB(string $type)
    {
        $returnVal = null;
        switch($type){
            case 'varchar':
                $returnVal = 255;
                break;
            case 'decimal':
                $returnVal = 8;
                break;
            case 'tinyint':
                $returnVal = 1;
                break;
            default:
                break;
        }
        return $returnVal;
    }
}