<?php

namespace LaravelVerifyEmails\Auth\VerifyEmails;

use InvalidArgumentException;
use Illuminate\Auth\CreatesUserProviders;
use Illuminate\Contracts\Auth\PasswordBrokerFactory as FactoryContract;

class VerifyEmailBrokerManager implements FactoryContract
{
    use CreatesUserProviders;

    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The array of created "drivers".
     *
     * @var array
     */
    protected $brokers = [];

    /**
     * Create a new VerifyEmail manager instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Attempt to get the broker from the local cache.
     *
     * @param  string  $name
     * @return \Illuminate\Contracts\Auth\VerifyEmailBroker
     */
    public function broker($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        return isset($this->brokers[$name])
                    ? $this->brokers[$name]
                    : $this->brokers[$name] = $this->resolve($name);
    }

    /**
     * Resolve the given broker.
     *
     * @param  string  $name
     * @return \Illuminate\Contracts\Auth\VerifyEmailBroker
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Email verifier [{$name}] is not defined.");
        }

        // The email verification broker uses a token repository to validate tokens
        // and send user verification e-mails, as well as validating that email
        // verification process as an aggregate service of sorts providing a
        // convenient interface for verifications.
        return new VerifyEmailBroker(
            $this->createTokenRepository($config),
            $this->app['mailer'],
            $config['email']
        );
    }

    /**
     * Create a token repository instance based on the given configuration.
     *
     * @param  array  $config
     * @return \Illuminate\Auth\Passwords\TokenRepositoryInterface
     */
    protected function createTokenRepository(array $config)
    {
        return new DatabaseTokenRepository(
            $this->app['db']->connection(),
            $config['table'],
            $this->app['config']['app.key'],
            $config['expire']
        );
    }

    /**
     * Get the password broker configuration.
     *
     * @param  string  $name
     * @return array
     */
    protected function getConfig($name)
    {
        return $this->app['config']["auth.verify_emails.{$name}"];
    }

    /**
     * Get the default password broker name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['auth.defaults.verify_emails'];
    }

    /**
     * Set the default password broker name.
     *
     * @param  string  $name
     * @return void
     */
    public function setDefaultDriver($name)
    {
        $this->app['config']['auth.defaults.verify_emails'] = $name;
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->broker(), $method], $parameters);
    }
}
