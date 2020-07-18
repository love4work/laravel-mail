<?php

namespace Love4Work\Laravel\Mail\Tests\TestClasses;

use Love4Work\Laravel\Mail\Contracts\DkimProvider as DkimProviderContract;
use Love4Work\Laravel\Mail\Providers\DkimProvider;

class MailExtensionServiceProvider extends \Love4Work\Laravel\Mail\MailExtensionServiceProvider
{
    /**
     * The extensions mappings for the application.
     *
     * @var array
     */
    protected array $extensions = [
        'createMessage', [
            'attachDkim'
        ]
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->app->singleton(DkimProviderContract::class,
            fn($app) => new DkimProvider(
                config('mail.dkim_selector'),
                config('mail.dkim_domain'),
                storage_path(config('mail.dkim_private_key'))
            )
        );
    }
}