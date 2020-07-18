<?php

namespace Love4Work\Laravel\Mail;

use Illuminate\Support\ServiceProvider;

class MailServiceProvider extends \Illuminate\Mail\MailServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/mail.php', 'mail.customize');
        $this->registerLove4WorkMailer();
        $this->registerMarkdownRenderer();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerLove4WorkMailer()
    {
        $this->app->bind(
            'Love4Work\Laravel\Mail\Contracts\Message',
            config('mail.customize.message')
        );

        $this->app->bind(
            'Love4Work\Laravel\Mail\Contracts\Mailer',
            config('mail.customize.mailer'),
        );

        $this->app->singleton('mail.manager', function ($app) {
            return new MailManager($app);
        });

        $this->app->bind('mailer', function ($app) {
            return $app->make('mail.manager')->mailer();
        });

    }

}
