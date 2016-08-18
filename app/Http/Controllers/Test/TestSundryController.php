<?php
namespace App\Http\Controllers\Test;

use Illuminate\Support\Facades\Request;
use App\Util\Log\LoggerFacade;
use App\Util\QrCodeCreater;
use App\Util\IdCard;

/**
 * Author: CHQ.
 * Time: 2016/8/8 10:23
 * Usage:
 * Update:
 */
class TestSundryController extends TestBaseController
{
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


    public function getIdcard()
    {
        $id = Request::input('card');
        var_dump($id);
        $gender = IdCard::judgeGender($id);
        $check = IdCard::checkIdNumber($id);
        var_dump($gender, $check);
    }
}