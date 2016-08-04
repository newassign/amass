<?php
namespace App\Http\Controllers\Test;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

/**
 * Author: CHQ.
 * Time: 2016/8/3 17:58
 * Usage:
 * Update:
 */
class TestBaseController extends Controller{
    public function __construct(){
        if(App::environment('product')){
            App::abort(404);
        }else{
//	        $existMark = Session::get(md5('source'));
//	        $str = Session::get(md5('pmark'));
//	        $verify = md5('/bqjieqianadmin/test_panel/test-interface/' . $str);
//	        if($verify !== $existMark){
//		        die('对不起，您没有权限访问！');
//	        }
        }
    }
}