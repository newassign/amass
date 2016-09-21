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
        $file = file_get_contents(storage_path() . '/upload/document/new.xlsx');
        $mycfg = ['folder'=>'upload', 'extend' => 'xlsx'];
        $uri = OssCommon::uploadFileByContent($file, $mycfg);
        var_dump($uri);
    }

}