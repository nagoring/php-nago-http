<?php
declare(strict_types=1);

namespace Nago\Component\Http;

class Request extends \GuzzleHttp\Psr7\Request
{
	/**
	 * @var array
	 */
	private $vars;

	/**
	 * Request constructor.
	 * @param string $method
	 * @param $uri
	 * @param array $vars
	 * @param array $headers
	 * @param null $body
	 * @param string $version
	 */
	public function __construct(string $method, $uri, array $vars, array $headers = [], $body = null, string $version = '1.1') {
		parent::__construct($method, $uri, $headers, $body, $version);

		$this->vars = $vars;
	}

	/**
	 * @param $name
	 * @return bool|mixed
	 */
	public function __get($name) {
		if(!isset($this->vars[$name]))return false;
		return $this->vars[$name];
	}
}