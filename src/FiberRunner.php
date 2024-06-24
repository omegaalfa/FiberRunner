<?php

namespace Omegaalfa\FiberRunner;

use Fiber;
use Throwable;
use WeakMap;

class FiberRunner
{
	/**
	 * Holds a reference to all Fiber that are created by this driver.
	 * Service fibers have a special service context.
	 *
	 * @var WeakMap<Fiber, ContextInterface>
	 */
	private WeakMap $contexts;

	/**
	 * @var array
	 */
	protected array $fiberPool = [];

	/**
	 * @var array
	 */
	public array $runningFibers = [];

	/**
	 * @param  int  $maxFibers
	 */
	public function __construct(protected int $maxFibers = 10)
	{
		$this->contexts = new WeakMap();
	}


	/**
	 * @param  callable          $fn
	 * @param  ContextInterface  $context
	 *
	 * @return Fiber
	 */
	public function createFiber(callable $fn, ContextInterface $context): Fiber
	{
		$fiber = new Fiber(function() use ($fn, $context) {
			try {
				$fn();
			} catch(Throwable $e) {
				$context->setContextException($e);
			}
		});

		$this->contexts[$fiber] = $context;
		$this->fiberPool[] = $fiber;

		return $fiber;
	}

	/**
	 * @param  Fiber  $fiber
	 *
	 * @return void
	 * @throws Throwable
	 */
	public function start(Fiber $fiber): void
	{
		if(!isset($this->contexts[$fiber])) {
			throw new \InvalidArgumentException("Fiber not managed by this driver");
		}

		$fiber->start();
		$this->runningFibers[] = $fiber;
	}

	/**
	 * @param  Fiber  $fiber
	 *
	 * @return void
	 * @throws Throwable
	 */
	public function stop(Fiber $fiber): void
	{
		if(!isset($this->contexts[$fiber])) {
			throw new \InvalidArgumentException("Fiber not managed by this driver");
		}

		$context = $this->contexts[$fiber];
		if($context->getContextException()) {
			echo "Exception: " . $context->getContextException()->getMessage();
		}

		unset($this->contexts[$fiber]);
	}

	/**
	 * @param  Fiber  $fiber
	 *
	 * @return ContextInterface|null
	 */
	public function getContext(Fiber $fiber): ?ContextInterface
	{
		return $this->contexts[$fiber] ?? null;
	}


	/**
	 * @param  callable               $fn
	 * @param  array|null             $args
	 * @param  ContextInterface|null  $context
	 *
	 * @return array|null
	 * @throws Throwable
	 */
	public function run(callable $fn, ?array $args = [], ?ContextInterface $context = null): array|null
	{
		try {
			$fiber = $this->createFiber($fn, $context ?? new DefaultContext($args));
			$this->start($fiber);

			while(count($this->runningFibers) > 0) {
				foreach($this->runningFibers as $index => $runningFiber) {
					if($runningFiber->isTerminated()) {
						unset($this->runningFibers[$index]);
					} else {
						$runningFiber->resume();
					}
				}
			}

			return $context?->getContextData();
		} catch(Throwable $e) {
			throw new $e;
		}
	}
}
