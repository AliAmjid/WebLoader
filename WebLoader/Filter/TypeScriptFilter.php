<?php

declare(strict_types = 1);

namespace WebLoader\Filter;

use WebLoader\Compiler;

/**
 * TypeScript filter
 *
 * @author Jan Tvrdík
 * @license MIT
 */
class TypeScriptFilter
{

	/** @var string */
	private $bin;

	/** @var array */
	private $env;

	/**
	 * @param string $bin
	 * @param array $env
	 */
	public function __construct(string $bin = 'tsc', array $env = [])
	{
		$this->bin = $bin;
		$this->env = $env + $_ENV;
		unset($this->env['argv'], $this->env['argc']);
	}

	/**
	 * Invoke filter
	 *
	 * @param  string $code
	 * @param  \WebLoader\Compiler $compiler
	 * @param  string $file
	 * @return string
	 */
	public function __invoke(string $code, Compiler $compiler, ?string $file = null): string
	{
		if (pathinfo($file, PATHINFO_EXTENSION) === 'ts') {
			$out = substr_replace($file, 'js', -2);
			$cmd = sprintf('%s %s --target ES5 --out %s', $this->bin, escapeshellarg($file), escapeshellarg($out));
			Process::run($cmd, null, null, $this->env);
			$code = file_get_contents($out);
		}

		return $code;
	}

}
