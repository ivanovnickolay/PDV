<?php

namespace App\Command;

use App\Services\loadDataInFile_ERPN;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use Doctrine\DBAL\DBALException;
use Symfony\Component\{
    Console\Command\Command, Console\Input\InputArgument, Console\Input\InputInterface, Console\Input\InputOption, Console\Output\OutputInterface, Console\Style\SymfonyStyle
};

/**
 * Class LoadDataToErpnInCommand комманда для загрузки данных из файла *.csv в таблицу erpn_in
 *
 * @package App\Command
 */
class LoadDataToErpnInCommand extends Command
{
    protected static $defaultName = 'loadDataToErpnIn';
    /**
     * @var loadDataInFile_ERPN
     */
    private $loader;

    /**
     * LoadDataToErpnInCommand constructor.
     * @param loadDataInFile_ERPN $loader "тянется" через автозагрузку сервиса с полными настройками путей
     */
    public function __construct(loadDataInFile_ERPN $loader){
        $this->loader  = $loader;
        parent::__construct();
    }

    /**
     *  для работы комманды необходимо название файла который будем загружать
     */
    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('fileName', InputArgument::REQUIRED, 'Название файла с данными для загрузки ')
            ->setDescription("Загрузка данных из файла в таблицу ЕРПН полученные");
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws errorLoadDataException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileName = $input->getArgument('fileName');
       if (is_null($fileName)){
           throw new errorLoadDataException("Не указано название файла с данными для загрузки !");
       }
       try{
           $this->loader->load_ERPN_In($fileName);
       } catch (errorLoadDataException $exception ){
           echo $exception->getMessage();
       } catch (DBALException $exception){
           echo $exception->getMessage();
       }



    }
}
