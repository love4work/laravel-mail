# Laravel Mail

This package is build to give some flexibility to the current Mailer that Laravel provides.

Some headaches it tries to solve or you:

- Write your own logic on top of the Mailer and Message by extending the default
- Allow for Mailer Extensions (see MailerExtensions)
- Support for signing your Message with DKIM by using a configurable DKIMProvider

## Version Compatibility
This package has support for Laravel 6.x and 7.x

## Installation

To install through composer, run the following command from terminal:

    composer require "love4work/laravel-mail"
    
Next let us extend our mail config with this command:
    
    php artisan vendor:publish --provider="Love4Work\Laravel\Mail\MailServiceProvider" --tag="config"

## Usage

By default, Laravel Mail gives you full access to extend our Mailer and Message

```php
<?php

class Mailer extends \Love4Work\Laravel\Mail\Mailer
{
    //
}
```

You will at least need to extend our Message class to add functionality you want to use via traits
```php
<?php

use Love4Work\Laravel\Mail\Message\AttachDkimTrait;

class Message extends \Love4Work\Laravel\Mail\Message
{
    use AttachDkimTrait;
}
```

### Setup a Service Provider

To use Laravel Mail Extensions, all you need to do is extend `\Love4Work\Laravel\Mail\MailExtensionServiceProvider` and add your desired extensions.
The format on how to do this is quite simple, in the `$extensions` the key would be the function you want to hook into, you can pass an array of multiple functions you wish to call, but remember that the subject you wish to call these functions on actually exist. 

In this particular case of `createMessage` the subject is `Message` and the callback function we set is `attachDkim`. this means `atttachDkim` must exist and it does thanks to our `AttachDkimTrait` 
Please see our [test ServiceProvider](tests/TestClasses/MailExtensionServiceProvider.php)
```php
<?php

class MailExtensionServiceProvider extends \Love4Work\Laravel\Mail\MailExtensionServiceProvider
{
    protected array $extensions = [
        'createMessage', [
            'attachDkim'
        ]
    ];
    // see full options on how to implement this in our test ServiceProvider
}
```

## Contributing

All contributions are welcomed! (but please submit an issue to make sure the PR is warranted first)

