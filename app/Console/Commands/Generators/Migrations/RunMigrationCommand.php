<?php

namespace App\Console\Commands\Generators\Migrations;

use App\Console\Command;
use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunMigrationCommand extends Command
{
    /**
     * The phinx command to run.
     */
    const PHINX_COMMAND = 'migrate';

    /**
     * The name of the command.
     *
     * @param string
     */
    protected $command = 'db:migrate';

    /**
     * The description of the command.
     *
     * @param string
     */
    protected $description = 'Run all the available migrations or just a specific version.';

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
            '-c' => './config/phinx.php',
        ]);

        // Run the phinx command to create a new migration and show a friendly message.
        $returnCode = $phinx->find(self::PHINX_COMMAND)->run($phinxInput, $output);

        if ($returnCode !== 0) {
            return $this->error('Failed to run the migrations through the cmder. Try using vendor/bin/phinx migrate -c ./config/phinx.php');
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
            //
        ];
    }
    /**
     * Command options.
     *
     * @return array
     */
    protected function options(): array
    {
        return [
            ['target', 't', InputOption::VALUE_OPTIONAL, 'To migrate to a specific version. Use the timestamp to specify the version.', null],
        ];
    }
}