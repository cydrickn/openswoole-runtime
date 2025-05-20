<?php

namespace Cydrickn\Runtime;

use Swoole\Http\Server;
use OpenSwoole\Process;
use Runtime\Swoole\ServerFactory as SwooleServerFactory;
use Cydrickn\PHPWatcher\Watcher;
use Cydrickn\PHPWatcher\Adapters\SwooleTimerAdapter;

class ServerFactory extends SwooleServerFactory
{
    private const DEFAULT_OPTIONS = [
        'host' => '127.0.0.1',
        'port' => 8000,
        'mode' => 2, // SWOOLE_PROCESS
        'sock_type' => 1, // SWOOLE_SOCK_TCP
        'settings' => [],
        'hot_reload' => 0,
        'base_path' => __DIR__,
    ];

    public static function getDefaultOptions(): array
    {
        return self::DEFAULT_OPTIONS;
    }

    public function __construct(array $options = [])
    {
        $options['host'] = $options['host'] ?? $_SERVER['SWOOLE_HOST'] ?? $_ENV['SWOOLE_HOST'] ?? self::DEFAULT_OPTIONS['host'];
        $options['port'] = $options['port'] ?? $_SERVER['SWOOLE_PORT'] ?? $_ENV['SWOOLE_PORT'] ?? self::DEFAULT_OPTIONS['port'];
        $options['mode'] = $options['mode'] ?? $_SERVER['SWOOLE_MODE'] ?? $_ENV['SWOOLE_MODE'] ?? self::DEFAULT_OPTIONS['mode'];
        $options['sock_type'] = $options['sock_type'] ?? $_SERVER['SWOOLE_SOCK_TYPE'] ?? $_ENV['SWOOLE_SOCK_TYPE'] ?? self::DEFAULT_OPTIONS['sock_type'];
        $options['hot_reload'] = $options['hot_reload'] ?? $_SERVER['SWOOLE_HOT_RELOAD'] ?? $_ENV['SWOOLE_HOT_RELOAD'] ?? self::DEFAULT_OPTIONS['hot_reload'];
        $options['base_path'] = $options['base_path'] ?? $_SERVER['SWOOLE_BASE_PATH'] ?? $_ENV['SWOOLE_BASE_PATH'] ?? self::DEFAULT_OPTIONS['base_path'];

        parent::__construct($options);
    }

    public function createServer(callable $requestHandler): Server
    {
        $options = $this->getOptions();
        $server = new Server($options['host'], (int) $options['port'], (int) $options['mode'], (int) $options['sock_type']);
        $server->set($options['settings']);
        $server->on('request', $requestHandler);

        if (class_exists(Watcher::class) && $options['hot_reload'] === 1) {
            $this->addHotReloadProcess($server);
        }

        return $server;
    }

    private function addHotReloadProcess(Server $server): void
    {
        $basePath = $this->getOptions()['base_path'];
        $server->addProcess(new Process(function () use ($basePath, $server) {
            $watcher = new Watcher(
                new SwooleTimerAdapter(),
                [$basePath],
                [
                    $basePath . '/vendor/',
                    $basePath . '/var/',
                    $basePath . '/.idea/',
                    $basePath . '/var/cache/',
                    $basePath . '/var/log/',
                ],
                function () use ($server) {
                    $server->reload();
                }
            );

            $watcher->tick();
        }, enableCoroutine: true));
    }
}