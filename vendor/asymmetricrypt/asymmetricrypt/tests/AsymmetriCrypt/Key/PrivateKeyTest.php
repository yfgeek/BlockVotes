<?php

use AsymmetriCrypt\Key\PrivateKey;

class PrivateKeyTest extends PHPUnit_Framework_TestCase
{
    public function testCreateKey()
    {
        $key = PrivateKey::create();
        $this->assertInstanceOf("AsymmetriCrypt\Key\PrivateKey", $key);

        return $key;
    }

    public function testLoadKey()
    {
        $key = new PrivateKey(__DIR__.'/../../certs/key.pem');
        $this->assertInstanceOf("AsymmetriCrypt\Key\PrivateKey", $key);

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
    public function testGetPublicKey($key)
    {
        $public = $key->getPublicKey();
        $this->assertInstanceOf("AsymmetriCrypt\Key\PublicKey", $public);
    }

    /**
     * @depends testCreateKey
     */
    public function testSave($key)
    {
        $fname = __DIR__.'/../../certs/testkey';

        $key->save("$fname");
        $this->assertFileExists("$fname.pem");

        @unlink("$fname.pem");
    }

    /**
     * @depends testLoadKey
     */
    public function testToString($key)
    {
        $this->assertStringEqualsFile(__DIR__.'/../../certs/key.pem', "$key");
    }
}