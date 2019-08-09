<?php

namespace PlumeSolution\Server\Managers;

use React\EventLoop\LoopInterface;
use Thunder\Application;

class PeriodicCommandManager
{
	private $params;

	public function __construct(array $params)
	{
		$this->params = $params;
	}

	public function createTimers(Application $application, LoopInterface $loop)
	{
		foreach ($this->params as $param)
		{
			$loop->addPeriodicTimer($param['timer'],
				function ($param) use ($application)
				{
					$application = new Console($application);
					$application->run($param['input']);
				}
			);
		}
	}
}