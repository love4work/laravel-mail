<?php

namespace Love4Work\Laravel\Mail;

use Illuminate\Support\ServiceProvider;
use Love4Work\Laravel\Mail\Facades\MailerExtension as MailerExtensionFacade;

abstract class MailExtensionServiceProvider extends ServiceProvider
{
    /**
     * The extensions mappings for the application.
     *
     * @var array
     */
    protected array $extensions = [];

    public function register()
    {
        $this->registerMailerExtensions();
    }

    /**
     * Register MailerExtensions
     */
    public function registerMailerExtensions(): void
    {

        $extensions = $this->getExtensions();

        foreach ($extensions as $function => $listeners) {
            foreach (is_array($listeners) ? array_unique($listeners) : [$listeners] as $extension) {

                MailerExtensionFacade::extend($function, $extension);

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
