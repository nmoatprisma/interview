<?php

declare(strict_types=1);

namespace App\Service;

class SecretRepository
{
    public function __construct(protected $storage, private $secretKey = 'd981678ab411007e11a13042c7bc5c0c4769a0e7fef59b5ebba4ad46dfa86301')
    {
    }

    public function get($key)
    {
        $base64 = $this->storage->get($key);

        if (null === $base64) {
            return null;
        }

        $bin = sodium_base642bin($base64, SODIUM_BASE64_VARIANT_ORIGINAL);
        $nonce = mb_substr($bin, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $cipherValue = mb_substr($bin, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');
        $value = sodium_crypto_secretbox_open($cipherValue, $nonce, sodium_hex2bin($this->secretKey));

        if (false === $value) {
            throw new \Exception('Could not decrypt');
        }

        return $value;
    }

    public function set($key, $value)
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipherValue = sodium_crypto_secretbox($value, $nonce, sodium_hex2bin($this->secretKey));
        $base64 = sodium_bin2base64($nonce . $cipherValue, SODIUM_BASE64_VARIANT_ORIGINAL);
        $this->storage->set($key, $base64);
    }

}
