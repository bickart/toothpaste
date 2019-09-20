<?php

namespace Toothpaste\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Toothpaste\Sugar;

class MassModuleRetrievalCommand extends Command
{
    protected static $defaultName = 'sugar:massretrieve';

    protected function configure()
    {
        $this
            ->setDescription('Mass module retrieval')
            ->setHelp('Retrive all records of a module')
            ->addOption('url', null, InputOption::VALUE_REQUIRED, 'Base instance url')
            ->addOption('user', null, InputOption::VALUE_REQUIRED, 'Username')
            ->addOption('pass', null, InputOption::VALUE_REQUIRED, 'Password')
            ->addOption('module', null, InputOption::VALUE_REQUIRED, 'Module to retrieve data from')
            ->addOption('dir', null, InputOption::VALUE_REQUIRED, 'Output directory')
            ->addOption('limit', null, InputOption::VALUE_OPTIONAL, 'Limit', 1000)
            ->addOption('filter', null, InputOption::VALUE_OPTIONAL, 'Filter', [])
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getOption('url');
        $user = $input->getOption('user');
        $pass = $input->getOption('pass');
        $module = $input->getOption('module');
        $dir = $input->getOption('dir');
        $limit = $input->getOption('limit');
        $filter = $input->getOption('filter');

        $output->writeln('Executing retrival of all records for module ' . $module  . ' in chunks of ' . $limit  . ' records, and saving them into ' . $dir . ' ...');

        \Toothpaste\Toothpaste::resetStartTime();

        if (!empty($url) && !empty($user) && !empty($pass) && !empty($module) && !empty($dir) && !empty($limit)) {
            $logic = new Sugar\Actions\MassRetrieverApi($url, 'base', $user, $pass, $module); 
            $output->writeln('Connecting to url ' . $url . '...');
            $savedCount = $logic->initiateRetrieve($module, $filter, $limit, $dir);
            $output->writeln('Saved ' . $savedCount . ' ' . strtolower($module) . ' records in ' . $dir);
        } else {
            $output->writeln('Please make sure all required parameters are passed correctly to the command. Aborting...');
        }
    }
}
