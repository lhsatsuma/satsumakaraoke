<?php
namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception as ExceptionAlias;
use Psr\Log\LoggerInterface;

class PayloadController extends AdminBaseController
{
    protected $module_name = 'Payload';

    protected static $payload_modules = [
        'musics' => \App\Controllers\Musics::class,
    ];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        hasPermission(12, 'r', true);
    }

    public function index()
    {
        echo 'root not allowed';exit;
    }

    public static function getStatusPayload()
    {
        $return_status = [];
        try {
            $path_payload = WRITEPATH . 'utils/payload/payload_status.json';
            if (!file_exists($path_payload)) {
                throw new ExceptionAlias('Payload not available', 1);
            }
            $payload_status = self::getPayloadFile();

            if(!$payload_status['status']){
                throw new ExceptionAlias('Payload not available', $payload_status['code']);
            }

            $android_info = getParameterValue('android_app_info');
            $android_info = explode('|', $android_info);
            $payload_status['data']['android_app'] = [
                'version' => $android_info[0],
                'hash' => $android_info[1],
            ];

            $return_status['status'] = 1;
            $return_status['data'] = $payload_status['data'];
        }catch(ExceptionAlias $e){
            $return_status['status'] = 0;
            $return_status['msg'] = $e->getMessage();
            $return_status['code'] = $e->getCode() ?? 503;
        }

        return $return_status;
    }

    public static function getPayloadFile(string $file_name = 'payload_status')
    {
        return json_decode(file_get_contents(WRITEPATH . 'utils/payload/'.$file_name.'.json'), true);
    }


    public static function setPayloadFile(string $module, array $data)
    {
        return file_put_contents(WRITEPATH . 'utils/payload/'.$module.'.json', json_encode($data));
    }

    public static function setJSONStatus(int $code, array | null $data = null)
    {
        $valid_payload = [
            'status' => !$code ? 1 : 0,
            'code' => $code,
        ];
        if($data){
            $valid_payload['data'] = $data;
        }
        return self::setPayloadFile('payload_status', $valid_payload);
    }

    public static function resetPayloadFiles()
    {
        if(file_exists(WRITEPATH . 'utils/payload/')) {
            $files = scan_dir(WRITEPATH . 'utils/payload/');
            foreach($files as $file){
                unlink($file);
            }
        }
    }


    public static function generatePayload()
    {
        $date_start = date('Y-m-d H:i:s');
        if(!file_exists(WRITEPATH . 'utils/payload/')) {
            if (!mkdir(WRITEPATH . 'utils/payload/')) {
                log_message('critical', 'Error creating dir: ' . WRITEPATH . 'utils/payload/');
                return false;
            }
        }
        self::resetPayloadFiles();
        self::setJSONStatus(1);

        $total_records = [];
        foreach(self::$payload_modules as $module_name => $class_payload){

            $payload = $class_payload::generatePayload();
            if(!$payload['status']){
                self::setJSONStatus(2);
                return false;
            }
            self::setPayloadFile($module_name, $payload['data']);
            $total_records[$module_name] = $payload['total'];
        }

        self::setJSONStatus(0, [
            'date' => $date_start,
            'hash' => create_guid(),
            'total_records' => $total_records
        ]);

        return true;
    }

    public static function getPayload()
    {
        $data_payload = [];

        foreach(self::$payload_modules as $module_name => $class_payload){
            $data_payload[$module_name] = self::getPayloadFile($module_name);
        }

        return $data_payload;
    }
}