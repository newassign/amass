<?php
/**
 * Author: CHQ.
 * Time: 2016/8/3 17:49
 * Usage: OSS配置
 * Update:
 */

return [
    // bucket名称，注意不能用生产环境的bucket，以免污染用户数据！
    'OSS_BUCKET' => env('OSS_BUCKET', 'jieqianmeoss01-develop'),

    // OSS AccessKeyId
    'OSS_ACCESS_ID' => env('OSS_ACCESS_ID', 'HIzdW5DSM8POmMkH'),

    // OSS AccessKeySecret
    'OSS_ACCESS_SECRET' => env('OSS_ACCESS_SECRET', 'si1YdjsZMYPqOOnetpwl7knkaYwiXF'),

    // OSS数据中心访问域名-外网
    'OSS_ENDPOINT_OUTER' => env('OSS_ENDPOINT_OUTER', 'oss-cn-shenzhen.aliyuncs.com'),

    // OSS数据中心访问域名-内网
    'OSS_ENDPOINT_INNER' => env('OSS_ENDPOINT_INNER', 'oss-cn-shenzhen-internal.aliyuncs.com'),

    // oss配置的自定义域名。文件访问使用这个域名
    'OSS_CUSTOM_DOMAIN' => env('OSS_CUSTOM_DOMAIN', 'static.j1i2e3q4i5a6n7m8e9t0e1s2t.jieqianme.cn')
];