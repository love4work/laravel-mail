<?php

use Love4Work\Laravel\Mail\Mailer;
use Love4Work\Laravel\Mail\Message;

return [

    /*
     * You can customize some of the behavior of this package by using our own custom Mailer / Message.
     * Your custom Mailer / Message should always extend our default one.
     */
    'mailer' => Mailer::class,
    'message' => Message::class,
];
