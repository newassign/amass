<?php
namespace App\Util\OSS;

use OSS\OssClient;
use OSS\Core\OssException;
use App\Util\Log\LoggerFacade;

/**
 * Author: CHQ.
 * Time: 2016/8/3 17:45
 * Usage: 示例程序的公共类，用于获取OssClient实例和其他公用方法
 * Update: 2016/08/25 15:15 增加图片配置，改写createObjName()方法。
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

    /**
     * 获取OSS配置项，如果传入了$key则只返回键为$key的项，否则返回全部配置项。
     * @param null $key
     * @return array|string
     */
    private static function getConfig($key = null)
    {
        $cfg = [
            'id' => config('ossconfig.OSS_ACCESS_ID'),
            'secret' => config('ossconfig.OSS_ACCESS_SECRET'),
            'endpoint' => config('ossconfig.OSS_ENDPOINT_OUTER'),
            'bucket' => config('ossconfig.OSS_BUCKET'),
        ];
        return (null === $key) ? $cfg : (in_array($key, array_keys($cfg), true) ? $cfg[$key] : '');
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
            Logger::info($record['msg'], $record['fileName']);
            return null;
        }
    }


    /**
     * 上传文件时，生成文件名（包含路径和扩展名）
     * @param array $mycfg 举例：$mycfg = ['folder'=>'upload', 'fileName' => 'unixtime_userid', 'extend' => 'jpg']
     * @return string      举例：传入上述$mycfg则返回值类似于 upload/20160825/unixtime_userid.jpg
     */
    private static function createObjName(array $mycfg = [])
    {
        // 图片默认配置
        $imgConfig = [
            'folder' => 'common',
            'fileName' => md5(str_random(16) . time()),
            'extend' => 'jpg'
        ];
        $imgConfig = array_merge($imgConfig, $mycfg);
        $res = $imgConfig['folder'] . '/' . date('Ymd', time()) . '/' . $imgConfig['fileName'] . '.' . $imgConfig['extend'];
        return $res;
    }


    /**
     * 上传文件（通过文件内容字符串方式）
     * @param   string $content  要上传的文件的内容字符串，例如 $content = file_get_contents(storage_path().'/118.jpg')
     * @param   array $mycfg     自定义配置，例如 $mycfg = ['folder'=>'upload', 'fileName' => 'unixtime_userid', 'extend' => 'jpg']
     * @return  array            传入上述参数，返回值类似于 ['success' => true, 'data' => 'upload/20160825/unixtime_userid.jpg', 'msg' => '上传成功！']
     */
    public static function uploadFileByContent($content, array $mycfg = [])
    {
        $bucket = self::getConfig('bucket');
        $ossClient = self::getOssClient();
        if (!$ossClient) {
            return ['success' => false, 'data' => '', 'msg' => '配置有误，获取OssClient实例失败！'];
        }
        $file = self::createObjName($mycfg);
        try {
            $res = $ossClient->putObject($bucket, $file, $content);
            return $res ? ['success' => true, 'data' => $file, 'msg' => '上传成功！'] : ['success' => false, 'data' => '', 'msg' => '上传失败！'];
        } catch (OssException $e) {
            $record = [
                'msg' => '上传文件时出现异常！异常信息为：' . $e->getMessage(),
                'fileName' => 'oss-exception'
            ];
            Logger::info($record['msg'], $record['fileName']);
            return ['success' => false, 'data' => '', 'msg' => '上传文件时出现异常，上传失败！'];
        }
    }
}