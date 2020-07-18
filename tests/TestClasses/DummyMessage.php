<?php

namespace Love4Work\Laravel\Mail\Tests\TestClasses;

use Love4Work\Laravel\Mail\Contracts\DkimProvider;

class DummyMessage implements \Love4Work\Laravel\Mail\Contracts\Message
{
    public $dkim = '';
    public function attachDkim(DkimProvider $dkimProvider)
    {
        $this->dkim = 'attached';
        return $this;
    }

    public function detachDkim(DkimProvider $dkimProvider)
    {
        $this->dkim = '';
        return $this;
    }

}