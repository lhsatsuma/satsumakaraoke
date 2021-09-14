<?php
namespace App\Controllers\Admin;

class Internal extends AdminBaseController
{
	public $module_name = 'Internal';
	public $data = array();
	public $generic_filter = true;

    public function __construct()
    {
        parent::__construct();
        if(($this->session->get('auth_user_admin') && $this->session->get('auth_user_admin')['tipo'] != 99)
        || ($this->session->get('auth_user')['tipo'] && $this->session->get('auth_user')['tipo'] != 99)){
            header('HTTP/1.0 403 Forbbiden');
            echo '<p>Acesso Negado!</p>';
            echo '<p><a href="'.base_url().'">Voltar para Página Inicial</a></p>';
            exit;
        }
    }
	
	public function ExtButtonsGenericFilters()
	{
		return array(
			'new' => '<a class="btn btn-success" href="'.$this->base_url.'admin/usuarios/editar">Novo +</a>',
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

        $msg_return = 'Arquivos cache deletados com sucesso!';
        $msg_return .= "<br/>{$total_files} arquivo(s) encontrado(s) na pasta.";
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

        $msg_return = 'Sessões deletadas com sucesso!';

        $msg_return .= "<br/>{$total_files} arquivo(s) encontrado(s) na pasta.";
        $msg_return .= "<br/>{$deleted} arquivo(s) foram deletado(s).";

        $this->setMsgData('success', $msg_return);
        rdct('/admin/internal/index');
    }

    public function pruneDatabase()
    {
        /*
        Delete records from database where deletado = 1
        */

        $model = new \CodeIgniter\Model();

        $tables = $model->listTables();

        $tables_msg = '';
        foreach($tables as $table){
            $tables_msg .= "<br/><br />Analisando {$table}...";

            /* Check if table has deletado field */
            $fields = $model->getFieldNames($table);
            if(in_array('deletado', $fields)){

                /* Get total records deleted */
                $countTotal = $model->query("SELECT count(*) as total FROM {$table} WHERE deletado = '1'");
                $result = (int)$countTotal->getResult()[0]->total;

                $tables_msg .= "<br/>Tabela {$table} possui {$result} registros deletados";

                if($result > 0){
                    $query = $model->query("DELETE FROM {$table} WHERE deletado = '1'");
                    if($query){
                        $affected = $model->affectedRows();
                        $tables_msg .= "<br/>{$affected} registros deletados da tabela {$table}";
                    }else{
                        $tables_msg .= "<br/>Ocorreu um erro ao deletar os registros da tabela {$table}";
                    }
                }
            }else{
                $tables_msg .= "<br/>Tabela {$table} não possui campo deletado";
            }
        }
        $msg_return = 'Registros deletados com sucesso!';
        $msg_return .= $tables_msg;
        
        $this->setMsgData('success', $msg_return);
        rdct('/admin/internal/index');
    }

    public function deleteArquivos()
    {
        /*
        Delete files from upload where doenst exist on database
        */

        $model = new \App\Models\Arquivos\Arquivosmodel();

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

        $msg_return = 'Arquivos uploads deletados com sucesso!';
        $msg_return .= "<br/>{$total_files} arquivo(s) encontrado(s) na pasta.";
        $msg_return .= "<br/>{$deleted} arquivo(s) foram deletado(s).";
        
        $this->setMsgData('success', $msg_return);
        rdct('/admin/internal/index');
    }

    public function reconstructDB()
    {
        /*
        Reconstruct database with models definitions
        */
        $model = new \CodeIgniter\Model();

        getModules();
        $models = getModules();
        $sqlRepair = '';
        $tablesList = $model->listTables();
        foreach($models as $className => $name){

            $mdl = new $className();

            $tableExists = in_array($mdl->table, $tablesList);
            $previousField = null;

            /* Check if table already exists, if it does, just try to repair */
            if ($tableExists){
                $fieldsDB = $mdl->getFieldData($mdl->table);
            }else{
                $fieldsDB = [];
                $sqlRepair .= (($sqlRepair) ? "<br />" : "") ."CREATE TABLE {$mdl->table} (";
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
                        if($fieldDBSearch->name == $field){
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
                    $maxLength = ($options['max_length']);
                }else{
                    $maxLength = $this->getMaxLengthFieldDB($typeDB);
                }
                
                if(!$tableExists){
                    //If table don't exists, just put field and type
                    $sqlRepair .= (($sqlRepair) ? "<br />" : "") ."{$field} {$typeDB}";
                    $needUpdate = true;
                }elseif($tableExists && !$fieldInDB){
                    //If table exists but field don't, let's try an ADD COLUMN
                    $sqlRepair .= (($sqlRepair) ? "<br />" : "") ."ALTER TABLE {$mdl->table} ADD {$field} {$typeDB}";
                    $needUpdate = true;
                    $isAdd = true;
                }elseif(
                    (
                        $tableExists &&
                        $fieldInDB->type !== $typeDB
                        || 
                        $fieldInDB->max_length !== $maxLength
                    )
                    ||
                    (
                        !empty($options['default'])
                        && $options['default'] != $fieldInDB->default
                    )
                ){
                    //If table and field exists but there's something different, let's try an MODIFY COLUMN
                    $sqlRepair .= (($sqlRepair) ? "<br />" : "") ."ALTER TABLE {$mdl->table} MODIFY COLUMN {$field} {$typeDB}";
                    $needUpdate = true;
                }

                if($needUpdate){
                    /* If field needs to update, let's make the definitions of */
                    if($maxLength){
                        if($options['type'] == 'currency'){
                            $sqlRepair .= '('.$maxLength.','.$options['parameter']['precision'].')';
                        }elseif($options['type'] == 'float'){
                            $sqlRepair .= '('.$maxLength.','.$options['parameter']['precision'].')';
                        }elseif($typeDB !== 'datetime' && $typeDB !== 'bool'){
                            $sqlRepair .= '('.$maxLength.')';
                        }
                    }
                    if(isset($options['default']) && $typeDB == 'tinyint'){
                        $sqlRepair .= ' DEFAULT '. (($options['default']) ? "TRUE" : "FALSE");
                    }elseif($options['default']){
                        $sqlRepair .= ' DEFAULT '.(is_string($options['default']) ? "'{$options['default']}'" : $options['default']);
                    }

                    if($tableExists && $isAdd && $previousField){
                        //Insert field after previousField in model definition if possibly
                        $sqlRepair .= " AFTER ".$previousField.";";
                    }elseif($tableExists && $isAdd){
                        //Insert field in first of the table
                        $sqlRepair .= " FIRST;";
                    }elseif(!$tableExists){
                        $sqlRepair .= ",";
                    }else{
                        $sqlRepair .= ";";
                    }
                }
                
                $previousField = $field;
            }

            if(!$tableExists){
                $sqlRepair .= (($sqlRepair) ? "<br />" : "") ."PRIMARY KEY (id)<br />) ENGINE = InnoDB;<br/>CREATE INDEX idx_id_del ON {$mdl->table} (id, deletado);<br />CREATE INDEX idx_name_del ON {$mdl->table} (nome, deletado);";
            }
        }
        $msg_return = "Reconstrução do banco de dados verificado!";
        if($sqlRepair){
            $msg_return .= "<br/>Execute o SQL abaixo para que as alterações sejam feitas:<br /><hr /><p>{$sqlRepair}</p><hr />";
        }else{
            $msg_return .= "<br/>Nada a se fazer...";
        }
        
        $this->setMsgData('success', $msg_return);
        rdct('/admin/internal/index');
        exit;
    }

    public function reorderMusics()
    {
        ini_set('max_execution_time', 0);
        $model = new \App\Models\Musicas\Musicasmodel();
        $model->select = "id, nome";
        $model->order_by['TRIM(nome)'] = 'ASC';

        $results = $model->search();
        $totalResults = count($results);
        $codigo = 1;

        foreach($results as $result){
            $modelUpdate = new \App\Models\Musicas\Musicasmodel();
            $modelUpdate->f['id'] = $result['id'];
            $modelUpdate->f['codigo'] = $codigo;
            $modelUpdate->saveRecord();
            $codigo++;
        }

        $msg_return = 'Reordenação de códigos de músicas realizado com sucesso!';
        $msg_return .= "<br/>{$totalResults} registro(s) encontrado(s) na tabela.";
        $msg_return .= "<br/>{$codigo} registro(s) foram afetados.";
        
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
            case 'int':
                // $returnVal = 11;
                break;
            default:
                break;
        }
        return $returnVal;
    }
}