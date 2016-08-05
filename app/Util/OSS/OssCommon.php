<?php
namespace App\Util\OSS;

use OSS\OssClient;
use OSS\Core\OssException;

use App\Util\Log\LoggerFacade;

/**
 * Author: CHQ.
 * Time: 2016/8/3 17:45
 * Usage: 示例程序的公共类，用于获取OssClient实例和其他公用方法
 * Update:
 */
final class OssCommon
{
    // 静态成员变量，用来保存类的唯一实例
    private static $_instance;

    // 用private修饰构造函数，防止外部程序来使用new关键字实例化这个类
    private function __construct()
    {
    }

    // 覆盖php魔术方法__clone()，防止克隆
    private function __clone()
    {
        trigger_error('Clone is not allow', E_USER_ERROR);
    }

    // 单例方法，返回类唯一实例的一个引用
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private static function getConfig($key = null)
    {
        $cfg = [
            'id' => config('ossconfig.OSS_ACCESS_ID'),
            'secret' => config('ossconfig.OSS_ACCESS_SECRET'),
            'endpoint' => config('ossconfig.OSS_ENDPOINT_OUTER'),
            'bucket' => config('ossconfig.OSS_BUCKET'),
        ];
        if(!empty($key)){
            $cfgkeys = array_keys($cfg);
            return in_array($key, $cfgkeys, true) ? $cfg[$key] : '';
        }else{
            return $cfg;
        }
    }

    /**
     * 根据Config配置，得到一个OssClient实例
     * @return null|OssClient 返回OssClient实例或者null
     */
    public static function getOssClient()
    {
        try {
            $cfg = self::getConfig();
            $ossClient = new OssClient($cfg['id'], $cfg['secret'], $cfg['endpoint'], false);
            return $ossClient;
        } catch (OssException $e) {
            $record = [
                'msg' => '获取OssClient实例时出现异常！异常信息为：' . $e->getMessage(),
                'fileName' => 'oss-exception'
            ];
            LoggerFacade::info($record['msg'], $record['fileName']);
            return null;
        }
    }


    /**
     * 判断指定名称的bucket是否存在
     * @param $bucketName
     * @return bool|null
     * Todo: 当调用此方法得到结果$exist，请这样判断 若$exist === true则存在，若$exist === false则不存在。
     */
    public static function isBucketExist($bucketName)
    {
        $ossClient = self::getOssClient();
        if (!$ossClient) {
            return null;
        }
        try {
            $res = $ossClient->doesBucketExist($bucketName);
            return (true === $res) ? true : false;
        } catch (OssException $e) {
            $record = [
                'msg' => '判断bucket是否存在时出现异常！异常信息为：' . $e->getMessage(),
                'fileName' => 'oss-exception'
            ];
            LoggerFacade::info($record['msg'], $record['fileName']);
            // TODO:由于发生异常，无法判断是否存在$bucketName，故返回null
            return null;
        }
    }


    /**
     * 判断object是否存在
     * @param $object
     * @return bool|null
     * Todo: 当调用此方法得到结果$exist，请这样判断 若$exist === true则存在，若$exist === false则不存在。
     */
    public static function isObjectExist($object)
    {
        $cfg = self::getConfig();
        $bucket = $cfg['bucket'];
        $ossClient = self::getOssClient();
        if (!$ossClient) {
            return null;
        }
        try{
            $exist = $ossClient->doesObjectExist($bucket, $object);
            return (true === $exist) ? true : false;
        } catch(OssException $e) {
            $record = [
                'msg' => '判断object是否存在时出现异常！异常信息为：' . $e->getMessage(),
                'fileName' => 'oss-exception'
            ];
            LoggerFacade::info($record['msg'], $record['fileName']);
            return null;
        }
    }


    /**
     * 上传字符串作为object的内容
     *
     * @param OssClient $ossClient OSSClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    public static function uploadImgByContent($content)
    {
        $cfg = self::getConfig();
        $bucket = $cfg['bucket'];
        $ossClient = self::getOssClient();
        if (!$ossClient) {
            return '';
        }
        $object = md5(time()).'.jpg';
        try {
            $res = $ossClient->putObject($bucket, $object, $content);
            return $object;
        } catch (OssException $e) {
            $record = [
                'msg' => '上传文件时出现异常！异常信息为：' . $e->getMessage(),
                'fileName' => 'oss-exception'
            ];
            LoggerFacade::info($record['msg'], $record['fileName']);
            return null;
        }
    }

    /**
     * 列出Bucket内所有目录和文件, 注意如果符合条件的文件数目超过设置的max-keys， 用户需要使用返回的nextMarker作为入参，通过
     * 循环调用ListObjects得到所有的文件，具体操作见下面的 listAllObjects 示例
     *
     * @param OssClient $ossClient OssClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    public static function listObjects()
    {
        $cfg = self::getConfig();
        $bucket = $cfg['bucket'];
        $ossClient = self::getOssClient();
        if (!$ossClient) {
            return null;
        }

        $prefix = '';
        $delimiter = '';
        $nextMarker = '';
        $maxkeys = 1000;
        $options = array(
            'delimiter' => $delimiter,
            'prefix' => $prefix,
            'max-keys' => $maxkeys,
            'marker' => $nextMarker,
        );
        try {
            $listObjectInfo = $ossClient->listObjects($bucket, $options);
        } catch (OssException $e) {
            //printf(__FUNCTION__ . ": FAILED\n");
            //printf($e->getMessage() . "\n");
            return;
        }
        //print(__FUNCTION__ . ": OK" . "\n");
        $objectList = $listObjectInfo->getObjectList(); // 文件列表
        $prefixList = $listObjectInfo->getPrefixList(); // 目录列表
        if (!empty($objectList)) {
            print("objectList:\n");
            foreach ($objectList as $objectInfo) {
                print($objectInfo->getKey() . "\n");
            }
        }
        if (!empty($prefixList)) {
            print("prefixList: \n");
            foreach ($prefixList as $prefixInfo) {
                print($prefixInfo->getPrefix() . "\n");
            }
        }
    }

    private static function createObjName(){

    }
}