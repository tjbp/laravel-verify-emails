<?php

namespace LaravelVerifyEmails\Auth\VerifyEmails;

use Illuminate\Support\ServiceProvider;
use LaravelVerifyEmails\Auth\Console\MakeVerifyEmailsCommand;

class VerifyEmailServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerVerifyEmailBroker();

        $this->commands(MakeVerifyEmailsCommand::class);
    }

    /**
     * Register the verify email broker instance.
     *
     * @return void
     */
    protected function registerVerifyEmailBroker()
    {
        $this->app->singleton('auth.verify_emails', function ($app) {
            return new VerifyEmailBrokerManager($app);
        });

        $this->app->bind('auth.verify_emails.broker', function ($app) {
            return $app->make('auth.verify_emails')->broker();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'auth.verify_emails',
            'auth.verify_emails.broker',
            'command.verify_emails.make'
        ];
    }
}
