<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use Think\Cache;

/**
 * Redis缓存驱动
 * 要求安装phpredis扩展：https://github.com/nicolasff/phpredis
 */
class RedisExt extends Cache {
    /**
     * 架构函数
     * @param array $options 缓存参数
     * @access public
     */
    public $handler;
    public function __construct($options=array()) {
        if ( !extension_loaded('redis') ) {
            E(L('_NOT_SUPPORT_').':redis');
        }
        $options = array_merge(array (
            'host'          => C('REDIS_HOST') ? : '127.0.0.1',
            'port'          => C('REDIS_PORT') ? : 6379,
            'timeout'       => C('REDIS_TIMEOUT') ? : false,
            'persistent'    => false,
        ),$options);
        $this->options =  $options;
        $this->options['expire'] =  isset($options['expire'])?  $options['expire']  :   C('REDIS_TIMEOUT');
        $this->options['prefix'] =  isset($options['prefix'])?  $options['prefix']  :   C('DATA_CACHE_PREFIX');
        $this->options['length'] =  isset($options['length'])?  $options['length']  :   0;
        $func = $options['persistent'] ? 'pconnect' : 'connect';
        $this->handler  = new \Redis;
        $options['timeout'] === false ?
            $this->handler->$func($this->options['host'], $this->options['port']) :
            $this->handler->$func($this->options['host'], $this->options['port'], $this->options['timeout']);
        if(C('REDIS_AUTH')){
            $this->handler->auth(C('REDIS_AUTH'));
        }
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($name) {
        N('cache_read',1);
        $value = $this->handler->get($this->options['prefix'].$name);
        $jsonData  = json_decode( $value, true );
        return ($jsonData === NULL) ? $value : $jsonData;	//检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
    }

    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value  存储数据
     * @param integer $expire  有效时间（秒）
     * @return boolean
     */
    public function set($name, $value, $expire = null) {
        N('cache_write',1);
        if(is_null($expire)) {
            $expire  =  $this->options['expire'];
        }
        $name   =   $this->options['prefix'].$name;
        //对数组/对象数据进行缓存处理，保证数据完整性
        $value  =  (is_object($value) || is_array($value)) ? json_encode($value) : $value;
        if(is_int($expire) && $expire) {
            $result = $this->handler->setex($name, $expire, $value);
        }else{
            $result = $this->handler->set($name, $value);
        }
        if($result && $this->options['length']>0) {
            // 记录缓存队列
            $this->queue($name);
        }
        return $result;
    }

    /**
     * 分布式锁
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function setnx($name, $value) {
        return $this->handler->setnx($this->options['prefix'].$name, $value);
    }

    /**
     * 获取锁
     * @param  String  $key    锁标识
     * @param  Int     $expire 锁过期时间
     * @return Boolean
     */
    public function lock($key, $expire=5){
        $is_lock = $this->handler->setnx($this->options['prefix'].$key, time()+$expire);
        // 不能获取锁
        if(!$is_lock){
            // 判断锁是否过期
            $lock_time = $this->handler->get($this->options['prefix'].$key);
            // 锁已过期，删除锁，重新获取
            if(time()>$lock_time){
                $this->unlock($key);
                $is_lock = $this->handler->setnx($this->options['prefix'].$key, time()+$expire);
            }
        }
        return $is_lock? true : false;
    }

    /**
     * 释放锁
     * @param  String  $key 锁标识
     * @return Boolean
     */
    public function unlock($key){
        return $this->handler->del($this->options['prefix'].$key);
    }

    /**
     * 移除列表中与参数 VALUE 相等的元素
     * 删除count个名称为key的list中值为value的元素。
     * count为0，删除所有值为value的元素，
     * count>0从头至尾删除count个值为value的元素，
     * count<0从尾到头删除|count|个值为value的元素
     * @param $key
     * @param $value
     * @param $count
     * @return  被移除元素的数量。 列表不存在时返回 0
     */
    public function lRem($key, $value, $count){
        return $this->handler->lRem($key, $value, $count);
    }
    
    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function rm($name) {
        return $this->handler->delete($this->options['prefix'].$name);
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function del($name) {
        return $this->handler->del($this->options['prefix'].$name);
    }
    /**
     * 清除缓存
     * @access public
     * @return boolean
     */
    public function clear() {
        return $this->handler->flushDB();
    }

}
