<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Dotenv\Dotenv;

#[AsCommand(
    name: 'app:validate-environment',
    description: 'Validate .env configurations and inform about the missing enviroment variable',
)]
class ValidateEnvironmentCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Environment Validation');
        $envPath = __DIR__ . '/../../.env';
        if (!file_exists($envPath)) {
            $io->error('.env file not found.');
            return Command::FAILURE;
        }

        $dotenv = new Dotenv();
        $dotenv->usePutenv();
        $dotenv->loadEnv($envPath);

        $envVars = $_ENV;

        $requiredVars = [
            'DATABASE_URL' => 'The database URL.',
            'DATABASE_USERNAME' => 'The database username.',
            'DATABASE_PASSWORD' => 'The database password.',
            'TEST_ENV' => 'Test env',
            'APP_SECRET' => 'A secret key used for various security-related purposes.',
        ];

        $hasErrors = false;

        foreach ($requiredVars as $variable => $description) {
            if (empty($envVars[$variable] ?? null)) {
                $io->error(sprintf('Missing required environment variable: %s (%s)', $variable, $description));
                $hasErrors = true;
            }
        }

        if ($hasErrors) {
            $io->error('Environment variable validation failed successfully.');
            return Command::FAILURE;
        }

        $io->success('Environment validation passed successfully. Are required enviroment variables are set.');
        return Command::SUCCESS;
    }
}
