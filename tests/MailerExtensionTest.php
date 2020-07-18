<?php
namespace Love4Work\Laravel\Mail\Tests;

use Love4Work\Laravel\Mail\Contracts\DkimProvider;
use Love4Work\Laravel\Mail\Facades\MailerExtension;
use Love4Work\Laravel\Mail\Tests\TestClasses\DummyMessage;


class MailerExtensionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_accepts_extensions_based_on_function_names()
    {
        MailerExtension::extend('createMessage', 'attachDkim');
        MailerExtension::extend('createMessage', 'fakeMessage');

        $this->assertEquals(MailerExtension::hooks(), ['createMessage' => ['attachDkim', 'fakeMessage']]);
    }

    /** @test */
    public function it_can_handle_a_single_extension()
    {
        MailerExtension::extend('createMessage', 'attachDkim');

        $message = new DummyMessage;
        $dkimProvider = \Mockery::mock(DkimProvider::class);
        $this->app->instance(DkimProvider::class, $dkimProvider);

        MailerExtension::handle('createMessage', $message);

        $this->assertEquals('attached', $message->dkim);
    }

    /** @test */
    public function it_can_handle_a_multiple_extensions_sequentially()
    {
        MailerExtension::extend('createMessage', 'attachDkim');
        MailerExtension::extend('createMessage', 'detachDkim');

        $message = new DummyMessage;
        $dkimProvider = \Mockery::mock(DkimProvider::class);
        $this->app->instance(DkimProvider::class, $dkimProvider);

        MailerExtension::handle('createMessage', $message);

        $this->assertEquals('', $message->dkim);
    }
}