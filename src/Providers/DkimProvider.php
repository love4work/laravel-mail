<?php

namespace Love4Work\Laravel\Mail\Providers;

use Love4Work\Laravel\Mail\Contracts\DkimProvider as DkimProviderContract;

class DkimProvider implements DkimProviderContract
{
    protected string $algorithm = 'rsa-sha256';
    protected string $identity = '';
    protected string $selector;
    protected string $domain;
    protected string $private_key;
    protected string $passphrase;

    public function __construct($selector, $domain, $private_key, $passphrase = '')
    {
        $this->algorithm  ='rsa-' . config('mail.dkim_algo', 'rsa-sha256');
        $this->identity = config('mail.dkim_identity', '');

        $this->selector = $selector;
        $this->domain = $domain;
        $this->setPrivateKey($private_key);
        $this->passphrase = $passphrase;
    }

    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
        return $this;
    }

    public function setIdentity($identity)
    {
        $this->identity = $identity;
        return $this;
    }

    public function setPrivateKey($private_key)
    {
        $this->private_key = \File::exists($private_key) ? \File::get($private_key) : $private_key;
    }

    /**
     * @return string
     */
    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    /**
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getSelector()
    {
        return $this->selector;
    }

    public function getPrivateKey()
    {
        return $this->private_key;
    }

    public function getPassphrase()
    {
        return $this->passphrase;
    }
}
