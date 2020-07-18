<?php

namespace Love4Work\Laravel\Mail\Facades;

use Illuminate\Support\Facades\Facade;

class MailerExtension extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'mailer-extension';
    }
}