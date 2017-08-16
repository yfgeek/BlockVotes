<?php

use AsymmetriCrypt\Crypter;
use AsymmetriCrypt\Key\PublicKey;
use AsymmetriCrypt\Key\PrivateKey;

class CrypterTest extends PHPUnit_Framework_TestCase
{
    public function testCreateKeys()
    {
        $privkey = Crypter::createPrivateKey();
        $this->assertInstanceOf("AsymmetriCrypt\Key\PrivateKey", $privkey);

        $pubkey = $privkey->getPublicKey();
        $this->assertInstanceOf("AsymmetriCrypt\Key\PublicKey", $pubkey);

        return array( $privkey, $pubkey );
    }

    /**
     * @depends testCreateKeys
     */
    public function testEncryptAndDecrypt(array $keys)
    {
        $privkey = $keys[0];
        $pubkey = $keys[1];

        $encrypted = Crypter::encrypt('Lorem ipsum dolor sit amet', $pubkey);
        $this->assertRegExp("/[^-A-Za-z0-9+\/=]|=[^=]|={3,}$/", $encrypted);

        $decrypted = Crypter::decrypt($encrypted, $privkey);
        $this->assertEquals('Lorem ipsum dolor sit amet', $decrypted);
    }

    /**
     * @depends testCreateKeys
     */
    public function testSignAndVerify(array $keys)
    {
        $privkey = $keys[0];
        $pubkey = $keys[1];
        $data = 'Lorem ipsum dolor sit amet';

        $signature = Crypter::sign($data, $privkey);
        $this->assertRegExp("/[^-A-Za-z0-9+\/=]|=[^=]|={3,}$/", $signature);

        $verify = Crypter::verify($data, $signature, $pubkey);
        $this->assertTrue($verify);
    }

    public function testLoadExistingKeys()
    {
        $fname = __DIR__.'/../certs/key';

        $privkey = Crypter::loadPrivateKey("$fname.pem");
        $this->assertInstanceOf("AsymmetriCrypt\Key\PrivateKey", $privkey);

        $pubkey = Crypter::loadPublicKey("$fname.pub");
        $this->assertInstanceOf("AsymmetriCrypt\Key\PublicKey", $pubkey);

        return array( $privkey, $pubkey );
    }

    /**
     * @depends testLoadExistingKeys
     */
    public function testEncryptAndDecryptWithExistingKeys(array $keys)
    {
        $privkey = $keys[0];
        $pubkey = $keys[1];

        $encrypted = Crypter::encrypt('Lorem ipsum dolor sit amet', $pubkey);
        $this->assertRegExp("/[^-A-Za-z0-9+\/=]|=[^=]|={3,}$/", $encrypted);

        $decrypted = Crypter::decrypt($encrypted, $privkey);
        $this->assertEquals('Lorem ipsum dolor sit amet', $decrypted);
    }

    /**
     * @depends testLoadExistingKeys
     */
    public function testSignAndVerifyWithExistingKeys(array $keys)
    {
        $privkey = $keys[0];
        $pubkey = $keys[1];
        $data = 'Lorem ipsum dolor sit amet';

        $signature = Crypter::sign($data, $privkey);
        $this->assertRegExp("/[^-A-Za-z0-9+\/=]|=[^=]|={3,}$/", $signature);

        $verify = Crypter::verify($data, $signature, $pubkey);
        $this->assertTrue($verify);
    }
}