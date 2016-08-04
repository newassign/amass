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
        $client = OssCommon::getOssClient();
        $exist = $client->doesBucketExist('jieqianmeoss01-develop');
        var_dump($exist);
    }
}