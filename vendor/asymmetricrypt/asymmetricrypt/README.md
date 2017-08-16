AsymmetriCrypt - Simple PHP public key cryptography
===================================================

[![Build Status](https://travis-ci.org/xFlatlinex/AsymmetriCrypt.png)](https://travis-ci.org/xFlatlinex/AsymmetriCrypt)

Installation
------------

Require `asymmetricrypt/asymmetricrypt` in your project's `composer.json`:

```javascript
{
    "require": {
        "asymmetricrypt/asymmetricrypt": "0.1.*"
    }
}
```

Now update or install your packages with `composer update` or `composer install` respectively.

Usage
-----

```php
<?php

use AsymmetriCrypt\Crypter;
use AsymmetriCrypt\Key\PublicKey;
use AsymmetriCrypt\Key\PrivateKey;

// Create a private key
$priv = Crypter::createPrivateKey();
// or
$priv = PrivateKey::create();

// Load a private key
$priv = Crypter::loadPrivateKey("file:///path/to/key.pem");
// or
$priv = new PrivateKey("file:///path/to/key.pem");

// Get derived public key
$pub = $priv->getPublicKey();

// Load a public key
$pub = Crypter::loadPublicKey("file:///path/to/key.pub");
// or
$pub = new PublicKey("file:///path/to/key.pub");

// Encrypt data
$encrypted = Crypter::encrypt("data to encrypt", $pub);

// Decrypt data
$decrypted = Crypter::decrypt($encrypted, $priv);

// Sign data
$signature = Crypter::sign("data to sign", $priv);

// Verify signature
$signature_valid = Crypter::verify("data to sign", $signature, $pub);
```

Docs
----

I'm still working on a detailed documentation, but I don't have an ETA.
