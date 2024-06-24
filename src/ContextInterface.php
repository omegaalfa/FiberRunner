<?php


namespace Omegaalfa\FiberRunner;



use Throwable;

interface ContextInterface
{
	/**
	 * @return array
	 */
	public function getContextData(): array;

	/**
	 * @param  array  $data
	 *
	 * @return void
	 */
	public function setContextData(array $data): void;

	/**
	 * @return Throwable|null
	 */
	public function getContextException(): ?Throwable;

	/**
	 * @param  Throwable|null  $exception
	 *
	 * @return void
	 */
	public function setContextException(?Throwable $exception): void;
}
