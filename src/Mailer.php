<?php

namespace Love4Work\Laravel\Mail;

use Love4Work\Laravel\Mail\Contracts\Mailer as MailerContract;
use Love4Work\Laravel\Mail\Contracts\Message as MessageContract;
use Love4Work\Laravel\Mail\Facades\MailerExtension;

class Mailer extends \Illuminate\Mail\Mailer implements MailerContract
{
    /**
     * Create a new message instance.
     *
     * @return \Illuminate\Mail\Message
     */
    protected function createMessage()
    {
        $message = app()->makeWith(MessageContract::class, ['swift' => $this->swift->createMessage('message')]);

        // If a global from address has been specified we will set it on every message
        // instance so the developer does not have to repeat themselves every time
        // they create a new message. We'll just go ahead and push this address.
        if (! empty($this->from['address'])) {
            $message->from($this->from['address'], $this->from['name']);
        }

        // When a global reply address was specified we will set this on every message
        // instance so the developer does not have to repeat themselves every time
        // they create a new message. We will just go ahead and push this address.
        if (! empty($this->replyTo['address'])) {
            $message->replyTo($this->replyTo['address'], $this->replyTo['name']);
        }

        MailerExtension::handle('createMessage', $message);

        return $message;
    }

}
