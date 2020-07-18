<?php

namespace Love4Work\Laravel\Mail;

use InvalidArgumentException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Love4Work\Laravel\Mail\Contracts\Mailer as MailerContract;

if(class_exists("\Illuminate\Mail\MailManager")){

    /**
     * @mixin \Illuminate\Mail\Mailer
     */
    class MailManager extends \Illuminate\Mail\MailManager
    {
        /**
         * Resolve the given mailer.
         *
         * @param  string  $name
         * @return \Illuminate\Mail\Mailer
         *
         * @throws \InvalidArgumentException
         */
        protected function resolve($name)
        {
            $config = $this->getConfig($name);

            if (is_null($config)) {
                throw new InvalidArgumentException("Mailer [{$name}] is not defined.");
            }

            // Once we have created the mailer instance we will set a container instance
            // on the mailer. This allows us to resolve mailer classes via containers
            // for maximum testability on said classes instead of passing Closures.
            $mailer = $this->app->makeWith(MailerContract::class, [
                'name' => $name,
                'views' => $this->app['view'],
                'swift' => $this->createSwiftMailer($config),
                'events' => $this->app['events']
            ]);

            if ($this->app->bound('queue')) {
                $mailer->setQueue($this->app['queue']);
            }

            // Next we will set all of the global addresses on this mailer, which allows
            // for easy unification of all "from" addresses as well as easy debugging
            // of sent messages since these will be sent to a single email address.
            foreach (['from', 'reply_to', 'to', 'return_path'] as $type) {
                $this->setGlobalAddress($mailer, $config, $type);
            }

            return $mailer;
        }
    }

} else {
    class MailManager
    {
        /**
         * The application instance.
         *
         * @var \Illuminate\Contracts\Foundation\Application
         */
        protected $app;

        /**
         * Create a new Mail manager instance.
         *
         * @param  \Illuminate\Contracts\Foundation\Application  $app
         * @return void
         */
        public function __construct($app)
        {
            $this->app = $app;
        }

        public function mailer()
        {
            $config = $this->app->make('config')->get('mail');

            // Once we have create the mailer instance, we will set a container instance
            // on the mailer. This allows us to resolve mailer classes via containers
            // for maximum testability on said classes instead of passing Closures.
            $mailer = $this->app->makeWith(MailerContract::class, [
                'views' => $this->app['view'], 'swift' => $this->app['swift.mailer'], 'events' => $this->app['events']
            ]);

            if ($this->app->bound('queue')) {
                $mailer->setQueue($this->app['queue']);
            }

            // Next we will set all of the global addresses on this mailer, which allows
            // for easy unification of all "from" addresses as well as easy debugging
            // of sent messages since they get be sent into a single email address.
            foreach (['from', 'reply_to', 'to'] as $type) {
                $this->setGlobalAddress($mailer, $config, $type);
            }

            return $mailer;
        }

        /**
         * Set a global address on the mailer by type.
         *
         * @param  \Illuminate\Mail\Mailer  $mailer
         * @param  array  $config
         * @param  string  $type
         * @return void
         */
        protected function setGlobalAddress($mailer, array $config, $type)
        {
            $address = Arr::get($config, $type);

            if (is_array($address) && isset($address['address'])) {
                $mailer->{'always'.Str::studly($type)}($address['address'], $address['name']);
            }
        }
    }
}

