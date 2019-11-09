<?php

namespace App\Console\Commands\Generators\Migrations;

use App\Console\Command;
use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RollbackMigrationCommand extends Command
{
    /**
     * The phinx command to run.
     */
    const PHINX_COMMAND = 'rollback';

    /**
     * The name of the command.
     *
     * @param string
     */
    protected $command = 'db:rollback';

    /**
     * The description of the command.
     *
     * @param string
     */
    protected $description = 'Undo previous migrations. This is the opposite of the migrate command.';

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
            return $this->error('Failed to roll back the migrations through the cmder. Try using vendor/bin/phinx rollback -c ./config/phinx.php');
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
            ['target', 't', InputOption::VALUE_OPTIONAL, 'To rollback all migrations to a specific version. Specifying 0 as the target version will revert all migrations.', 0],
            ['all', 'a', InputOption::VALUE_OPTIONAL, 'Revert all migrations.', 0],
            ['date', 'd', InputOption::VALUE_OPTIONAL, 'To rollback all migrations to a specific date.', null],
        ];
    }
}