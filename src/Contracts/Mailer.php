<?php

namespace Love4Work\Laravel\Mail\Contracts;

use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Contracts\Mail\MailQueue as MailQueueContract;

interface Mailer extends MailerContract, MailQueueContract
{

}
