<?php

namespace App\Controllers;
/**
 * Class Controller
 * @author  Yifan Wu
 * The Controller including the $container
 * @package Controllers
 */
class Controller
{
	protected $container;

	public function __construct($container)
	{
		$this->container = $container;
	}

	public function __get($property)
	{
		if ($this->container->{$property}) {
			return $this->container->{$property};
		}
	}
}