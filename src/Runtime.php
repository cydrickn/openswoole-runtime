<?php

namespace Cydrickn\Runtime;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Runtime\RunnerInterface;
use Symfony\Component\Runtime\SymfonyRuntime;
use Runtime\Swoole\SymfonyRunner;
use Runtime\Swoole\CallableRunner;

class Runtime extends SymfonyRuntime
{
    private ServerFactory $serverFactory;

    public function __construct(array $options, ?ServerFactory $serverFactory = null)
    {
        $this->serverFactory = $serverFactory ?? new ServerFactory($options);
        parent::__construct($this->serverFactory->getOptions());
    }

    public function getRunner(?object $application): RunnerInterface
    {
        if (is_callable($application)) {
            return new CallableRunner($this->serverFactory, $application);
        }

        if ($application instanceof HttpKernelInterface) {
            return new SymfonyRunner($this->serverFactory, $application);
        }

        return parent::getRunner($application);
    }
}
