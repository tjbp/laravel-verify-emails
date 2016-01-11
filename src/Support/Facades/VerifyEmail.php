<?php

namespace LaravelVerifyEmails\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LaravelVerifyEmails\Auth\VerifyEmails\VerifyEmailBroker
 */
class VerifyEmail extends Facade
{
    /**
     * Constant representing a successfully sent verification link.
     *
     * @var string
     */
    const VERIFY_LINK_SENT = 'verify_emails.sent';

    /**
     * Constant representing a successfully verified email address.
     *
     * @var string
     */
    const EMAIL_VERIFIED = 'verify_emails.verified';

    /**
     * Constant representing an invalid token.
     *
     * @var string
     */
    const INVALID_TOKEN = 'verify_emails.token';

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'auth.verify_emails';
    }
}
