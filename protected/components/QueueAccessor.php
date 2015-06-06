<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 06.06.2015
 * Time: 12:57
 */
class QueueAccessor {

    const TYPE_MONGO = 'mongo';
    const TYPE_REDIS = 'redis';
    const TYPE_TARANTOOL = 'tarantool';
    const TYPE_PHEANSTALK = 'pheanstalk';
    const TYPE_PDO = 'pdo';
    const TYPE_SQLITE = 'sqlite';
    const TYPE_SYSVQ = 'sysvq';
    const TYPE_MYSQL = 'mysql';

    /** @var  \Phive\Queue\Queue\QueueInterface */
    protected $_queue;

    public function __construct($dsn, $queueName) {
        $dsn = explode(':',$dsn,2);
        $params = array_filter(explode(';',$dsn[1]));
        $args = array();
        foreach ($params as $param) {
            $param = explode('=',$param,2);
            if (sizeof($param)==2) {
                $args[$param[0]] = $param[1];
            }
        }
        switch ($dsn[0]) {
            case self::TYPE_MONGO:
                $client = new MongoClient();
                $args['coll'] = $queueName;
                $queue = new \Phive\Queue\Queue\MongoQueue($client, $args);
                break;
            case self::TYPE_REDIS:
                $redis = new Redis();
                $redis->connect($args['host']);
                $redis->setOption(Redis::OPT_PREFIX, $queueName);
                $queue = new \Phive\Queue\Queue\RedisQueue($redis);
                break;
            case self::TYPE_TARANTOOL:
                $tarantool = new Tarantool($args['host'], $args['port']);
                $queue = new \Phive\Queue\Queue\TarantoolQueue($tarantool, $args['db']);
                break;
            case self::TYPE_PHEANSTALK:
                $pheanstalk = new \Pheanstalk\Pheanstalk\Pheanstalk($args['host']);
                $queue = new Phive\Queue\Queue\PheanstalkQueue($pheanstalk, $args['db']);
                break;
            case self::TYPE_MYSQL:
                $pdo = new PDO('mysql:host='.$args['host'].';port='.$args['port'].';dbname='.$args['db'], $args['user'], $args['pass']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $queue = new Phive\Queue\Queue\Pdo\MysqlQueue($pdo, $queueName);
                break;
            case self::TYPE_SQLITE:
                $pdo = new PDO('sqlite:'.$args['filename']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $queue = new Phive\Queue\Queue\Pdo\SqliteQueue($pdo, $queueName);
                break;
            case self::TYPE_SYSVQ:
                $queue = new Phive\Queue\Queue\SysVQueue($queueName);
                break;
            default:
                throw new Exception('Unknown queue engine: '.$dsn[0]);
        }
        $this->_queue = $queue;
    }

    public function push($item, $eta = null) {
        return $this->_queue->push($item, $eta);
    }

    /**
     * @return string
     *
     * @throws \Phive\Queue\Exception\NoItemException
     */
    public function pop() {
        return $this->_queue->pop();
    }

    /**
     * Removes all items from the queue.
     */
    public function clear() {
        $this->_queue->clear();
    }
}