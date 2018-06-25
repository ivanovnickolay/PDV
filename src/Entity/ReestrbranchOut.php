<?php

namespace App\Entity;

use App\Entity\forForm\validationConstraint\ContainsNumDoc;
use App\Utilits\workToTypeVariable\workToTypeVariable;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ReestrbranchOut
 */
class ReestrbranchOut
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $month = '0';

    /**
     * @var integer
     */
    private $year = '0';

    /**
     * @var string
     */
    private $numBranch;

    /**
     * @var \DateTime
     */
    private $dateCreateInvoice;

    /**
     * @var string
     */
    private $numInvoice;

    /**
     * @var string
     */
    private $typeInvoiceFull;

    /**
     * @var string
     */
    private $typeInvoice;

    /**
     * @var string
     */
    private $nameClient;

    /**
     * @var string
     */
    private $innClient;

    /**
     * @var float
     */
    private $zagSumm;

    /**
     * @var float
     */
    private $baza20;

    /**
     * @var float
     */
    private $pdv20;

    /**
     * @var float
     */
    private $baza7;

    /**
     * @var float
     */
    private $pdv7;

    /**
     * @var float
     */
    private $baza0;

    /**
     * @var float
     */
    private $bazaZvil;

    /**
     * @var float
     */
    private $bazaNeObj;

    /**
     * @var float
     */
    private $bazaZaMezhiTovar;

    /**
     * @var float
     */
    private $bazaZaMezhiPoslug;

    /**
     * @var \DateTime
     */
    private $rkeDateCreateInvoice;

    /**
     * @var string
     */
    private $rkeNumInvoice;

    /**
     * @var string
     */
    private $rkePidstava;

    /**
     * @var string
     */
    private $keyField;

    private $month_create_invoice;

    /**
     * @return mixed
     */
    public function getMonthCreateInvoice ()
    {
        return $this->month_create_invoice;
    }

    /**
     * @param mixed $month_create_invoice
     */
    public function setMonthCreateInvoice ($month_create_invoice)
    {
        $this->month_create_invoice = $month_create_invoice;
    }

    /**
     * @return mixed
     */
    public function getYearCreateInvoice ()
    {
        return $this->year_create_invoice;
    }

    /**
     * @param mixed $year_create_invoice
     */
    public function setYearCreateInvoice ($year_create_invoice)
    {
        $this->year_create_invoice = $year_create_invoice;
    }
    private $year_create_invoice;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set month
     *
     * @param integer $month
     *
     * @return ReestrbranchOut
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return ReestrbranchOut
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set numBranch
     *
     * @param string $numBranch
     *
     * @return ReestrbranchOut
     */
    public function setNumBranch($numBranch)
    {
        $this->numBranch = $numBranch;

        return $this;
    }

    /**
     * Get numBranch
     *
     * @return string
     */
    public function getNumBranch()
    {
        return $this->numBranch;
    }

    /**
     * Set dateCreateInvoice
     *
     * @param \DateTime $dateCreateInvoice
     *
     * @return ReestrbranchOut
     */
    public function setDateCreateInvoice($dateCreateInvoice)
    {
        if (new \DateTime("0000-00-00")==$dateCreateInvoice)
        {
            $this->dateCreateInvoice = null;
        } else {
            $this->dateCreateInvoice = $dateCreateInvoice;
        }
        return $this;

    }

    /**
     * Get dateCreateInvoice
     *
     * @return \DateTime
     */
    public function getDateCreateInvoice()
    {
        return $this->dateCreateInvoice;
    }

    /**
     * Set numInvoice
     *
     * @param string $numInvoice
     *
     * @return ReestrbranchOut
     */
    public function setNumInvoice($numInvoice)
    {
        $this->numInvoice = $numInvoice;

        return $this;
    }

    /**
     * Get numInvoice
     *
     * @return string
     */
    public function getNumInvoice()
    {
        return $this->numInvoice;
    }

    /**
     * Set typeInvoiceFull
     *
     * @param string $typeInvoiceFull
     *
     * @return ReestrbranchOut
     */
    public function setTypeInvoiceFull($typeInvoiceFull)
    {
        $this->typeInvoiceFull = $typeInvoiceFull;

        return $this;
    }

    /**
     * Get typeInvoiceFull
     *
     * @return string
     */
    public function getTypeInvoiceFull()
    {
        return $this->typeInvoiceFull;
    }

    /**
     * Set typeInvoice
     *
     * @param string $typeInvoice
     *
     * @return ReestrbranchOut
     */
    public function setTypeInvoice($typeInvoice)
    {
        $this->typeInvoice = $typeInvoice;

        return $this;
    }

    /**
     * Get typeInvoice
     *
     * @return string
     */
    public function getTypeInvoice()
    {
        return $this->typeInvoice;
    }

    /**
     * Set nameClient
     *
     * @param string $nameClient
     *
     * @return ReestrbranchOut
     */
    public function setNameClient($nameClient)
    {
        $this->nameClient = $nameClient;

        return $this;
    }

    /**
     * Get nameClient
     *
     * @return string
     */
    public function getNameClient()
    {
        return $this->nameClient;
    }

    /**
     * Set innClient
     *
     * @param string $innClient
     *
     * @return ReestrbranchOut
     */
    public function setInnClient($innClient)
    {
        $this->innClient = $innClient;

        return $this;
    }

    /**
     * Get innClient
     *
     * @return string
     */
    public function getInnClient()
    {
        return $this->innClient;
    }

    /**
     * Set zagSumm
     *
     * если передано пустое значение то присваием ноль
     * если передано значение вида "-7.2759576141834E-12" тоже присваеваем ноль
     * так как это 7 после 12 нулей после запятой
     *
     * @param float $zagSumm
     *
     * @return ReestrbranchOut
     */
    public function setZagSumm($zagSumm)
    {
        $this->zagSumm = workToTypeVariable::setFloatVariable($zagSumm);
        return $this;
    }

    /**
     * Get zagSumm
     *
     * @return float
     */
    public function getZagSumm()
    {
        return $this->zagSumm;
    }

    /**
     * Set baza20
     *
     * @param float $baza20
     *
     * @return ReestrbranchOut
     */
    public function setBaza20($baza20)
    {
        $this->baza20 = workToTypeVariable::setFloatVariable($baza20);
        return $this;
    }

    /**
     * Get baza20
     *
     * @return float
     */
    public function getBaza20()
    {
        return $this->baza20;
    }

    /**
     * Set pdv20
     *
     * @param float $pdv20
     *
     * @return ReestrbranchOut
     */
    public function setPdv20($pdv20)
    {
        $this->pdv20 = workToTypeVariable::setFloatVariable($pdv20);
        return $this;
    }

    /**
     * Get pdv20
     *
     * @return float
     */
    public function getPdv20()
    {
        return $this->pdv20;
    }

    /**
     * Set baza7
     *
     * @param float $baza7
     *
     * @return ReestrbranchOut
     */
    public function setBaza7($baza7)
    {
        $this->baza7 = workToTypeVariable::setFloatVariable($baza7);
        return $this;
    }

    /**
     * Get baza7
     *
     * @return float
     */
    public function getBaza7()
    {
        return $this->baza7;
    }

    /**
     * Set pdv7
     *
     * @param float $pdv7
     *
     * @return ReestrbranchOut
     */
    public function setPdv7($pdv7)
    {
        $this->pdv7 = workToTypeVariable::setFloatVariable($pdv7);
        return $this;
    }

    /**
     * Get pdv7
     *
     * @return float
     */
    public function getPdv7()
    {
        return $this->pdv7;
    }

    /**
     * Set baza0
     *
     * @param float $baza0
     *
     * @return ReestrbranchOut
     */
    public function setBaza0($baza0)
    {
        $this->baza0 = workToTypeVariable::setFloatVariable($baza0);
        return $this;
    }

    /**
     * Get baza0
     *
     * @return float
     */
    public function getBaza0()
    {
        return $this->baza0;
    }

    /**
     * Set bazaZvil
     *
     * @param float $bazaZvil
     *
     * @return ReestrbranchOut
     */
    public function setBazaZvil($bazaZvil)
    {
        $this->bazaZvil = workToTypeVariable::setFloatVariable($bazaZvil);
        return $this;
    }

    /**
     * Get bazaZvil
     *
     * @return float
     */
    public function getBazaZvil()
    {
        return $this->bazaZvil;
    }

    /**
     * Set bazaNeObj
     *
     * @param float $bazaNeObj
     *
     * @return ReestrbranchOut
     */
    public function setBazaNeObj($bazaNeObj)
    {
        $this->bazaNeObj = workToTypeVariable::setFloatVariable($bazaNeObj);
        return $this;
    }

    /**
     * Get bazaNeObj
     *
     * @return float
     */
    public function getBazaNeObj()
    {
        return $this->bazaNeObj;
    }

    /**
     * Set bazaZaMezhiTovar
     *
     * @param float $bazaZaMezhiTovar
     *
     * @return ReestrbranchOut
     */
    public function setBazaZaMezhiTovar($bazaZaMezhiTovar)
    {
        $this->bazaZaMezhiTovar = workToTypeVariable::setFloatVariable($bazaZaMezhiTovar);
        return $this;
    }

    /**
     * Get bazaZaMezhiTovar
     *
     * @return float
     */
    public function getBazaZaMezhiTovar()
    {
        return $this->bazaZaMezhiTovar;
    }

    /**
     * Set bazaZaMezhiPoslug
     *
     * @param float $bazaZaMezhiPoslug
     *
     * @return ReestrbranchOut
     */
    public function setBazaZaMezhiPoslug($bazaZaMezhiPoslug)
    {
        $this->bazaZaMezhiPoslug = workToTypeVariable::setFloatVariable($bazaZaMezhiPoslug);
        return $this;
    }

    /**
     * Get bazaZaMezhiPoslug
     *
     * @return float
     */
    public function getBazaZaMezhiPoslug()
    {
        return $this->bazaZaMezhiPoslug;
    }

    /**
     * Set rkeDateCreateInvoice
     * значение \DateTime("0000-00-00") присваивается при разборе строк в реестре если дата установлена пустой
     * если получено значение даты равное \DateTime("0000-00-00")  rkeDateCreateInvoice = null
     * иначе - присваиваем полученную дату
     * @param \DateTime $rkeDateCreateInvoice
     *
     * @return ReestrbranchOut
     */
    public function setRkeDateCreateInvoice($rkeDateCreateInvoice)
    {
        if (new \DateTime("0000-00-00")==$rkeDateCreateInvoice)
        {
            $this->rkeDateCreateInvoice = null;
        } else {
            $this->rkeDateCreateInvoice = $rkeDateCreateInvoice;
        }

        return $this;
    }

    /**
     * Get rkeDateCreateInvoice
     * если было передано нулевое значение то вернуть надо нулевую дату
     * иначе надо вернуть полученное значение
     * @return \DateTime|null
     */
    public function getRkeDateCreateInvoice()
    {
        //return $this->rkeDateCreateInvoice;
        if(null == $this->rkeDateCreateInvoice){
            return new \DateTime("0000-00-00");
        }else{
            return $this->rkeDateCreateInvoice;
        }
    }

    /**
     * Set rkeNumInvoice
     *
     * @param string $rkeNumInvoice
     *
     * @return ReestrbranchOut
     */
    public function setRkeNumInvoice($rkeNumInvoice)
    {
        $this->rkeNumInvoice = $rkeNumInvoice;

        return $this;
    }

    /**
     * Get rkeNumInvoice
     *
     * @return string
     */
    public function getRkeNumInvoice()
    {
        return $this->rkeNumInvoice;
    }

    /**
     * Set rkePidstava
     *
     * @param string $rkePidstava
     *
     * @return ReestrbranchOut
     */
    public function setRkePidstava($rkePidstava)
    {
        $this->rkePidstava = $rkePidstava;

        return $this;
    }

    /**
     * Get rkePidstava
     *
     * @return string
     */
    public function getRkePidstava()
    {
        return $this->rkePidstava;
    }

    /**
     * Set keyField
     *
     * @return ReestrbranchOut
     */
    public function setKeyField()
    {
        if ($this->dateCreateInvoice==null) {
            $this->keyField = $this->numInvoice . '/' . $this->typeInvoiceFull . '/null/' . $this->innClient;
        } else{
            $this->keyField = $this->numInvoice . '/' . $this->typeInvoiceFull . '/' . date_format ($this->dateCreateInvoice , "d-m-Y") . '/' . $this->innClient;
        }

        return $this;
    }

    /**
     * Get keyField
     *
     * @return string
     */
    public function getKeyField()
    {
        return $this->keyField;
    }

    /**
     * Проверка типа причины на соответствие правилам из Порядка № 1307
     * и пустому значению, которое тоже разрешается
     * @link https://i.factor.ua/law-303/section-1080/article-16961/
     *  если тип соответствует то возвращаем истино
     *  если тип не сответствует - ложь
     * @return bool
     */
    public function isTypeInvoiceLegal():bool {
        // массив правильных значений типов причин
        $validTypeInvoice=array(
            '01',
            '02',
            '03',
            '04',
            '05',
            '06',
            '07',
            '08',
            '08',
            '09',
            '10',
            '11',
            '12',
            '13',
            '14',
            '15'
        );
        if (empty($this->getTypeInvoice())){
            return true;
        }
        if (in_array($this->getTypeInvoice(), $validTypeInvoice)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Проверка полноты заполнения реквизитов для документа типа РКЕ
     *  -   не пустой номер РКЕ
     *  -   не пустая дата создания РКЕ
     *  -   не пустая причина создания РКЕ
     *
     * если все заполнено то возвращаем истино
     *
     * если чтото упущенно - ложь
     */
    public function isValidRKE():bool {
        if('РКЕ'==$this->getTypeInvoiceFull()){
            if(empty($this->getRkeNumInvoice())){
                return false;
            }else {
                // регулярное выражение выдает истинно если в номере есть буквы
                // а если в номере есть буква - занчит номер не верен
                if (preg_match("/[^0-9\/]/", $this->getRkeNumInvoice(), $matches))
                {
                    return false;
                }
            }
            // Пустое поле основание для создания РКЕ возможно - так как до
            // 2018 года оно не было обязательным полем при создании РКЕ
            //if(empty($this->getRkePidstava())){
            //    return false;
            //}
            if (new \DateTime("0000-00-00")==$this->getRkeDateCreateInvoice()){
                return false;
            }
            return true;
        } else{
            return true;
        }

    }

    /**
     * Проверяем правильность написание номера документов
     * Проверка производится только для документов ПНЕ и РКЕ
     *  - если документ не прошел проверку - return false;
     *  - если документ прошел проверку или документ не подлежал проверке - return true;
     * @return bool
     */
    public function isValidNumDoc(ExecutionContextInterface $context){
        // массив типов документов которые не проверяются на верный номер документа
        //echo "isValidNumDoc  ";
        $arrayNotVerified = array(
            'МДЕ',
            'ЧК',
            'ТК',
            'ГР',
            'ПО',
            'ПЗ',
            'НП',
            'БО'
        );
        $numInvoice = $this->getNumInvoice();
        if(!in_array($this->getTypeInvoiceFull(),$arrayNotVerified)){
            if (preg_match("/[^0-9\/]/", $numInvoice, $matches))
            {
                $context->buildViolation(sprintf(
                    '%s - не верный номер документа ',$numInvoice))
                    ->atPath('numInvoice')
                    ->addViolation();
            }
        }
        return true;

    }

    /**
     * Валидация сущности
     *
     * регулярное выражение для проверки денежного вида "/^-?[0-9]+(?:\.[0-9]{1,2})?$/" взято по ссылкам
     * @link https://stackoverflow.com/questions/4982291/how-to-check-if-an-entered-value-is-currency
     * @link https://regex101.com/r/GTlf5O/1
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Валидация поля month
                $metadata->addPropertyConstraint('month', new Assert\NotBlank(array(
                    'message'=>'Месяц подачи {{ value }} документа не может быть пустым '
                )));
                    $metadata->addPropertyConstraint('month', new Assert\Range(array(
                        'min'        => 1,
                        'max'        => 12,
                        'minMessage' => 'Значение месяца {{ value }} меньше 1',
                        'maxMessage'=> 'Значение месяца {{ value }} больше 12',
                        'invalidMessage'=> 'Значение месяца не число',
                                )
                    ));

        //Валидация поля year
                $metadata->addPropertyConstraint('year', new Assert\NotBlank(array(
                    'message'=>'Год подачи документа не может быть пустым '
                )));
                    $metadata->addPropertyConstraint('year', new Assert\Range(array(
                            'min'        => 2015,
                            'max'        => 2020,
                            'minMessage' => 'Значение года меньше 2015',
                            'maxMessage'=> 'Значение года больше 2020',
                            'invalidMessage'=> 'Значение месяца не число',
                        )
                    ));
        //Валидация поля numBranch
                $metadata->addPropertyConstraint('numBranch', new Assert\NotBlank(array(
                    'message'=>'Номер филиала {{ value }} не может быть пустым '
                )));
                    $metadata->addPropertyConstraint('numBranch', new Assert\Type(array(
                        'type'    => 'digit',
                        'message' => 'Номер структурного подразделения {{ value }} должен содержать только цифры .',
                    )));
                        $metadata->addPropertyConstraint('numBranch', new Assert\Length(array(
                            'min'        => 3,
                            'max'        => 3,
                            'minMessage' => 'Номер структурного подразделения {{ value }} не должен быть меньше {{ limit }} символов',
                            'maxMessage' => 'Номер структурного подразделения {{ value }} не должен быть больше {{ limit }} символов',
                        )));
        //Валидация поля $dateCreateInvoice
                $metadata->addPropertyConstraint('dateCreateInvoice',new Assert\NotNull(array(
                    'message'=>'Дата создания документа {{ value }} не может быть пустым '
                )));
                    $metadata->addPropertyConstraint('dateCreateInvoice',new Assert\Date(array(
                        'message'=>'Дата создания документа {{ value }} не верная'
                    )));

        //Валидация поля numInvoice
                $metadata->addPropertyConstraint('numInvoice',new Assert\NotBlank(array(
                    'message'=>'Номер документа {{ value }} не может быть пустым '
                )));
                $metadata->addConstraint((new Assert\Callback('isValidNumDoc')));
                    //$metadata->addPropertyConstraint('numInvoice', new ContainsNumDoc());
                        //$metadata->addPropertyConstraint('numInvoice', new Assert\Type(array(
                        //    'type'=>'alpha',
                        //    'message'=>'Тип документа не может содержать ни каких символов кроме буквенных'
                        //)));

        //Валидация поля typeInvoiceFull
                $metadata->addPropertyConstraint('typeInvoiceFull',new Assert\NotBlank(array(
                    'message'=>'Тип документа {{ value }} не может быть пустым '
                )));
                    $metadata->addPropertyConstraint('typeInvoiceFull', new Assert\Choice(array(
                        'choices' => array(
                            'ПНЕ',
                            'РКЕ',
                            'МДЕ'
                        ),
                        'message' => 'Указан {{ value }} не верный тип документа',
                    )));
        //Валидация поля typeInvoice
                $metadata->addGetterConstraint('TypeInvoiceLegal', new Assert\IsTrue(array(
                    'message' => 'Указан  не верный тип причины не выдачи документа покупателю',
                )));
        //Валидация поля innClient
                $metadata->addPropertyConstraint('innClient',new Assert\NotBlank(array(
                    'message'=>'ИНН документа не может быть пустым '
                )));
                $metadata->addPropertyConstraint('innClient', new Assert\Length(array(
                    'min'        => 9,
                    'max'        => 12,
                    'minMessage' => 'Длина ИНН не может быть меньше {{ limit }} цифр',
                    'maxMessage' => 'Длина ИНН не может быть более {{ limit }} цифр',
                )));
                $metadata->addPropertyConstraint('innClient', new Assert\Type(array(
                    'type'    => 'digit',
                    'message' => 'ИНН {{ value }} должен содержать только цифры .',
                )));
                $metadata->addPropertyConstraint('zagSumm', new Assert\Regex(array(
                    'pattern' => '/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                    'message' => 'Поле zagSumm {{ value }} содержит данные не того типа.',
                )));
                    $metadata->addPropertyConstraint('baza20',new Assert\Regex(array(
                        'pattern' => '/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                        'message' => 'Поле baza20 {{ value }} содержит данные не того типа.',
                    )));
                        $metadata->addPropertyConstraint('pdv20', new Assert\Regex(array(
                            'pattern' => '/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                            'message' => 'Поле pdv20 {{ value }} содержит данные не того типа.',
                        )));
                            $metadata->addPropertyConstraint('baza7', new Assert\Regex(array(
                                'pattern' => '/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                                'message' => 'Поле baza7 {{ value }} содержит данные не того типа.',
                            )));
                                $metadata->addPropertyConstraint('pdv7',new Assert\Regex(array(
                                    'pattern' => '/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                                    'message' => 'Поле pdv7 {{ value }} содержит данные не того типа.',
                                )));
                                $metadata->addPropertyConstraint('baza0', new Assert\Regex(array(
                                    'pattern' => '/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                                    'message' => 'Поле baza0 {{ value }} содержит данные не того типа.',
                                )));
                            $metadata->addPropertyConstraint('bazaZvil', new Assert\Regex(array(
                                'pattern' => '/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                                'message' => 'Поле bazaZvil {{ value }} содержит данные не того типа.',
                            )));
                        $metadata->addPropertyConstraint('bazaNeObj', new Assert\Regex(array(
                            'pattern' => '/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                            'message' => 'Поле bazaNeObj {{ value }} содержит данные не того типа.',
                        )));
                    $metadata->addPropertyConstraint('bazaZaMezhiTovar',new Assert\Regex(array(
                        'pattern' => '/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                        'message' => 'Поле bazaZaMezhiTovar {{ value }} содержит данные не того типа.',
                    )));
                $metadata->addPropertyConstraint('bazaZaMezhiPoslug',new Assert\Regex(array(
                    'pattern' => '/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                    'message' => 'Поле bazaZaMezhiPoslug {{ value }} содержит данные не того типа.',
                )));

                /**
                $metadata->addPropertyConstraint('zagSumm', new Assert\Type(array(
                    'type'    => 'float',
                    'message' => 'Поле zagSumm {{ value }} содержит данные не того типа.',
                )));
                    $metadata->addPropertyConstraint('baza20',new Assert\Type(array(
                        'type'    => 'float',
                        'message' => 'Поле baza20 {{ value }} содержит данные не того типа.',
                    )));
                        $metadata->addPropertyConstraint('pdv20', new Assert\Type(array(
                            'type'    => 'float',
                            'message' => 'Поле pdv20 {{ value }} содержит данные не того типа.',
                        )));
                            $metadata->addPropertyConstraint('baza7', new Assert\Type(array(
                                'type'    => 'float',
                                'message' => 'Поле baza7 {{ value }} содержит данные не того типа.',
                            )));
                        $metadata->addPropertyConstraint('pdv7',new Assert\Type(array(
                            'type'    => 'float',
                            'message' => 'Поле pdv7 {{ value }} содержит данные не того типа.',
                        )));
                            $metadata->addPropertyConstraint('baza0', new Assert\Type(array(
                                'type'    => 'float',
                                'message' => 'Поле baza0 {{ value }} содержит данные не того типа.',
                            )));
                        $metadata->addPropertyConstraint('bazaZvil', new Assert\Type(array(
                            'type'    => 'float',
                            'message' => 'Поле bazaZvil {{ value }} содержит данные не того типа.',
                        )));
                            $metadata->addPropertyConstraint('bazaNeObj', new Assert\Type(array(
                                'type'    => 'float',
                                'message' => 'Поле bazaNeObj {{ value }} содержит данные не того типа.',
                            )));
                        $metadata->addPropertyConstraint('bazaZaMezhiTovar',new Assert\Type(array(
                            'type'    => 'float',
                            'message' => 'Поле bazaZaMezhiTovar {{ value }} содержит данные не того типа.',
                        )));
                            $metadata->addPropertyConstraint('bazaZaMezhiPoslug',new Assert\Type(array(
                                'type'    => 'float',
                                'message' => 'Поле bazaZaMezhiPoslug {{ value }} содержит данные не того типа.',
                            )));
                */
                // Проверка показателей РКЕ
                $metadata->addGetterConstraint('ValidRKE', new Assert\IsTrue(array(
                    'message' => 'Указан не верные реквизиты документа который корректировал РКЕ',
                )));
                //Валидация поля numInvoice
                    $metadata->addPropertyConstraint('rkeNumInvoice', new ContainsNumDoc());

            $metadata->addPropertyConstraint('rkeDateCreateInvoice',new Assert\Date(array(
                'message'=>'Дата создания РКЕ не верная'
            )));

    }
}

