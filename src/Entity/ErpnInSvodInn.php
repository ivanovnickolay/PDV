<?php

namespace App\Entity;

/**
 * ErpnInSvodInn
 */
class ErpnInSvodInn
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $month;

    /**
     * @var integer
     */
    private $year;

    /**
     * @var string
     */
    private $inn;

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
    private $keyField;


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
     * @return ErpnInSvodInn
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
     * @return ErpnInSvodInn
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
     * Set inn
     *
     * @param string $inn
     *
     * @return ErpnInSvodInn
     */
    public function setInn($inn)
    {
        $this->inn = $inn;

        return $this;
    }

    /**
     * Get inn
     *
     * @return string
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Set sumaInvoice
     *
     * @param float $sumaInvoice
     *
     * @return ErpnInSvodInn
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
     * @return ErpnInSvodInn
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
     * @return ErpnInSvodInn
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
     * Set keyField
     *
     * @param string $keyField
     *
     * @return ErpnInSvodInn
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
}
