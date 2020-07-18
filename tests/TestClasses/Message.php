<?php

namespace Love4Work\Laravel\Mail\Tests\TestClasses;

use Love4Work\Laravel\Mail\Message\AttachDkimTrait;

class Message extends \Love4Work\Laravel\Mail\Message
{
    use AttachDkimTrait;
}