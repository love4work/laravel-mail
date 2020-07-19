<?php

namespace Love4Work\Laravel\Mail;

class MailServiceProvider extends \Illuminate\Mail\MailServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if(method_exists($this, 'registerSwiftMailer')){
            $this->registerSwiftMailer();
        }

        $this->registerMailer();
        $this->registerMarkdownRenderer();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerMailer()
    {

        $this->app->singleton('mail.manager', function ($app) {
            return new MailManager($app);
        });

        $this->app->bind('mailer', function ($app) {
            return $app->make('mail.manager')->mailer();
        });

    }

}
