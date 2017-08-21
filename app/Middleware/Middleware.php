<?php

namespace App\Middleware;

/**
 * Middleware
 * @author  Yifan Wu
 * @package Middleware
 */
class Middleware
{
	protected $container;
	
	public function __construct($container)
	{
		$this->container = $container;
    }
}