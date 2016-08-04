<?php
/**
 * Author: CHQ.
 * Time: 2016/8/3 17:49
 * Usage: OSS配置
 * Update:
 */

return [
    // bucket名称，注意不能用生产环境的bucket，以免污染用户数据！
    'OSS_BUCKET' => 'jieqianmeoss01-develop',

    // OSS AccessKeyId
    'OSS_ACCESS_ID' => 'SgPBpBQmGl8pOG5W',

    // OSS AccessKeySecret
    'OSS_ACCESS_SECRET' => 'd6iQ98xx35nG32xHvWRNqve6VHgqoD',

    // OSS数据中心访问域名-内网
    'OSS_ENDPOINT_INNER' => 'oss-cn-shenzhen-internal.aliyuncs.com',

    // OSS数据中心访问域名-外网
    'OSS_ENDPOINT_OUTER' => 'oss-cn-shenzhen.aliyuncs.com',
];