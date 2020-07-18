<?php

namespace Love4Work\Laravel\Mail\Contracts;

interface DkimProvider
{
    public function setAlgorithm($algorithm);
    public function setIdentity($identity);

    public function getAlgorithm();
    public function getIdentity();
    public function getDomain();
    public function getSelector();
    public function getPrivateKey();
    public function getPassphrase();
}
