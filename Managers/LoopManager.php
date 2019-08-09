<?php


namespace PlumeSolution\Server\Managers;

use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use Thunder\Application;
use React\Http\Server;
use React\Socket\Server as Socket;

/**
 * Class LoopManager.
 *
 * Manage event loop from ReactPHP and integrate it with Symfony 4 kernel.
 *
 * @package App\Server
 */
class LoopManager
{
    /**
     * The loop.
     *
     * @var LoopInterface $loop
     */
    private $loop;

    /**
     * Primary kernel.
     *
     * @var Application $application
     */
	private $application;

	/**
	 * @var PeriodicCommandManager
	 */
	private $periodicCommandManager;

	/**
	 * @var ServerManager
	 */
	private $serverManager;

	/**
	 * LoopManager constructor.
	 *
	 * @param Application $application
	 * @param PeriodicCommandManager $periodicCommandManager
	 * @param ServerManager $serverManager
	 */
    public function __construct(Application $application, PeriodicCommandManager $periodicCommandManager, ServerManager $serverManager)
    {
        $this->application = $application;
        $this->loop = Factory::create();
        $this->periodicCommandManager = $periodicCommandManager;
        $this->serverManager = $serverManager;
    }

    /**
     * Create fully configured event loop.
     *
     * @param $callback
     * @return mixed
     */
    public function create($callback)
    {
        $this->periodicCommandManager->createTimers($this->application, $this->loop);
        $this->serverManager->createServer($this->application, $this->loop);
        return $this->loop;
    }
}