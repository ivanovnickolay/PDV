<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.02.2018
 * Time: 00:16
 */

namespace App\Utilits\loadDataExcel\handlerRow;


/**
 * Обработчик который реализует фукционал сохранения
 * данных полученных из файла в базу данных
 *
 * !!! валидация тут не проводится!!!
 *
 * Class handlerRowsSaveReestrIn
 * @package App\Utilits\loadDataExcel\handlerRow
 */

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class handlerRowsSaveReestrIn
 * @package App\Utilits\loadDataExcel\handlerRow
 */
class handlerRowsSave extends handlerRowAbstract
{

    /**
     * реализация по строчной обработки
     * -    формируем сущность на основании массива данных
     * -    сохраняем в кеше для последующего сохранения
     * -    для сохранения памяти обнуляем сущность
     * @param array $data
     *
     */
    public function handlerRow(array $data)
    {
        $objEntity = $this->entity->createReestr($data);
        try {
            $this->entityManager->persist($objEntity);
        } catch (ORMException $e) {
            echo $e->getMessage();
        }
        $this->entity->unsetReestr();
    }

    /**
     * Практическая реализация сохранения в базе обработанных строк
     * -    если в handlerRow происходит
     *      -    $this->entityManager->persist ($e);
     * -    то в saveProcessedRows
     *     -    $this->entityManager->flush ();
     *     -    $this->entityManager->clear ();
     *
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     */
    public function saveHandlingRows()
    {
        // решенние проблемы PDO::beginTransaction(): MySQL server has gone away
        // https://stackoverflow.com/questions/33250453/how-to-solve-general-error-2006-mysql-server-has-gone-away
        // interactive_timeout & wait_timeout == 28800
        $this->reconnect();
        try {
            $this->entityManager->flush();
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
        }
        $this->entityManager->clear();
    }

    /**
     * сохранение данных из кеша
     *  -   если класс кеша не передан или кеширующий массив пустой - \Exception
     *  -   для каждого элемента массива кеша десереализируем объект который записываем в Doctrine
     *  -   если номер элемента кратен 1000 то сохраняем в базе
     *  -   если количество записей было меньше 1000 то запись в базу по выходу из foreach
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Exception если класс кеша не передан или кеширующий массив пустой
     */
    public function saveHandlingRowsWithCache(){
        if (is_null($this->objCache)){
            throw new \Exception("Вызов saveHandlingRowsWithCache при отсутствиии объекта cacheDataRow");
        }
        $arrayFromCache = $this->objCache->getArrayCache();
        if(empty($arrayFromCache)){
            throw new \Exception("Кеш объектов пустой");
        }
        $counter = 0;
        foreach ($arrayFromCache as $value){
            $objEntity = unserialize($value);

                try {
                    $this->entityManager->persist($objEntity);
                } catch (ORMException $e) {
                    echo $e->getMessage();
                }
                   unset($objEntity);
                $counter++;
                    if(0==($counter/1000)-round($counter/1000)){
                        $this->saveHandlingRows();
                    }
        }
        $this->saveHandlingRows();

    }
    /**
     * Практическая реализация возврата результата обработки всех строк файла
     * @return mixed
     */
    public function getResultHandlingAllRows()
    {
      return null;
    }

    // http://seyferseed.ru/ru/php/reshenie-problemy-doctrine-2-i-mysql-server-has-gone-away.html#sthash.vh49fkii.dpbs

    private function disconnect()
    {
        $this->entityManager->getConnection()->close();
    }

    private function connect()
    {
        $this->entityManager->getConnection()->connect();
    }

    /**
     * MySQL Server has gone away
     * @throws ORMException
     */
    private function reconnect()
    {
        $connection = $this->entityManager->getConnection();
        if (!$connection->ping()) {

            $this->disconnect();
            $this->connect();

            try {
                $this->checkEMConnection($connection);
            } catch (ORMException $e) {
            }
        }
    }

    /**
     * method checks connection and reconnect if needed
     * MySQL Server has gone away
     *
     * @param $connection
     * @throws \Doctrine\ORM\ORMException
     */
    private function checkEMConnection($connection)
    {

        if (!$this->entityManager->isOpen()) {
            $config = $this->entityManager->getConfiguration();

            $this->em = $this->entityManager->create(
                $connection, $config
            );
        }
    }
}