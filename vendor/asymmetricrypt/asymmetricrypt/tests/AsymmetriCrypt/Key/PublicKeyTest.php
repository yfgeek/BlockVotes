<?php

use AsymmetriCrypt\Key\PublicKey;

class PublicKeyTest extends PHPUnit_Framework_TestCase
{
    public function testLoadKey()
    {
        $key = new PublicKey(__DIR__.'/../../certs/key.pub');
        $this->assertInstanceOf("AsymmetriCrypt\Key\PublicKey", $key);

        return $key;
    }

    /**
     * @depends testLoadKey
     */
    public function testGetKey($key)
    {
        $resource = $key->getKey();
        $this->assertEquals('resource', gettype($resource));
        $this->assertEquals(get_resource_type($resource), 'OpenSSL key');
    }

    /**
     * @depends testLoadKey
     */
    public function testSave($key)
    {
        $fname = __DIR__.'/../../certs/testkey';

        $key->save("$fname");
        $this->assertFileExists("$fname.pub");

        @unlink("$fname.pub");
    }

    /**
     * @depends testLoadKey
     */
    public function testToString($key)
    {
        $this->assertStringEqualsFile(__DIR__.'/../../certs/key.pub', "$key");
    }
}