<?php

/**
 * trait tErrmgs
 *
 * 定义错误信息处理特性
 * @author xiaozan<i@xiaozan.me>
 * @copyright (c) 2016, xiaozan
 */
trait tErrmgs {

    /**
     * 错误信息
     * @var
     */
    private $_errMessage;

    /**
     * 返回最后一次错误信息
     */
    public function getLastError() {
        return $this->_errMessage;
    }

    /**
     * 设置错误信息
     * @param $errMessage
     */
    protected function _setErrMessage($errMessage) {
        $this->_errMessage = $errMessage;
    }
}