<?php

require_once(dirname(__FILE__) . '/Safebase64.class.php');
require_once(dirname(__FILE__) . '/tErrmgs.php');

/**
 * Class Rsa
 *
 * 实现Rsa加解密过程，需要安装openssl扩展，使用PKCS8格式的私钥(需要设置密钥路径)
 * 使用公钥加密，私钥解密
 * @author xiaozan<i@xiaozan.me>
 * @copyright (c) 2016, xiaozan
 */
class Rsa {

    use tErrmgs;

    const ERR_PUBLIC_KEY_GONE = '公钥不存在';
    const ERR_PRIVATE_KEY_GONE = '私钥不存在';
    const ERR_PUBLIC_KEY_READ = '公钥内容读取发生错误';
    const ERR_PRIVATE_KEY_READ = '私钥内容读取发生错误';
    const ERR_PUBLIC_KEY_CONVERT = '转换公钥内容成资源发生错误';
    const ERR_PRIVATE_KEY_CONVERT = '转换私钥内容成资源发生错误';
    const ERR_PUBLIC_KEY_ENCRYPT = '执行加密过程发生错误';
    const ERR_PRIVATE_kEY_DECRYPT = '执行解密过程发生错误';

    /**
     * 公钥路径
     * @var
     */
    private $_publicKeyPath;

    /**
     * 私钥路径
     * @var
     */
    private $_privateKeyPath;

    /**
     * 加密
     * 对数据进行加密，返回经过Safebase64编码后的文本数据
     * @param string $data 需要加密的数据
     * @return mixed 加密后的数据
     */
    public function encrypt($data) {
        if (!$this->_pathExists($this->_publicKeyPath)) { //公钥不存在
            $this->_setErrMessage(self::ERR_PUBLIC_KEY_GONE);
        } elseif (!($public_key_contents = file_get_contents($this->_publicKeyPath))) { //读取公钥内容发生错误
            $this->_setErrMessage(self::ERR_PUBLIC_KEY_READ);
        } elseif (!($public_key_resource = openssl_pkey_get_public($public_key_contents))) { //转换公钥内容成资源发生错误
            $this->_setErrMessage(self::ERR_PUBLIC_KEY_CONVERT);
        } elseif (!openssl_public_encrypt($data, $result, $public_key_resource)) { //使用公钥加密发生错误
            $this->_setErrMessage(self::ERR_PUBLIC_KEY_ENCRYPT);
        } else {
            return Safebase64::encode($result);
        }
        return false;
    }

    /**
     * 解密
     * 对加密后的数据进行解密，自动对数据进行Safebase64解码
     * @param string $data 需要解密的数据
     * @return mixed 解密后的数据
     */
    public function decrypt($data) {
        if (!$this->_pathExists($this->_privateKeyPath)) { //私钥不存在
            $this->_setErrMessage(self::ERR_PRIVATE_KEY_GONE);
        } elseif (!($private_key_contents = file_get_contents($this->_privateKeyPath))) { //读取私钥内容发生错误
            $this->_setErrMessage(self::ERR_PRIVATE_KEY_READ);
        } elseif (!($private_key_resource = openssl_pkey_get_private($private_key_contents))) { //转换私钥内容成资源发生错误
            $this->_setErrMessage(self::ERR_PRIVATE_KEY_CONVERT);
        } elseif (!openssl_private_decrypt(Safebase64::decode($data), $result, $private_key_resource)) { //使用私钥加密发生错误
            $this->_setErrMessage(self::ERR_PRIVATE_kEY_DECRYPT);
        } else {
            return $result;
        }
        return false;
    }

    /**
     * 设置公钥的路径
     * @param string $path 公钥文件路径
     */
    public function setPublicKeyPath($path) {
        $this->_publicKeyPath = $path;
    }

    /**
     * 设置私钥路径
     * @param string $path 私钥文件路径
     */
    public function setPrivateKeyPath($path) {
        $this->_privateKeyPath = $path;
    }

    /**
     * 判断给定的路径是否存在
     * @param string $path 文件路径
     * @return boolean 存在：true|不存在：false
     */
    private function _pathExists($path) {
        return $path && file_exists($path);
    }

}
