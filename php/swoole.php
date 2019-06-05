<?php

/**
 * Created by PhpStorm.
 * User: huasx huashunxin01@gmail.com
 * Date: 7/28/16 22:26
 */
//之前写的一个简单的Swoole例子

define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', dirname(dirname(dirname(__DIR__))) . DS);

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 'On');
ini_set('memory_limit', '2048M');
$_ENV['DEBUG'] = true;

class SwooleMassSend
{
    private $serv;
    private $host = '127.0.0.1';
    //注意一个任务一个端口号
    private $port = 9501;


    public function __construct()
    {
        $this->serv = new swoole_server($this->host, $this->port);
        $this->serv->set(array(
            'process_name'      => 'swoole_mass_send', //swoole 进程名称
            'worker_num'        => 1,
            'task_worker_num'   => 10,
            'open_cpu_affinity' => true,
            'daemonize'         => false,
            'max_request'       => 10,
            'dispatch_mode'     => 10000,
            'debug_mode'        => 1,
            'log_file'          => 'swoolw_log',
            'open_tcp_nodelay'  => true,
            "task_ipc_mode "    => 3,
            'task_max_request'  => 10000
        ));

        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Task', array($this, 'onTask'));
        $this->serv->on('Finish', array($this, 'onFinish'));
        $this->serv->on('Close', array($this, 'onClose'));

        $this->serv->start();
    }

    /**
     * 设置swoole进程名称
     *
     * @param string $name swoole进程名称
     */
    private function setProcessName($name)
    {
        if (function_exists('cli_set_process_title')) {
            cli_set_process_title($name);
        } else {
            if (function_exists('swoole_set_process_name')) {
                swoole_set_process_name($name);
            } else {
                throw new RuntimeException(__METHOD__ . " failed. require cli_set_process_title or swoole_set_process_name.");
            }
        }
    }

    /**
     * worker start 加载业务脚本常驻内存
     *
     * @param $serv
     * @param $work_id
     */
    public function onWorkerStart($serv, $work_id)
    {
        if ($work_id >= $serv->setting['worker_num']) {
            $this->setProcessName($serv->setting['process_name'] . '-task');
        } else {
            $this->setProcessName($serv->setting['process_name'] . '-event');
        }
    }

    public function onStart($serv)
    {
        echo "Start\n";
        $client = new swoole_client(SWOOLE_SOCK_TCP);
        $client->connect($this->host, $this->port, 1);
        $client->send('start');
        $client->close();
    }

    public function onConnect($serv, $fd, $from_id)
    {
        e("Connect $fd:$from_id");
    }

    public function onReceive($serv, $fd, $from_id, $data)
    {
        $i=20;
        if ($data == 'start') {

            $openids = [];
            do {
                $i--;
                try {
                    if ($serv->stats()['tasking_num'] >= $serv->setting['task_worker_num'] - 1) {
                        sleep(1);
                        continue;
                    }
                    echo ("onReceive:准备分配任务，当前任务数:" . $serv->stats()['tasking_num']) . PHP_EOL;

                    $task = $serv->task([
                        'msg' => date("Y-m-d H:i:s"),
                    ]);
                    echo ("onReceive:分配的任务id:" . $task) . PHP_EOL;
                    echo ("onReceive:分配任务之后，当前任务数:" . $serv->stats()['tasking_num']) . PHP_EOL;
                } catch (Exception $e) {
                    var_dump([
                        "exception_msg" => $e->getMessage(),
                        "code"          => $e->getCode(),
                        "line"          => $e->getLine(),

                    ]);
                }
            } while (1);
        }
    }

    /**
     * 任务执行，请注意，本函数内的$this和onReceive中的$this并不是同一个实例
     *
     * @param $serv
     * @param $task_id
     * @param $from_id
     * @param $data
     * @return bool
     */
    public function onTask($serv, $task_id, $from_id, $data)
    {
        echo ("接收到任务 task_id :" . $task_id) . PHP_EOL;
        echo("onTask from_id :" . $from_id) . PHP_EOL;
        file_get_contents("http://test.com/crm_test.php");
        echo("调用结束 :" . $task_id) . PHP_EOL;
        $serv->finish("finishfinishfinishfinishfinish");
        return "return";
    }

    public function onFinish($serv, $task_id, $data)
    {
        echo ("任务结束 task_id :" . $task_id) . PHP_EOL;
        echo ("任务结束 data :" . $data) . PHP_EOL;
        echo "Finish {$task_id}\n";
    }

    public function onClose($serv, $fd, $from_id)
    {
        echo "Client {$fd} close connection\n";
    }
}

// 启动服务器
$server = new SwooleMassSend();
