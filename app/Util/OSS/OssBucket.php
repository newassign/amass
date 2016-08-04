<?php
namespace App\Util\OSS;

use OSS\OssClient;
use OSS\Core\OssException;
use App\Util\OSS\OssCommon;

use App\Util\Log\LoggerFacade;

/**
 * Author: CHQ.
 * Time: 2016/8/4 11:23
 * Usage: 创建一个存储空间
 * Update:
 */
class OssBucket
{
    public $ossClient;

    public function __construct()
    {
        $this->ossClient = OssCommon::getOssClient();
    }

    /**
     * 判断Bucket是否存在
     * @param string $bucket 存储空间名称
     * @return bool
     */
    public function isBucketExist($bucket)
    {
        if(!$this->ossClient){
            return false;
        }
        try {
            $res = $this->ossClient->doesBucketExist($bucket);
            return (true === $res) ? true : false;
        } catch (OssException $e) {
            //var_dump($e);
            $record = [
                'msg' => '调用' . __FUNCTION__ . '方法，判断Bucket是否存在时出现异常！异常信息为：' . $e->getMessage(),
                'fileName' => 'oss-error'
            ];
            LoggerFacade::info($record['msg'], $record['fileName']);
            return false;
        }
    }

    /**
     * 创建一个存储空间
     * @return bool|mixed 成功时返回数据，失败时返回false。
     */
    public static function createBucket($bucket)
    {
        $ossClient = self::getOssClient();
        if (null === $ossClient) {
            return false;
        }
        $acl = OssClient::OSS_ACL_TYPE_PUBLIC_READ;
        try {
            $res = $ossClient->createBucket($bucket, $acl);
            return $res;
        } catch (OssException $e) {
            $record = [
                'msg' => '调用' . __FUNCTION__ . '方法，创建存储空间时出现异常！异常信息为：' . $e->getMessage(),
                'fileName' => 'oss-error'
            ];
            LoggerFacade::info($record['msg'], $record['fileName']);
            return false;
        }
    }
}