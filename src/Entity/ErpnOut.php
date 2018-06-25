<?php

namespace App\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ErpnOut
 */
class ErpnOut
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $numInvoice;

    /**
     * @var \DateTime
     */
    private $dateCreateInvoice;

    /**
     * @var \DateTime
     */
    private $dateRegInvoice;

    /**
     * @var string
     */
    private $typeInvoiceFull;

    /**
     * @var string
     */
    private $edrpouClient;

    /**
     * @var string
     */
    private $innClient;

    /**
     * @var string
     */
    private $numBranchClient;

    /**
     * @var string
     */
    private $nameClient;

    /**
     * @var float
     */
    private $sumaInvoice;

    /**
     * @var float
     */
    private $pdvinvoice;

    /**
     * @var float
     */
    private $bazaInvoice;

    /**
     * @var string
     */
    private $nameVendor;

    /**
     * @var string
     */
    private $numBranchVendor;

    /**
     * @var string
     */
    private $numRegInvoice;

    /**
     * @var string
     */
    private $typeInvoice;

    /**
     * @var string
     */
    private $numContract;

    /**
     * @var \DateTime
     */
    private $dateContract;

    /**
     * @var string
     */
    private $typeContract;

    /**
     * @var string
     */
    private $personCreateInvoice;

    /**
     * @var string
     */
    private $keyField;

    /**
     * @var string
     */
    private $rkeInfo;

	/**
	 * @var integer
	 */
	private $monthCreateInvoice;

	/**
	 * @var integer
	 */
	private $yearCreateInvoice;

	private $numMainBranch;

	/**
	 * @return mixed
	 */
	public function getNumMainBranch()
	{
		return $this->numMainBranch;
	}

	/**
	 * @param mixed $numMainBranch
	 */
	public function setNumMainBranch($numMainBranch)
	{
		$this->numMainBranch = $numMainBranch;
	}
	/**
	 * @return int
	 */
	public function getYearCreateInvoice(): int
	{
		return $this->yearCreateInvoice;
	}

	/**
	 * @param int $yearCreateInvoice
	 */
	public function setYearCreateInvoice(int $yearCreateInvoice)
	{
		$this->yearCreateInvoice = $yearCreateInvoice;
	}

	/**
	 * @return mixed
	 */
	public function getMonthCreateInvoice()
	{
		return $this->monthCreateInvoice;
	}

	/**
	 * @param mixed $monthCreateInvoice
	 */
	public function setMonthCreateInvoice($monthCreateInvoice)
	{
		$this->monthCreateInvoice = $monthCreateInvoice;
	}


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
     * Set numInvoice
     *
     * @param string $numInvoice
     *
     * @return ErpnOut
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
     * Set dateCreateInvoice
     *
     * @param \DateTime $dateCreateInvoice
     * @link http://php.net/manual/ru/datetime.createfromformat.php
     * @link  http://php.net/manual/ru/function.date-parse.php
     *
     * @return ErpnOut
     */
    public function setDateCreateInvoice($dateCreateInvoice)
    {
        $this->dateCreateInvoice =date_create_from_format('d.m.Y',$dateCreateInvoice);
	    $parse=date_parse(date_format($this->dateCreateInvoice, 'd.m.Y'));
        $this->setMonthCreateInvoice($parse["month"]);
	    $this->setYearCreateInvoice($parse["year"]);

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
     * Set dateRegInvoice
     *
     * @param \DateTime $dateRegInvoice
     * @link http://php.net/manual/ru/datetime.createfromformat.php
     *
     * @return ErpnOut
     */
    public function setDateRegInvoice($dateRegInvoice)
    {
        $this->dateRegInvoice = date_create_from_format('d.m.Y',$dateRegInvoice);

        return $this;
    }

    /**
     * Get dateRegInvoice
     *
     * @return \DateTime
     */
    public function getDateRegInvoice()
    {
        return $this->dateRegInvoice;
    }

    /**
     * Set typeInvoiceFull
     *
     * @param string $typeInvoiceFull
     *
     * @return ErpnOut
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
     * Set edrpouClient
     *
     * @param string $edrpouClient
     *
     * @return ErpnOut
     */
    public function setEdrpouClient($edrpouClient)
    {
        $this->edrpouClient = $edrpouClient;

        return $this;
    }

    /**
     * Get edrpouClient
     *
     * @return string
     */
    public function getEdrpouClient()
    {
        return $this->edrpouClient;
    }

    /**
     * Set innClient
     *
     * @param string $innClient
     *
     * @return ErpnOut
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
     * Set numBranchClient
     *
     * @param string $numBranchClient
     *
     * @return ErpnOut
     */
    public function setNumBranchClient($numBranchClient)
    {
        $this->numBranchClient = $numBranchClient;

        return $this;
    }

    /**
     * Get numBranchClient
     *
     * @return string
     */
    public function getNumBranchClient()
    {
        return $this->numBranchClient;
    }

    /**
     * Set nameClient
     *
     * @param string $nameClient
     *
     * @return ErpnOut
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
     * Set sumaInvoice
     *
     * @param float $sumaInvoice
     *
     * @return ErpnOut
     */
    public function setSumaInvoice($sumaInvoice)
    {
        $this->sumaInvoice = $sumaInvoice;

        return $this;
    }

    /**
     * Get sumaInvoice
     *
     * @return float
     */
    public function getSumaInvoice()
    {
        return $this->sumaInvoice;
    }

    /**
     * Set pdvinvoice
     *
     * @param float $pdvinvoice
     *
     * @return ErpnOut
     */
    public function setPdvinvoice($pdvinvoice)
    {
        $this->pdvinvoice = $pdvinvoice;

        return $this;
    }

    /**
     * Get pdvinvoice
     *
     * @return float
     */
    public function getPdvinvoice()
    {
        return $this->pdvinvoice;
    }

    /**
     * Set bazaInvoice
     *
     * @param float $bazaInvoice
     *
     * @return ErpnOut
     */
    public function setBazaInvoice($bazaInvoice)
    {
        $this->bazaInvoice = $bazaInvoice;

        return $this;
    }

    /**
     * Get bazaInvoice
     *
     * @return float
     */
    public function getBazaInvoice()
    {
        return $this->bazaInvoice;
    }

    /**
     * Set nameVendor
     *
     * @param string $nameVendor
     *
     * @return ErpnOut
     */
    public function setNameVendor($nameVendor)
    {
        $this->nameVendor = $nameVendor;

        return $this;
    }

    /**
     * Get nameVendor
     *
     * @return string
     */
    public function getNameVendor()
    {
        return $this->nameVendor;
    }

    /**
     * Set numBranchVendor
     *
     * @param string $numBranchVendor
     *
     * @return ErpnOut
     */
    public function setNumBranchVendor($numBranchVendor)
    {
        $this->numBranchVendor = $numBranchVendor;

        return $this;
    }

    /**
     * Get numBranchVendor
     *
     * @return string
     */
    public function getNumBranchVendor()
    {
        return $this->numBranchVendor;
    }

    /**
     * Set numRegInvoice
     *
     * @param string $numRegInvoice
     *
     * @return ErpnOut
     */
    public function setNumRegInvoice($numRegInvoice)
    {
        $this->numRegInvoice = $numRegInvoice;

        return $this;
    }

    /**
     * Get numRegInvoice
     *
     * @return string
     */
    public function getNumRegInvoice()
    {
        return $this->numRegInvoice;
    }

    /**
     * Set typeInvoice
     *
     * @param string $typeInvoice
     *
     * @return ErpnOut
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
     * Set numContract
     *
     * @param string $numContract
     *
     * @return ErpnOut
     */
    public function setNumContract($numContract)
    {
        $this->numContract = $numContract;

        return $this;
    }

    /**
     * Get numContract
     *
     * @return string
     */
    public function getNumContract()
    {
        return $this->numContract;
    }

    /**
     * Set dateContract
     *
     * @param \DateTime $dateContract
     * @link http://php.net/manual/ru/datetime.createfromformat.php
     *
     * @return ErpnOut
     */
    public function setDateContract($dateContract)
    {
        $this->dateContract = date_create_from_format('d.m.Y',$dateContract);

        return $this;
    }

    /**
     * Get dateContract
     *
     * @return \DateTime
     */
    public function getDateContract()
    {
        return $this->dateContract;
    }

    /**
     * Set typeContract
     *
     * @param string $typeContract
     *
     * @return ErpnOut
     */
    public function setTypeContract($typeContract)
    {
        $this->typeContract = $typeContract;

        return $this;
    }

    /**
     * Get typeContract
     *
     * @return string
     */
    public function getTypeContract()
    {
        return $this->typeContract;
    }

    /**
     * Set personCreateInvoice
     *
     * @param string $personCreateInvoice
     *
     * @return ErpnOut
     */
    public function setPersonCreateInvoice($personCreateInvoice)
    {
        $this->personCreateInvoice = $personCreateInvoice;

        return $this;
    }

    /**
     * Get personCreateInvoice
     *
     * @return string
     */
    public function getPersonCreateInvoice()
    {
        return $this->personCreateInvoice;
    }

    /**
     * Set keyField
     *
     * @param string $keyField
     *
     * @return ErpnOut
     */
    public function setKeyField($keyField)
    {
        $this->keyField = $keyField;

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
     * Set rkeInfo
     *
     * @param string $rkeInfo
     *
     * @return ErpnOut
     */
    public function setRkeInfo($rkeInfo)
    {
        $this->rkeInfo = $rkeInfo;

        return $this;
    }

    /**
     * Get rkeInfo
     *
     * @return string
     */
    public function getRkeInfo()
    {
        return $this->rkeInfo;
    }

	/**
	 * валидация данных на уникальность поля keyField
	 * @param ClassMetadata $metadata
	 */
	public static function loadValidatorMetadata(ClassMetadata $metadata)
	{
		$metadata->addConstraint(new UniqueEntity(array(
			'fields'  => 'keyField',
		)));

	}

}
