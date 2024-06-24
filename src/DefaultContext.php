<?php


namespace Omegaalfa\FiberRunner;



use Throwable;


class DefaultContext implements ContextInterface
{
	/**
	 * @var array
	 */
	private array $data;

	/**
	 * @var Throwable|null
	 */
	private ?Throwable $exception = null;

	/**
	 * @param  array  $data
	 */
	public function __construct(array $data = [])
	{
		$this->data = $data;
	}

	/**
	 * @return array
	 */
	public function getContextData(): array
	{
		return $this->data;
	}

	/**
	 * @param  array  $data
	 *
	 * @return void
	 */
	public function setContextData(array $data): void
	{
		$this->data = $data;
	}

	/**
	 * @return Throwable|null
	 */
	public function getContextException(): ?Throwable
	{
		return $this->exception;
	}

	/**
	 * @param  Throwable|null  $exception
	 *
	 * @return void
	 */
	public function setContextException(?Throwable $exception): void
	{
		$this->exception = $exception;
	}
}
