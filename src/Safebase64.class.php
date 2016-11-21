<?php

/**
 * Class Base64
 *
 * 实现Base64编码解码过程(针对url传输安全)
 * [此处安全是指对特殊字符的处理，不涉及加密]
 * @author xiaozan <i@xiaozan.me>
 * @copyright (c) 2016, xiaozan
 */
class Safebase64 {

    /**
     * 对数据进行编码
     * @param string $data 需要编码的数据
     * @return mixed 编码后的数据
     */
    public static function encode($data) {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    /**
     * 对数据进行解码
     * @param string $data 需要解码的数据
     * @return mixed 解码后的数据
     */
    public static function decode($data) {
        $string = str_replace(['-', '_'], ['+', '/'], $data);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $string .= substr('====', $mod4);
        }
        return base64_decode($string);
    }
}