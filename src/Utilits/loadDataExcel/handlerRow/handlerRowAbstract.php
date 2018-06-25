<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31.01.2018
 * Time: 23:48
 */

namespace App\Utilits\loadDataExcel\handlerRow;
use App\Utilits\loadDataExcel\cacheDataRow\cacheDataRow;
use App\Utilits\loadDataExcel\configLoader\configLoader_interface;
use App\Utilits\loadDataExcel\loadData\loadRows;
use App\Utilits\LoadInvoice\createEntity\createEntityInterface;
use Doctrine\ORM\EntityManager;


/**
 * Абстрактный класс описывает фукнкции которые вызывваются для обработки строки
 * с данными которая выгружается из файла.
 *
 * Основные задачи которые решает обработчик
 * - разделение ответственности за обработку данных, а именно:
 *      -   класс @see loadRows реализует чтение данных функцией  @see loadRows::readRows()
 *      -   этот клас -  построчную обработку полученных данных
 * - расширение функционала программы при помощи разных обработчиков:
 *      -   проверки данных
 *      -   сохранение данных
 * Абстрактные функции должны реализовать
 * @see handlerRowAbstract::handlerRow() вызывается для оброботки каждой строки с данными
 * @see handlerRowAbstract::saveHandlingRows() вызывается после обработки порции строк сданными
 * @see handlerRowAbstract::getResultHandlingAllRows() вызывается после окончания обработки всех строк с данными
 * Interface handlerRowInterface
 * @package App\Utilits\loadDataExcel\handlerRow
 */
abstract class handlerRowAbstract
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    protected $entity;

    /**
     * @var configLoader_interface
     */
    protected $configLoader;


    /**
     * объект для реализации кеширования информации
     * @var cacheDataRow
     */
    protected $objCache;
    /**
     * handlerRowAbstract constructor.
     * @param EntityManager $entityManager
     * @param configLoader_interface $configLoader
     */
    public function __construct(EntityManager $entityManager, configLoader_interface $configLoader)
    {
        $this->configLoader=$configLoader;
        $this->entityManager=$entityManager;
        /** @var createEntityInterface $this */
        $this->entity=$this->configLoader->getEntityForLoad();
        $this->objCache=null;
    }

    /**
     * реализация по строчной обработки
     * @param array $data
     * @return mixed
     */
    public abstract function handlerRow(array $data);

    /**
     * Практическая реализация сохранения в базе обработанных строк
     * -    если в handlerRow происходит
     *      -    $this->entityManager->persist ($e);
     * -    то в saveProcessedRows
     *     -    $this->entityManager->flush ();
     *     -    $this->entityManager->clear ();
     *
     * выполняется после окончания обработки порции прочитанных строк
     * @return mixed
     */
    public abstract function saveHandlingRows();

    /**
     * Практическая реализация возврата результата обработки всех строк файла
     * @return mixed
     */
    public abstract function getResultHandlingAllRows();

    /**
     * Реализация передачи в обработчик объекта для кеширования строк
     * todo после реализации класса кеширования уточнить тип объекта который передается в метод !!
     * @param cacheDataRow $objCache
     */
    public function setCache(cacheDataRow $objCache){
       $this->objCache = $objCache;
    }


}