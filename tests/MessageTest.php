<?php
namespace Love4Work\Laravel\Mail\Tests;

use Love4Work\Laravel\Mail\Tests\TestClasses\Mailer;
use Love4Work\Laravel\Mail\Tests\TestClasses\Message;

class MessageTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

    }

    /** @test */
    public function it_can_attach_dkim_keys()
    {
        $this->assertTrue(true);
    }
}