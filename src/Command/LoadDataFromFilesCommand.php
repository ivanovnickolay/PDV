<?php

namespace App\Command;

use App\Services\LoadReestrFromFile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Команда для загрузки данных из файлов
 *
 * Class LoadDataFromFilesCommand
 * @package App\Command
 */
class LoadDataFromFilesCommand extends Command
{
    protected static $defaultName = 'loadDataFromFiles';

    /**
     * Сервис загрузки данных из файла
     * @var LoadReestrFromFile
     */
    private $loadReestrFromFile;

    public function __construct(LoadReestrFromFile $loadReestrFromFile){
        $this->loadReestrFromFile = $loadReestrFromFile;
        parent::__construct();
    }

    protected function configure()
    {
        $this
         ->setDescription('Загрузка данных из файлов Excel')
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
       ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
            $this->loadReestrFromFile->execute();
    }
}
