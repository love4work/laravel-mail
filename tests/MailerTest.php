<?php

namespace Love4Work\Laravel\Mail\Tests;

use Love4Work\Laravel\Mail\Contracts\MailerExtension;
use Love4Work\Laravel\Mail\Contracts\Message;
use Love4Work\Laravel\Mail\Mailer;
use Love4Work\Laravel\Mail\MailManager;

class MailerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }


    /** @test */
    public function get_our_instances_from_the_ioc_container()
    {
        $this->assertTrue($this->app->bound(Message::class), "We should make sure our MailServiceProvider is loaded!");
        $this->assertInstanceOf(MailManager::class, $this->app->get('mail.manager'));
        $this->assertInstanceOf(Mailer::class, $this->app->get('mailer'));
    }

    /** @test */
    public function can_hook_into_create_message()
    {
        $this->mock(MailerExtension::class)->expects('handle')->once();
        $this->app->get('mailer')->render('test');
    }

}