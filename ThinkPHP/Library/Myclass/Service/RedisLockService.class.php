<?php
/**
 *  Redis锁操作类
 *
 *  Func:
 *  public  lock    获取锁
 *  public  unlock  释放锁
 */
class RedisLockService { // class start

    private $_redis;

    /**
     * 初始化
     */
    public function __construct(){
        // 实例化redis
        $this->_redis = myclass('RedisExt', 'Redis');
    }

    /**
     * 获取锁
     * @param  String  $key    锁标识
     * @param  Int     $expire 锁过期时间
     * @return Boolean
     */
    public function lock($key, $expire=5){
        $is_lock = $this->_redis->setnx($key, time()+$expire);
        // 不能获取锁
        if(!$is_lock){
            // 判断锁是否过期
            $lock_time = $this->_redis->get($key);
            // 锁已过期，删除锁，重新获取
            if(time()>$lock_time){
                $this->unlock($key);
                $is_lock = $this->_redis->setnx($key, time()+$expire);
//                echo $is_lock;
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
        return $this->_redis->del($key);
    }


}