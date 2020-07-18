<?php

namespace Love4Work\Laravel\Mail\Tests;

use Illuminate\Support\Facades\Mail;
use Love4Work\Laravel\Mail\Contracts\DkimProvider;
use Love4Work\Laravel\Mail\MailServiceProvider;
use Love4Work\Laravel\Mail\Tests\TestClasses\Mailer;
use Love4Work\Laravel\Mail\Tests\TestClasses\MailExtensionServiceProvider;
use Love4Work\Laravel\Mail\Tests\TestClasses\Message;

class CustomMailerTest extends TestCase
{

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [MailServiceProvider::class, MailExtensionServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {

        $config = $app['config']->get('mail');
        $app['config']->set('mail', array_merge_recursive($config, [
            'driver' => 'log',
            'dkim_selector' => 'x',
            'dkim_domain' => 'localhost',
            'dkim_private_key' => 'dkim/localhostx_dkim_private.pem',
            'customize' => [
                'mailer' => Mailer::class,
                'message' => Message::class,
            ]
        ]));

        $app['config']->set('view', [
            'paths' => [realpath(__DIR__.'/views')],
            'compiled' => realpath(__DIR__.'/../build')
        ]);
    }
    /**
     * @test
     */
    public function custom_mailer_is_active()
    {
        $this->assertInstanceOf(Mailer::class, $this->app->get('mailer'));
    }

    /**
     * @test
     */
    public function can_attach_dkim_to_message_using_mailer_extension()
    {
        Mail::fake();

        $this->app->get('mailer')->send('test', [], function($message){
            $message->to('test@localhost');
        });

        $dkimProvider = app(DkimProvider::class);
        $this->assertEquals('localhost', $dkimProvider->getDomain());
    }
}