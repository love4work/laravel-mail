<?php

namespace Love4Work\Laravel\Mail;

use Illuminate\Support\ServiceProvider;

class MailExtensionServiceProvider extends ServiceProvider
{
    /**
     * The extensions mappings for the application.
     *
     * @var array
     */
    protected array $extensions = [];

    public function register()
    {
        $this->app->bind(
            \Love4Work\Laravel\Mail\Contracts\MailerExtension::class,
            MailerExtension::class
        );

        $this->app->alias(
            \Love4Work\Laravel\Mail\Contracts\MailerExtension::class,
            'mailer-extension'
        );

        $extensions = $this->getExtensions();

        foreach ($extensions as $function => $listeners) {
            foreach (is_array($listeners) ? array_unique($listeners) : [$listeners] as $extension) {

                \Love4Work\Laravel\Mail\Facades\MailerExtension::extend($function, $extension);

            }
        }

    }

    /**
     * Get the extensions defined on the provider.
     *
     * @return array
     */
    public function extensions(): array
    {
        return $this->extensions;
    }

    /**
     * @return array
     */
    public function getExtensions(): array
    {
        return $this->extensions();
    }
}
