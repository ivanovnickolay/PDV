<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.09.2016
 * Time: 18:40
 */

namespace App\Utilits\loadDataExcel\loadData;


use App\Utilits\loadDataExcel\createEntityForLoad;
use App\Utilits\loadDataExcel\createEntityForLoad\interfaceEntityForLoad\createEntityForLoad_interface;
use App\Utilits\loadDataExcel\createReaderFile\getReaderExcel;
use App\Utilits\loadDataExcel\configLoader\configLoader_interface;
use Doctrine\ORM\EntityManager;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;

/**
 * Класс служит для загрузки данных из файлов
 * Class loadData
 * @package AnalizPdvBundle\Utilits\loadData
 * @deprecated заменено на loadRows::class
 */
class loadData
{
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * @var getReaderExcel
	 */
	private $readerFile;

	/**
	 * @var createEntityForLoad_interface
	 */
	private $entity;
	/**
	 * @var int|null
	 */
	private $maxReadRow;


    /**
     * loadData constructor.
     * @param EntityManager $em
     * @param string $fileName
     * @param configLoader_interface $configLoad класс который содержит конфигурацию для загрузки
     * @throws errorLoadDataException вызывается при ошибках в данных конфигурации
     */
	public function __construct (EntityManager $em,string $fileName, configLoader_interface $configLoad)
	{
		$this->em=$em;
		// проверим есть ли в конфигурации значение сущности для загрузки
		if (!empty($configLoad->getEntityForLoad())){
			$this->setEntity($configLoad->getEntityForLoad());
		} else {
			throw new errorLoadDataException('Не указана Entity в которую должны загружаться данные ');
		}
		// проверим есть ли в конфигурации значение последнего столюбца с данными
		if (!empty($configLoad->getLastColumn())){
			$this->getReaderFile($fileName, $configLoad->getLastColumn());
		} else{
			throw new errorLoadDataException('Не указан последний столбец для чтения данных из файла');
		}
		$this->maxReadRow=$configLoad->getMaxReadRow();

	}

    /**
     * Получение и настройка Readerа
     * @param string $fileName
     * @param string $columnLast
     * @throws errorLoadDataException
     */
	private function getReaderFile(string $fileName, string $columnLast): void
	{
		$this->readerFile = new getReaderExcel($fileName,$this->maxReadRow);
		// настраиваем фильтр
		$this->readerFile->createFilter($columnLast);
		// получаем Ридер
		$this->readerFile->getReader();
	}


	public function __destruct ()
	{
		unset($this->readerFile);
		unset($this->validator);
		unset($this->entity);
	}

    /**
     * Получаем сущность для загрузки данных
     * @param createEntityForLoad_interface $entity
     */
	private function setEntity(createEntityForLoad_interface $entity)
	{
		$this->entity=$entity;
	}

    /**
     * Чтение данных из файла кусками по количеству записей указаных в maxReadRow
     *  после чтения maxReadRow происходит запись сущностей  в базу
     *  обнуление прочитанных данных
     *  чтание новой порции данных
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     */
	public function loadData()
	{
		$maxRowToFile = $this->readerFile->getMaxRow ();
        for ($startRow = 2; $startRow <= $maxRowToFile; $startRow += $this->readerFile->getFilterChunkSize())
        {
            $this->readerFile->loadDataFromFileWithFilter ($startRow);
            $maxRowReader = $this->readerFile->getFilterChunkSize () + $startRow;
            if ($maxRowReader > $maxRowToFile) {
                // специально что бы была прочитана последняя строка с данными
                $maxRowReader = $maxRowToFile + 1;
            }
            for ($d = $startRow; $d < $maxRowReader; $d ++) {
                // решенние проблемы PDO::beginTransaction(): MySQL server has gone away
                $this->reconnect();
                // Читаем строку из файла и возвращаем данные как массив
                $arr = $this->readerFile->getRowDataArray ($d);
                // создаем сущность с данными на основании полученного из файла массива
                $e = $this->entity->createReestr ($arr);
                // передаем сущность для сохрания в кеше
                $this->em->persist ($e);
                $this->entity->unsetReestr ();
            }
            // после окончания цикла чтения
            // сохраняем данные в базу
            $this->em->flush ();
            // очищаем кешш
            $this->em->clear ();
            // очищаем загрузчик данных
            $this->readerFile->unset_loadFileWithFilter ();
            //http://ru.php.net/manual/ru/features.gc.collecting-cycles.php
            gc_enable();
            gc_collect_cycles();
        }
	}

		// http://seyferseed.ru/ru/php/reshenie-problemy-doctrine-2-i-mysql-server-has-gone-away.html#sthash.vh49fkii.dpbs

    private function disconnect()
	{
		$this->em->getConnection()->close();
	}

    private function connect()
	{
		$this->em->getConnection()->connect();
	}

		/**
		 * MySQL Server has gone away
		 */
		private function reconnect()
	{
		$connection = $this->em->getConnection();
		if (!$connection->ping()) {

			$this->disconnect();
			$this->connect();

			$this->checkEMConnection($connection);
		}
	}

		/**
		 * method checks connection and reconnect if needed
		 * MySQL Server has gone away
		 *
		 * @param $connection
		 * @throws \Doctrine\ORM\ORMException
		 */
		protected function checkEMConnection($connection)
	{

		if (!$this->em->isOpen()) {
			$config = $this->em->getConfiguration();

			$this->em = $this->em->create(
				$connection, $config
			);
		}
	}


}