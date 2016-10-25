<?php
namespace App\Http\Controllers\Test;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use App\Util\Log\LoggerFacade;
use App\Util\QrCodeCreater;
use App\Util\IdCard;
use App\Model\TestSqlModel;
use Maatwebsite\Excel\Facades\Excel;
use App\Util\OSS\OssCommon;

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

    public function getCurrentEnv()
    {
        $env = app()->environment();
        var_dump($env);
    }

    public function getTestDb()
    {
        $model = new TestSqlModel();
        //$res = $model->doSql();
        $data = $model->getSourceData();
        $res = $model->handleOrderState($data);
        var_dump(count($res));
    }

    public function getTestExcel()
    {
        $file = storage_path() . '/upload/document/GB2312_simple_word.xlsx';
        Excel::load($file, function ($reader) {
            //获取excel的第1张表
            $reader = $reader->getSheet(0);
            //获取表中的数据
            $result = $reader->toArray();
            var_dump($result);
        });
    }

    public function getSelectInsert(){
        $model = new TestSqlModel();
        $sourceData = $model->SelectIntoTemp();
        var_dump($sourceData);
    }

    public function getDiff(){
        $model = new TestSqlModel();
        $data = $model->getDays();
        var_dump($data);
    }

    public function getEmptyTable(){
        $tableName = 't_cashloan_customer';
        $model = new TestSqlModel();
        $res = $model->emptyTable($tableName);
        var_dump($res);
    }

    public function getHash(){
        $hash = Hash::make('newreg123');
        var_dump($hash);
    }


    public function getExtractData(){
        $file = storage_path() . '/laravel-apache2handler-fqg-info-2016-09-21.log';
        $content = file_get_contents($file);
        $length = mb_strlen($content, 'UTF-8');
        //var_dump($length);
        $failurePattern = '/^\[[^[]*登录失败/m';
        $redirectPattern = '/^\[[^[]*已登录直接跳转/m';
        $successPattern = '/^\[[^[]*登录成功/m';
        var_dump(time());
        preg_match_all($failurePattern, $content, $failure);
        preg_match_all($redirectPattern, $content, $redirect);
        preg_match_all($successPattern, $content, $success);
        var_dump(time());
        var_dump($success);
    }

    public function getPrm(){
        $p = Request::input('in');
        var_dump($p);
    }
}