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

class PublicKey
{
    /**
     * The key
     */
    protected $key;

    /**
     * The key string
     */
    protected $value;

    /**
     * Load a public key from a file
     *
     * @param mixed $key The public key file or the content itself
     */
    public function __construct($key)
    {
        if (!is_resource($key)) {
            if (file_exists($key)) {
                $key = file_get_contents($key);
            }
            if (! ($key = openssl_pkey_get_public($key))) {
                throw new \Exception("Failed to load the public key.");
            }
        }

        $this->key = $key;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function save($filename)
    {
        $dest_filename = "$filename.pub";

        if (! file_put_contents($dest_filename, $this)) {
            throw new \Exception("Couldn't save the public key to '$dest_filename'.");
        }
    }

    public function __toString()
    {
        $details = openssl_pkey_get_details($this->key);

        return $details['key'];
    }
}
