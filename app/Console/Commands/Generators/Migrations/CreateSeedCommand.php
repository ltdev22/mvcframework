<?php

namespace App\Console\Commands\Generators\Migrations;

use App\Console\Command;
use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateSeedCommand extends Command
{
    /**
     * The phinx command to run.
     */
    const PHINX_COMMAND = 'seed:create';

    /**
     * The name of the command.
     *
     * @param string
     */
    protected $command = 'db:seed:create';

    /**
     * The description of the command.
     *
     * @param string
     */
    protected $description = 'Create new database seed.';

    /**
     * Handle the execution of the command.
     *
     * @param  Symfony\Component\Console\Input\InputInterface       $input
     * @param  Symfony\Component\Console\Output\OutputInterface     $output
     *
     * @return void
     */
    public function handle(InputInterface $input, OutputInterface $output)
    {
        // Instatiate Phinx and set the command inputs
        $phinx = new PhinxApplication();
        $phinxInput = new ArrayInput([
            'command' => self::PHINX_COMMAND,
            'name' => $input->getArgument('name'),
            '-c' => './config/phinx.php',
            // '-t' => base_path('/app/Console/stubs/migrations/createSeed.stub'),
        ]);

        // Run the phinx command to create a new migration and show a friendly message.
        $returnCode = $phinx->find(self::PHINX_COMMAND)->run($phinxInput, $output);

        if ($returnCode !== 0) {
            return $this->error('Failed to create a new seeder through the cmder. Try using vendor/bin/phinx seed:create ' . $input->getArgument('name') . ' -c ./config/phinx.php');
        }
    }

    /**
     * Command arguments.
     *
     * @return array
     */
    protected function arguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the migration to create in CamelCase.'],
        ];
    }
    /**
     * Command options.
     *
     * @return array
     */
    protected function options(): array
    {
        // @TODO: Same as CreateMigrationCommand
        return [
            //
        ];
    }
}