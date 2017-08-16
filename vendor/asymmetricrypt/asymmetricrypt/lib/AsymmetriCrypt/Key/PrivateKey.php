<?php

/*
 * AsymmetriCrypt - A PHP public key cryptography library
 *
 * @author    Luciano Longo <luciano.longo@studioigins.net>
 * @copyright (c) Luciano Longo
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @package   AsymmetriCrypt
 */

namespace AsymmetriCrypt\Key;

class PrivateKey
{
    /**
     * The private key itself
     */
    protected $pkey;

    /**
     * The passphrase
     */
    protected $pass;

    /**
     * The private key details
     */
    protected $details;

    public function __construct($pkey, $passphrase = null)
    {
        if (! is_resource($pkey)) {
            if (file_exists($pkey)) {
                $pkey = file_get_contents($pkey);
            }
            if (!($pkey = openssl_pkey_get_private($pkey, $passphrase))) {
                throw new \Exception("Failed to load the private key.");
            }
        }

        $this->pkey = $pkey;
        $this->pass = $passphrase;
        $this->details = openssl_pkey_get_details($pkey);
    }

    public static function create($passphrase = null, $bits = 1024)
    {
        // Make sure is an int
        $bits = (int) $bits;

        // Check size
        if ($bits < 384) {
            throw new \Exception("The bits can't be less than 384!");
        }

        // Create Private Key
        $pkey = openssl_pkey_new(array(
            'encrypt_key' => true,
            'private_key_type' =>  OPENSSL_KEYTYPE_RSA, // As of 5.3, php only supports RSA keys creation
            'private_key_bits' => $bits,
        ));
        if (! $pkey) throw new \Exception("Couldn't create private key!");
        return new static($pkey, $passphrase);
    }

    public function getKey()
    {
        return $this->pkey;
    }

    public function getPublicKey()
    {
        return new PublicKey($this->details['key']);
    }

    public function save($filename)
    {
        $dest_filename = "$filename.pem";

        if (! file_put_contents($dest_filename, $this)) {
            throw new \Exception("Couldn't save the private key to '$dest_filename'.");
        }
    }

    public function __toString()
    {
        if (! openssl_pkey_export($this->pkey, $out, $this->pass) ) {
            throw new \Exception("Couldn't export private key!");
        }

        return $out;
    }
}
