<?php

namespace Love4Work\Laravel\Mail;

use Illuminate\Support\ServiceProvider;

class MailBindingServiceProvider extends ServiceProvider
{

    /**
     * The extensions mappings for the application.
     *
     * @var array
     */
    protected array $extensions = [];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/mail.php', 'mail.customize');
        $this->registerMailBindings();
        $this->registerMailerExtensionsBindings();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerMailBindings()
    {
        $this->app->bind(
            'Love4Work\Laravel\Mail\Contracts\Message',
            fn($app) => $app->build(config('mail.customize.message'))
        );

        $this->app->bind(
            'Love4Work\Laravel\Mail\Contracts\Mailer',
            fn($app) => $app->build(config('mail.customize.mailer'))
        );
    }

    public function registerMailerExtensionsBindings(): void
    {
        $this->app->bind(
            \Love4Work\Laravel\Mail\Contracts\MailerExtension::class,
            MailerExtension::class
        );

        $this->app->alias(
            \Love4Work\Laravel\Mail\Contracts\MailerExtension::class,
            'mailer-extension'
        );
    }
}
