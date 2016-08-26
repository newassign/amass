<?php
namespace App\Http\Controllers\Test;

use Illuminate\Support\Facades\Request;
use App\Util\Log\LoggerFacade;
use App\Util\QrCodeCreater;
use App\Util\IdCard;
use App\Model\TestSqlModel;
use Maatwebsite\Excel\Facades\Excel;

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

    public function getCurrentEnv(){
        $env = app()->environment();
        var_dump($env);
    }

    public function getDosql(){
        $model = new TestSqlModel();
        $res = $model->doSql();
        var_dump($res);
    }

    public function getTestExcel(){
        $file = storage_path().'/upload/document/GB2312_simple_word.xlsx';
        Excel::load($file, function($reader){
            //获取excel的第1张表
            $reader = $reader->getSheet(0);
            //获取表中的数据
            $result = $reader->toArray();
            var_dump($result);
        });
    }
}