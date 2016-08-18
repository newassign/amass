<?php
namespace App\Util;
/**
 * Author: CHQ.
 * Time: 2016/8/10 12:39
 * Usage:
 * Update:
 */
class IdCard
{
    /**
     * 根据身份证号判断性别
     * @param string $idNumber 18位或者15位身份证号码
     * @return string 男性用M标识，女性用F标识。若参数不是15位或18位返回空字符串。
     */
    public static function judgeGender($idNumber)
    {
        $length = strlen($idNumber);
        if (15 === $length) {
            $characterNumber = (int)substr($idNumber, -1, 1);
        } elseif (18 === $length) {
            $characterNumber = (int)substr($idNumber, -2, 1);
        } else {
            return '';
        }
        return (($characterNumber & 1) === 1) ? 'M' : 'F';
    }


    /**
     * 依据GB11643-1999标准，判断身份证号码是否合法
     * @param string $idNumber 18位身份证号码
     * @return array
     */
    public static function checkIdNumber($idNumber)
    {
        if (18 !== strlen($idNumber)) {
            return ['valid' => false, 'msg' => '不合法的身份证号码。身份证号长度不是18位！'];
        }
        // 前17个位置上的加权因子
        $weightFactor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        // 余数-校验码对照表
        $checkCode = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
        $arr = str_split(substr($idNumber, 0, 17), 1);
        array_walk($arr, function (&$value, $key) use ($weightFactor) {
            $value = (int)$value * $weightFactor[$key];
        });
        $remainder = array_sum($arr) % 11;
        $validCode = $checkCode[$remainder];
        return (substr($idNumber, -1, 1) === $validCode) ? ['valid' => true, 'msg' => '合法的身份证号码'] : ['valid' => false, 'msg' => '不合法的身份证号码。计算得到的校验值为' . $validCode];
    }
}