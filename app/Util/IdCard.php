<?php
/**
 * Author: CHQ.
 * Time: 2016/8/10 12:39
 * Usage:
 * Update:
 */
class IdCard{
    /**
     * 根据身份证号判断性别
     * @param $idcard  18位或者15位身份证号码
     * @return string  男性用M标识，女性用F标识。若参数不是15位或18位返回空字符串。
     */
    public static function judgeGender($idcard){
        $length = strlen($idcard);
        if(15 === $length){
            $characterNumber = (int)substr($idcard, -1, 1);
        }elseif (18 === $length){
            $characterNumber = (int)substr($idcard, -2, 1);
        }else{
            return '';
        }
        return (($characterNumber & 1) === 1) ? 'M' : 'F';
    }
}