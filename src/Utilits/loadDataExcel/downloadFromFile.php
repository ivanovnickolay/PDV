<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 20.02.2018
 * Time: 01:14
 */

namespace App\Utilits\loadDataExcel;


use App\Utilits\loadDataExcel\cacheDataRow\cacheDataRow;
use App\Utilits\loadDataExcel\configLoader\configLoader_interface;
use App\Utilits\loadDataExcel\configLoader\configLoaderFactory;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use App\Utilits\loadDataExcel\handlerRow\handlerRowsSave;
use App\Utilits\loadDataExcel\handlerRow\handlerRowsValid;
use Doctrine\ORM\EntityManager;

/*
 * Задача класса - централизировать настройки и порядок действий
 * для проверки данных в файлах и загрузки информации
 *
 * todo ТЕСТЫ !!!
 */

class downloadFromFile
{

    /**
     * @var EntityManager
     */
    private $entytiManager;
    private $fileName;
    /**
     * @var configLoader_interface
     */
    private $config;
    /**
     * @var loadRowsFromFile
     */
    private $load;

    /**
     * @var cacheDataRow
     */
    private $cacheArray;

    /**
     *
     * Инициализация класса валидации и загрузки данных из файлов
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entytiManager=$entityManager;
            $this->load=null;
                $this->config=null;
                    $this->cacheArray=null;
    }

    /**
     * Получаем название файла
     * Инициализируем классы:
     *  - получения конфигурации чтения информации из файлов данного типа
     *  - инициализацию класса для чтения информации
     *
     *
     * При наличии исключений - обрабатываем и генерируем исключение   errorLoadDataException
     * @param $fileName string название файла с полным путем к нему
     * @throws errorLoadDataException при наличии каких либо ошибок создания объектов
     */
    public function setFileName($fileName){
        $this->fileName  = $fileName;
               try {
                   $this->config = configLoaderFactory::getConfigLoad($this->fileName);
                        $this->load = new loadRowsFromFile($this->fileName);
               }catch (errorLoadDataException $exception){
                   throw new errorLoadDataException($exception->getMessage(). " File name ". $this->fileName);
               }
    }

    /**
     * установка класса для кеширования строк прочитанных их файла
     * @param cacheDataRow $cacheArray
     */
    public function setCacheArray(cacheDataRow $cacheArray=null): void
    {
        $this->cacheArray = $cacheArray;
    }


    /**
     * Метод выполняет валидацию всех строк в файле.
     * Если во время валидации найдены ошибки - они передаются из метода в виде массива
     * если ошибок нет - передается пустой массив
     *
     * Перед началом загрузки и обработки строк с данными необходимо проверить чтобы
     *  вызов метода был позже метода в котором устанавливается название файла и создаются необходимые объекты
     *
     * @return array с списком ошибок в виде номер_строки => ошибки_в_строке
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws errorLoadDataException
     */
    public function downloadDataAndValid():array {
       try {
           $this->validParametrs();
       }catch (errorLoadDataException $e){
           throw new errorLoadDataException($e->getMessage());
       }
        $handler = new handlerRowsValid(
            $this->entytiManager,
            $this->config
        );
       if (!is_null($this->cacheArray)){
           $handler->setCache($this->cacheArray);
       }
        $this->load->setHandlerRows($handler);
        $this->load->loadDataFromFile();
        return $handler->getResultHandlingAllRows();
    }

    /**
     * Метод производит загрузку данных в базу данных.
     * крайне желательно вызывать метод ПОСЛЕ метода downloadDataAndValid()
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws errorLoadDataException
     */
    public function downloadDataAndSave(){
        try {
            $this->validParametrs();
        }catch (errorLoadDataException $e){
            throw new errorLoadDataException($e->getMessage());
        }

        $handler = new handlerRowsSave(
            $this->entytiManager,
            $this->config
        );
        // если объекта для кеширования не существует
        if (is_null($this->cacheArray)){
            // загружаем данные из файла и сохраяем
            $this->load->setHandlerRows($handler);
                $this->load->loadDataFromFile();
        } else {
            // если объект для кеширования существует
            $handler->setCache($this->cacheArray);
            // используем его для сохранения кешированных данных
            $handler->saveHandlingRowsWithCache();
        }

    }

    /**
     * Проверяем что бы все необходимые параметры были установлены перед началом работы с файлом
     *
     * @throws errorLoadDataException
     */
    private function validParametrs(): void
    {
    // если вызов метода произошел до установки названия файла
        // и следовательно до создания конфигурации загрузки данных
        if (is_null($this->config)) {
            throw new errorLoadDataException("Ошибка при поиске конфигурации для загрузки данных. Не могу загрузить обработчик для файла");
        }
        if (is_null($this->load)) {
            throw new errorLoadDataException("Ошибка при загрузке данных для валидации. Не найден объект loadRowsFromFile");
        }
    }

    /**
     * Анулирует все используемые классом вспомагательные объекты для освобождения памяти
     */
    public function unSetAllObjects(){
        unset($this->load);
            unset($this->config);
        unset($this->fileName);
    }


}