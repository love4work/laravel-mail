<?php

namespace Love4Work\Laravel\Mail\Tests;

use Love4Work\Laravel\Mail\Mailer;
use Love4Work\Laravel\Mail\MailExtensionServiceProvider;
use Love4Work\Laravel\Mail\Message;
use Orchestra\Testbench\TestCase as Orchestra;
use Love4Work\Laravel\Mail\MailServiceProvider;

class TestCase extends Orchestra
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

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('mail.customize', [
            'mailer' => Mailer::class,
            'message' => Message::class,
        ]);

        $app['config']->set('view', [
            'paths' => [realpath(__DIR__.'/views')],
            'compiled' => realpath(__DIR__.'/../build')
        ]);

    }

    protected function useCustomizeMail($app)
    {
        $app['config']->set('mail.customize', [
            'mailer' => \Love4Work\Laravel\Mail\Tests\TestClasses\Mailer::class,
            'message' => \Love4Work\Laravel\Mail\Tests\TestClasses\Message::class,
        ]);

        $app['config']->set('view', [
            'paths' => [realpath(__DIR__.'/views')],
            'compiled' => realpath(__DIR__.'/../build')
        ]);
    }

}