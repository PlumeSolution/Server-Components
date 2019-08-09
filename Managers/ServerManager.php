<?php

namespace PlumeSolution\Server\Managers;

use React\EventLoop\LoopInterface;
use React\Http\Server;
use React\Socket\Server as Socket;
use Thunder\Application;

class ServerManager
{
	private $params;

	public function __construct(array $params)
	{
		$this->params = $params;
	}

	public function createServer(Application $application, LoopInterface $loop)
	{
		$server = new Server($this->generateCallback($application));
		$socket = new Socket($this->params['port'], $loop);
		$server->listen($socket);
		echo 'System Online '.$this->params['url'].':'.$this->params['port'].'\n';
	}

	private function generateCallback(Application $application)
	{
		return function (Psr\Http\Message\ServerRequestInterface $request) use ($application)
		{
			try
			{
				$response = $application->handle($request);
			}
			catch (\Throwable $e)
			{
				return new React\Http\Response(
					500,
					['Content-Type' => 'text/plain'],
					$e->getMessage()
				);
			}
			return $response;
		};
	}
}