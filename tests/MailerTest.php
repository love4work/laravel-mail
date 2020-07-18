<?php

namespace Love4Work\Laravel\Mail\Tests;

use Love4Work\Laravel\Mail\Contracts\DkimProvider;
use Love4Work\Laravel\Mail\Contracts\MailerExtension;

class MailerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }


    /** @test */
    public function get_our_instances_from_the_ioc_container()
    {
        $this->assertTrue($this->app->bound(\Love4Work\Laravel\Mail\Contracts\Message::class), "We should make sure our MailServiceProvider is loaded!");
        $this->assertInstanceOf(\Love4Work\Laravel\Mail\MailManager::class, $this->app->get('mail.manager'));
        $this->assertInstanceOf(\Love4Work\Laravel\Mail\Mailer::class, $this->app->get('mailer'));
    }

    /** @test */
    public function can_hook_into_create_message()
    {
        $this->mock(MailerExtension::class)->expects('handle')->once();
        $mailer = $this->app->get('mailer')->render('test');
    }

}