<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.02.2018
 * Time: 00:49
 */

namespace App\Utilits\loadDataExcel\handlerRow;
use App\Entity\ReestrbranchIn;
use App\Entity\ReestrbranchOut;
use App\Entity\Repository\SprBranchRepository;
use App\Entity\SprBranch;
use App\Utilits\loadDataExcel\configLoader\configLoader_interface;
use App\Utilits\loadDataExcel\configLoader\configLoadReestrOut;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;


/**
 * Класс реализует функциональность проверки данных полученных в строке
 * Class handlerRowsValid
 * @package App\Utilits\loadDataExcel\handlerRow
 *
 * @see handlerRowsValidDataFromFileTest_ReestrIn
 * @see handlerRowValidDataFromArrayTest_ReestrIn
 * @see handlerRowValidDataFromArrayTest_ReestrOut
 */
class handlerRowsValid extends handlerRowAbstract
{
    /**
     * хранит массив ошибок валидации как ключ => значение
     *      - ключ = номер строки, которая содержит документ с ошибкой
     *      - значение = список ошибок валидации
     * @var array
     */
    private $allErrorsValidation;

    /**
     * номер строки которая проверяется. Используется
     * для создания адресного указания строки с ошибкой
     * @var integer
     */
    private $countRows;
    /**
     * Текущий месяц реестра - по первой строке данынх файла
     * @var integer
     */
    private $monthReestr;
    /**
     * Текущий год реестра - по первой строке данынх файла
     * @var integer
     */
    private $yearReestr;
    /**
     * Текущий номер филиала - по первой строке данынх файла
     * @var string
     */
    private $numBranchReestr;
    /**
     *  класс валидации данных сущностей
     * @var \Symfony\Component\Validator\Validator\RecursiveValidator|\Symfony\Component\Validator\Validator\ValidatorInterface
     */
    private $validator;


    /**
     * handlerRowsValid constructor.
     * @param EntityManager $entityManager
     * @param configLoader_interface $configLoader
     */
    public function __construct(EntityManager $entityManager, configLoader_interface $configLoader)
    {
       parent::__construct($entityManager,$configLoader);
       // инициализация массива для ошибок
        $this->allErrorsValidation=array();
        // инициализация валидатора
        $this->validator = Validation::createValidatorBuilder()
            ->addMethodMapping('loadValidatorMetadata')
            ->getValidator();
        // начальное значение строки = 1
        // первая строка - это шапка таблицы
        $this->countRows=1;
    }


    /**
     * реализация по строчной обработки
     * создаем сущность на основании данных
     *  -   добавляем объект в кеш
     *  -   проверяем на общие значения первой строки $this->verifyFirstRow($obj);
     *  -   проверяем данные на стабильность периода РПН и отправителя $this->verifyStabilityIndicators($obj);
     *  -   проверяем остальные правила валидации  $this->verifyRow($obj);
     *
     * @param array $data
     */
    public function handlerRow(array $data)
    {
        $obj=$this->entity->createReestr($data);
            $this->setRowToCache($obj);
            $this->countRows++;
                $this->verifyFirstRow($obj);
                    $this->verifyStabilityIndicators($obj);
                        $this->verifyRow($obj);
    }

    /**
     *  На этапе обработки порции строк возвращать не чего не надо
     */
    public function saveHandlingRows()
    {
        return null;
    }

    /**
     * Практическая реализация возврата результата обработки всех строк файла
     * @return mixed
     */
    public function getResultHandlingAllRows()
    {
        return $this->allErrorsValidation;
    }


    /**
     * добавляет ошибки к списку ошибок глобальной проверки файла.
     * Использует как ключ массива с ошибками номер проверяемой строки,
     * которая находится в $this->countRows
     * @param $error ConstraintViolationListInterface
     */
    private function addErrorToArray(ConstraintViolationListInterface  $error){
        /*
         * Uses a __toString method on the $errors variable which is a
         * ConstraintViolationList object. This gives us a nice string
         * for debugging.
         */
        $errorsString="";
        if(!empty($error)){
            $countElementsError=count($error);
            for ($i=0;$i<=$countElementsError-1;$i++){
                $errorsString = $errorsString.(string) $error->get($i)->getMessage()." | ";
            }

            // проверим есть ли у же в массиве запись с ошибками по этой строке
            if (key_exists($this->countRows,$this->allErrorsValidation)){
                // если есть - дописываем в конец строки значений
                $this->allErrorsValidation[$this->countRows].=$errorsString. " | ";
            }else{
                // если нет то создаем новую запись
                $this->allErrorsValidation[$this->countRows]=$errorsString. " | ";
            }
        }
    }

    /**
     * используется только для РПН !!
     * проверяет только первую строку с данными на следующие ошибки
     *  -   отсутствие в РПН записей с указанной в первой строке
     *      - месяце
     *      - годе
     *      - номере филиала
     * - проверяет если ли указанный в первой строке номере филиал среди номеров филиалов,
     * которому разрешена подача документов в главный офис
     * проверенные значения записываются в значения полей
     *
     *
     * @param $obj ReestrbranchIn | ReestrbranchOut
     */
    private function verifyFirstRow($obj){
        // если номер текущей строки равен двум
        if ($this->countRows==2){
            // если передан объект нужного типа
            if (($obj instanceof ReestrbranchIn)
               or($obj instanceof ReestrbranchOut)) {
                   // проверим имеет ли право филиал подавать этот документ
                   if (!$this->isValidNumBranch($obj)){
                       // если не иммет права подавать
                       if (key_exists($this->countRows,$this->allErrorsValidation)){
                           // если есть - дописываем в конец строки значений
                           $this->allErrorsValidation[$this->countRows].="Филиал не имеет право подавать РПН на уровень ЦФ | ";
                       }else{
                           // если нет то создаем новую запись
                           $this->allErrorsValidation[$this->countRows]="Филиал не имеет право подавать РПН на уровень ЦФ | ";
                       }
                   }
                   // нет ли в базе данных по этому РПН, кторые загружены ранее
                   if (!$this->isValidReestr($obj)){
                       // если таки подавал ранее
                       if (key_exists($this->countRows,$this->allErrorsValidation)){
                           // если есть - дописываем в конец строки значений
                           $this->allErrorsValidation[$this->countRows].="Филиал уже подавал РПН за этот период ранее | ";
                       }else{
                           // если нет то создаем новую запись
                           $this->allErrorsValidation[$this->countRows]="Филиал уже подавал РПН за этот период ранее | ";
                       }
                   }
                   $this->monthReestr = $obj->getMonth();
                   $this->yearReestr = $obj->getYear();
                   $this->numBranchReestr = $obj->getNumBranch();
               }
        }
    }

    /**
     * запрос на проверку отсутствия в РПН записей с указанной в первой строке
     *      - месяце
     *      - годе
     *      - номере филиала
     *
     *  - если данных нет возвращает  ложь - валидация не прошла
     *  - если данные есть  = возвращает истинно - валидация прошла
     * @param $obj ReestrbranchOut|ReestrbranchIn
     * @return boolean
     */
    private function isValidReestr($obj):bool {
        $repository = $this->getRepositoryEntity($obj);
        $resultQuery=$repository->findOneBy(array(
            "month"=>$obj->getMonth(),
            "year"=>$obj->getYear(),
            "numBranch"=>$obj->getNumBranch()
        )
        );
        //если вернулся пустой массив = данных нет
        if (empty($resultQuery)){
            // филиал не подавал РПН ранее
            return true;
        } else {
            // филиал подавал отчет ранее
            return false;
        }
    }

    /**
     * запрос на проверку есть ли указанный в первой строке филиал, среди филиалов,
     * которому разрешена подача документов в главный офис
     *
     * если данные есть -
     * @param $obj ReestrbranchOut|ReestrbranchIn
     * @return bool
     */
    private function isValidNumBranch($obj):bool {
        $repository = $this->entityManager->getRepository(SprBranch::class);
        $reportQuery=$repository->findOneBy(
            array(
                "numMainBranch"=>$obj->getNumBranch()
            )
        );
        //если вернулся пустой массив = данных нет
        if (empty($reportQuery)){
            //валидация не прошла
            return false;
        } else {
            //валидация прошла
            return true;
        }
    }

    /**
     * Мини фабриака по централизации вызова репозиториев классов котоые будут проверяться
     * @param $obj ReestrbranchOut|ReestrbranchIn
     * @return \App\Entity\Repository\ReestrBranch_in|\App\Entity\Repository\ReestrBranch_out|\Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     */
    private function getRepositoryEntity($obj){
        if (($obj instanceof ReestrbranchIn)) {
            return $this->entityManager->getRepository(ReestrbranchIn::class);
        }
        if (($obj instanceof ReestrbranchOut)) {
            return $this->entityManager->getRepository(ReestrbranchOut::class);
        }
        return null;
    }

    /**
     * Реализация проверки строк путем вызова валидатора проверяемой сущности
     *  ошибки при наявности записываются в массив $this->allErrorsValidation
     * @param $obj ReestrbranchOut|ReestrbranchIn
     */
    private function verifyRow($obj): void
    {
       $error = $this->validator->validate($obj);
        if (count($error) != 0) {
            $this->addErrorToArray($error);
        }
    }

    /**
        Если это не первая строка то проверим не изменность года, месяца и номера филиала
        данным первой строки. Если не совпадают - генерируем ошибку

     * @param $obj ReestrbranchOut|ReestrbranchIn
     */
    private function verifyStabilityIndicators($obj): void
    {
        if ($this->countRows>=3) {
            $err = "";
            if ($this->monthReestr != $obj->getMonth()) {
                $err .= "Месяц реестра не соответствует месяцу указанному в первой строке файла! ";
            }
                if ($this->yearReestr != $obj->getYear()) {
                    $err .= "Год реестра не соответствует году указанному в первой строке файла! ";
                }
                    if ($this->numBranchReestr != $obj->getNumBranch()) {
                        $err .= "Номер филиала реестра не соответствует номеру указанному в первой строке файла! ";
                    }
            if (!empty($err)){
                $this->allErrorsValidation[$this->countRows] = $err;
            }

        }
    }

    /**
     * добавление объекта в кеш
     * @param $object
     * @throws \Exception
     */
    private function setRowToCache($object){
        if (!is_null($this->objCache)){
            $this->objCache->addData($object);
        }
    }
}