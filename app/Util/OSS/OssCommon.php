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

    private static function getConfig()
    {
        return [
            'id' => config('ossconfig.OSS_ACCESS_ID'),
            'secret' => config('ossconfig.OSS_ACCESS_SECRET'),
            'endpoint' => config('ossconfig.OSS_ENDPOINT_OUTER'),
        ];
    }

    /**
     * 根据Config配置，得到一个OssClient实例
     * @return null|OssClient 返回值为一个OssClient实例或者null
     */
    public static function getOssClient()
    {
        try {
            $cfg = self::getConfig();
            $ossClient = new OssClient($cfg['id'], $cfg['secret'], $cfg['endpoint'], false);
            return $ossClient;
        } catch (OssException $e) {
            $record = [
                'msg' => '调用' . __FUNCTION__ . '方法，获取OssClient实例时出现异常！异常信息为：' . $e->getMessage(),
                'fileName' => 'oss-error'
            ];
            LoggerFacade::info($record['msg'], $record['fileName']);
            return null;
        }
    }

}