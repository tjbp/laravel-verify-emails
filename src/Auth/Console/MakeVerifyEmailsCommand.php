<?php

namespace LaravelVerifyEmails\Auth\Console;

use Illuminate\Console\Command;

class MakeVerifyEmailsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:verify-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold email verification views and controller';

    /**
     * THe views that need to be exported.
     *
     * @var array
     */
    protected $views = [
        'auth/emails/verify.stub' => 'auth/emails/verify.blade.php',
        'auth/verify-emails/unverified.stub' => 'auth/verify-emails/unverified.blade.php',
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->createDirectories();

        $this->exportViews();

        $this->info('Installed EmailController.');

        copy(__DIR__.'/stubs/make/controllers/EmailController.stub', app_path('Http/Controllers/Auth/EmailController.php'));

        $this->info('Updated routes file.');

        file_put_contents(
            app_path('Http/routes.php'),
            file_get_contents(__DIR__.'/stubs/make/routes.stub'),
            FILE_APPEND
        );

        $this->info('Added language lines.');

        copy(__DIR__.'/stubs/make/lang/en/verify_emails.stub', base_path('resources/lang/en/verify_emails.php'));

        $this->comment('Email verification scaffolding generated successfully!');
    }

    /**
     * Create the directories for the files.
     *
     * @return void
     */
    protected function createDirectories()
    {
        if (! is_dir(base_path('resources/views/auth/verify-emails'))) {
            mkdir(base_path('resources/views/auth/verify-emails'), 0755, true);
        }

        if (! is_dir(base_path('resources/views/auth/emails'))) {
            mkdir(base_path('resources/views/auth/emails'), 0755, true);
        }
    }

    /**
     * Export the authentication views.
     *
     * @return void
     */
    protected function exportViews()
    {
        foreach ($this->views as $key => $value) {
            $path = base_path('resources/views/'.$value);

            $this->line('<info>Created View:</info> '.$path);

            copy(__DIR__.'/stubs/make/views/'.$key, $path);
        }
    }
}
