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
    protected  $sourceTable = 't_cashloan_customer';


    public function doSql(){
        return DB::table($this->articleTable)
            ->whereNotExists(function($query){
                $query->select('*')->from($this->alluserTable)->whereRaw("$this->alluserTable .`uid` = $this->articleTable .`uid` ");
            })
            ->get();
    }

    public function getSourceData(){
        return DB::table($this->sourceTable)
            ->orderBy('id')
            ->forPage(2, 200)
            ->get();
    }

    /**
     * 判断办单状态
     * @param array $prm 二维数组
     * @return array
     */
    public function handleOrderState(array $prm)
    {
        // 1:未办单 2:已过期 3:已办单
        $state = ['notTransact' => 1, 'expired' => 2, 'hasTransact' => 3];
        $today = strtotime(date('Y-m-d', time()));
        $treatment = [];
        array_walk($prm, function ($pval, $pkey) use ($state, $today, &$treatment) {
            $treatment[$pkey] = get_object_vars($pval);
            $treatment[$pkey]['handle_status'] = ($today >= strtotime($treatment[$pkey]['begin_date'])) ? $state['expired'] : $state['hasTransact'];
        });
        return $treatment;
    }
}
