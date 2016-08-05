<?php
namespace App\Http\Controllers\Test;

use App\Util\OSS\OssCommon;
use App\Util\OSS\OssBucket;

use App\Util\Log\LoggerFacade;
/**
 * Author: CHQ.
 * Time: 2016/8/3 18:04
 * Usage:
 * Update:
 */
class TestOssController extends TestBaseController{
    public function __construct()
    {
    }

    public function getRunExample(){
        //$exist = OssCommon::isObjectExist('test_1.jpg');
        //$content = file_get_contents(storage_path().'/upload/116.jpg');
        //$uri = OssCommon::uploadImgByContent($content);
        //var_dump($uri);
        $cfg = [
            'id' => config('ossconfig.OSS_ACCESS_ID'),
            'secret' => config('ossconfig.OSS_ACCESS_SECRET'),
            'endpoint' => config('ossconfig.OSS_ENDPOINT_OUTER'),
            'bucket' => config('ossconfig.OSS_BUCKET'),
        ];
        var_dump(array_keys($cfg));
        var_dump(in_array('id', array_keys($cfg), true));
    }
}