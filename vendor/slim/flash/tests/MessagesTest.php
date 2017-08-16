<?php
namespace Slim\Flash\Tests;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages;

class MessagesTest extends \PHPUnit_Framework_TestCase
{
    // Test get messages from previous request
    public function testGetMessagesFromPrevRequest()
    {
        $storage = ['slimFlash' => ['Test']];
        $flash = new Messages($storage);

        $this->assertEquals(['Test'], $flash->getMessages());
    }

    // Test get empty messages from previous request
    public function testGetEmptyMessagesFromPrevRequest()
    {
        $storage = [];
        $flash = new Messages($storage);

        $this->assertEquals([], $flash->getMessages());
    }

    // Test set messages for next request
    public function testSetMessagesForNextRequest()
    {
        $storage = [];
        
        $flash = new Messages($storage);
        $flash->addMessage('Test', 'Test');
        $flash->addMessage('Test', 'Test2');

        $this->assertArrayHasKey('slimFlash', $storage);
        $this->assertEquals(['Test', 'Test2'], $storage['slimFlash']['Test']);
    }
    
    //Test getting the message from the key
    public function testGetMessageFromKey()
    {
        $storage = ['slimFlash' => [ 'Test' => ['Test', 'Test2']]];
        $flash = new Messages($storage);

        $this->assertEquals(['Test', 'Test2'], $flash->getMessage('Test'));        
    }
}
