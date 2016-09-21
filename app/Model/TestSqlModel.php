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
    protected $orignalTable = 'sync_cashloan_customer';
    protected $sourceTable = 't_cashloan_customer';
    protected $justTempTable = 't_just_temp';
    protected $gatherTable = 'all_cashloan_customer';


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

    public function SelectIntoTemp(){
        $sourceData = DB::table($this->sourceTable)->lists('customer_id');
        return $sourceData;
    }

    public function getDiffCount(){
        $incrementCount = DB::table($this->gatherTable)
            ->where('handle_status', '=', 1)
            ->whereRaw(" customer_id not in (select customer_id from t_cashloan_customer) ")
            ->count();
        var_dump($incrementCount);
        $data = DB::table($this->gatherTable)
            ->where('handle_status', '=', 1)
            ->whereRaw(" customer_id not in (select customer_id from t_cashloan_customer) ")
            ->orderBy('id', 'ASC')
            ->forPage(1, 50)
            ->get();
        return $data;
    }

    public function getDays(){
        $data = DB::table($this->orignalTable)
            ->select('EVENTNAME', 'EVENTDATE', 'BEGINDATE')
            ->groupBy('EVENTNAME')
            ->get();
        $res = [];
        foreach($data as $val){
            $res[$val->EVENTNAME] = (strtotime($val->EVENTDATE) - strtotime($val->BEGINDATE)) / 86400;
            var_dump((strtotime($val->BEGINDATE) + 90 * 86400) === strtotime($val->EVENTDATE));
        }
        return $res;
    }


    public function emptyTable($tableName){
        return DB::statement("truncate table $tableName ");
    }
}
