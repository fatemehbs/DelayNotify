<?php


namespace App\Utility;

use Illuminate\Support\Facades\Redis;


class CacheUtils
{

    /**
     * @param string $queueName
     * @param $data
     * @return int
     */
    public static function enqueue(string $queueName, $data): int
    {
        return Redis::rpush($queueName, $data);
    }

    /**
     * @param string $queueName
     * @return mixed
     */
    public static function dequeue(string $queueName): mixed
    {
        return Redis::lpop($queueName);
    }

    /**
     * @param string $queueName
     * @return int
     */
    public static function count(string $queueName): int
    {
        return Redis::llen($queueName);
    }

    /**
     * @param string $queueName
     * @return bool
     */
    public function isEmpty(string $queueName): bool
    {
        return $this->count($queueName) == 0;
    }
}
