# Laravel Mail

This package is build to give some flexibility to the current Mailer that Laravel provides.

Some headaches it tries to solve for you:

- Write your own logic on top of the Mailer and Message by extending the default
- Allow for Mailer Extensions (see MailerExtensions)
- Support for signing your Message with DKIM by using a configurable DKIMProvider

## Version Compatibility
This package has support for Laravel 6.x and 7.x

## Installation

To install through composer, run the following command from terminal:

    composer require "love4work/laravel-mail"

Create your own [Mailer](#mailer) and [Message](#message) classes
 
Once you have your classes set up, update your `config/mail.php`:
```php
<?php
return [
    //
    
    'customize' => [
        'mailer' => \App\Mail\Mailer::class,
        'message' => \App\Mail\Message::class,
    ]
];
```
## Usage

By default, Laravel Mail gives you full access to extend our Mailer and Message

#### Mailer
```php
<?php

namespace App\Mail;

class Mailer extends \Love4Work\Laravel\Mail\Mailer
{
    //
}
```
#### Message
You will at least need to extend our Message class to add functionality you want to use via traits
```php
<?php

namespace App\Mail;

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

Add your new ServiceProvider to the providers array in `config/app.php`
```php
<?php
[
    //
    'providers' => [
    
        /*
         * Application Service Providers...
         */
        App\Providers\MailExtensionServiceProvider::class,
        
    ]
    //
];
```

### Using the DkimProvider

The DkimProvider makes it possible for us to inject our DKIM values.
In this sample we pull the data from our configs.

```php
<?php

class MailExtensionServiceProvider extends \Love4Work\Laravel\Mail\MailExtensionServiceProvider
{

    public function register()
    {
        parent::register();

        // Here we can modify how we want to populate our DkimProvider
        $this->app->singleton(DkimProviderContract::class,
            fn($app) => new DkimProvider(
                config('mail.dkim_selector'),
                config('mail.dkim_domain'),
                storage_path(config('mail.dkim_private_key'))
            )
        );
    }
}
```

**Note: The dkim_private_key can either be a string, or a path to a pem file.**


## Contributing

All contributions are welcomed! (but please submit an issue to make sure the PR is warranted first)

