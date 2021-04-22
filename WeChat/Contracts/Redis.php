<?php
/**
 * Redis
 * @author        shuguang <5565907@qq.com>
 */

namespace WeChat\Contracts;

class Redis
{
    /**
     * 实例
     * @var null|object
     */
    private static $instance = null;

    /**
     * 参数
     * @var array
     */
    private static $config;

    /**
     * 设置参数
     * @param array $args
     */
    public static function setConfig(array $args)
    {
        self::$config = $args;
    }

    /**
     * 获取配置信息
     * @param  string $key 键名
     * @return mixed|array
     */
    public static function getConfig($key = null)
    {
        if ($key) {
            return self::$config[$key];
        } else {
            return self::$config;
        }
    }

    /**
     * 获取实例
     * @return object
     */
    public static function getInstance()
    {
        if (self::$instance) {
            return self::$instance;
        }
        self::$instance = new \Redis();
        self::$instance->connect(self::$config['host'], self::$config['port']);
        if (self::$config['auth']) {
            self::$instance->auth(self::$config['auth']);
        }
        self::$instance->select(self::$config['database']);
        self::$instance->setOption(\Redis::OPT_PREFIX, self::$config['prefix']); // use custom prefix on all keys
        return self::$instance;
    }

    /**
     * 批量设置
     * @param string $func  方法
     * @param string $key   键名
     * @param array $datas 值
     */
    public static function batSet($func, $key, $data)
    {
        array_unshift($data, $key);
        call_user_func_array([self::getInstance(), $func], $data);
    }
}
