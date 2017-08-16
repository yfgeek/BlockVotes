<?php
require_once dirname(dirname(__FILE__))."/vendor/autoload.php";

class Stackable {

    use \Slim\MiddlewareAwareTrait;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response) {
        return $res->write('Center');
    }

}

class ContainerWithoutArrayAccess implements \Psr\Container\ContainerInterface {

    protected $container;

    function __construct(array $values = []) {
        $this->container = new \Slim\Container($values);
    }

    public function get($id) {
        return $this->container->get($id);
    }

    public function has($id) {
        return $this->container->has($id);
    }

    public function set($id, $value) {
        $this->container[$id] = $value;
    }

}

class StrangeContainerWithoutArrayAccess implements \Psr\Container\ContainerInterface {

    protected $container;

    function __construct(array $values = []) {
        $this->container = new \Slim\Container($values);
    }

    public function get($id) {
        return $this->container->get($id);
    }

    public function has($id) {
        return $this->container->has($id);
    }

    public function strangeSetter($value, $id) {
        $this->container[$id] = $value;
    }

}
