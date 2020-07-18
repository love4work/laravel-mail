<?php

namespace Love4Work\Laravel\Mail\Contracts;

interface MailerExtension
{
    public function extend($function, $extension);
    public function hooks();
    public function handle($function, &$object);
}
