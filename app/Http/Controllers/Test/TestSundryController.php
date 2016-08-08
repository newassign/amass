<?php
namespace App\Http\Controllers\Test;

use App\Util\QrCodeCreater;
use App\Util\Log\LoggerFacade;
/**
 * Author: CHQ.
 * Time: 2016/8/8 10:23
 * Usage:
 * Update:
 */
class TestSundryController extends TestBaseController{
    public function __construct()
    {
    }


    public function getQrcode()
    {
        $prm = [
            'issave' => true,
            'format' => 'png',
            'text' => 'http://www.striver.site?name=hehe',
            'withlogo' => true,
            'logo' => [
                'filename' => storage_path() . '/upload/logo.png',
                'percentage' => 0.3,
                'absolute' => true
            ]
        ];
        $res = QrCodeCreater::getQrCode($prm);
        var_dump($res);
    }

}