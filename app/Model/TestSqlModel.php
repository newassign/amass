<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Util\Log\LoggerFacade;

/**
 * Author: CHQ.
 * Time: 2016/8/25 17:59
 * Usage: 测试数据库操作
 * Update:
 */
class TestSqlModel{
    protected $alluserTable = 'alluser';
    protected $articleTable = 'article';


    public function doSql(){
        return DB::table($this->articleTable)
            ->whereNotExists(function($query){
                $query->select('*')->from($this->alluserTable)->whereRaw("$this->alluserTable .`uid` = $this->articleTable .`uid` ");
            })
            ->get();
    }
}
