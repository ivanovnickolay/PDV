<?php

namespace App\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ErpnIn
 */
class ErpnIn
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
     * @return ErpnIn
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
     *
     * @return ErpnIn
     */
    public function setDateCreateInvoice($dateCreateInvoice)
    {
        $this->dateCreateInvoice = $dateCreateInvoice;

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
     *
     * @return ErpnIn
     */
    public function setDateRegInvoice($dateRegInvoice)
    {
        $this->dateRegInvoice = $dateRegInvoice;

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
     * @return ErpnIn
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
     * @return ErpnIn
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
     * @return ErpnIn
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
     * @return ErpnIn
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
     * @return ErpnIn
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
     * @return ErpnIn
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
     * @return ErpnIn
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
     * @return ErpnIn
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
     * @return ErpnIn
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
     * @return ErpnIn
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
     * @return ErpnIn
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
     * @return ErpnIn
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
     * @return ErpnIn
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
     *
     * @return ErpnIn
     */
    public function setDateContract($dateContract)
    {
        $this->dateContract = $dateContract;

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
     * @return ErpnIn
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
     * @return ErpnIn
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
     * @return ErpnIn
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
     * @return ErpnIn
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
