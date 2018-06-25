<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;


class SprBranch
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $numBranch;

    /**
     * @var string
     */
    private $nameBranch;

    /**
     * @var string
     */
    private $branchAdr;

    /**
     * @var string
     */
    private $nameMainBranch;

    /**
     * @var string
     */
    private $numMainBranch;


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
     * Set numBranch
     *
     * @param string $numBranch
     *
     * @return SprBranch
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
     * Set nameBranch
     *
     * @param string $nameBranch
     *
     * @return SprBranch
     */
    public function setNameBranch($nameBranch)
    {
        $this->nameBranch = $nameBranch;

        return $this;
    }

    /**
     * Get nameBranch
     *
     * @return string
     */
    public function getNameBranch()
    {
        return $this->nameBranch;
    }

    /**
     * Set branchAdr
     *
     * @param string $branchAdr
     *
     * @return SprBranch
     */
    public function setBranchAdr($branchAdr)
    {
        $this->branchAdr = $branchAdr;

        return $this;
    }

    /**
     * Get branchAdr
     *
     * @return string
     */
    public function getBranchAdr()
    {
        return $this->branchAdr;
    }

    /**
     * Set nameMainBranch
     *
     * @param string $nameMainBranch
     *
     * @return SprBranch
     */
    public function setNameMainBranch($nameMainBranch)
    {
        $this->nameMainBranch = $nameMainBranch;

        return $this;
    }

    /**
     * Get nameMainBranch
     *
     * @return string
     */
    public function getNameMainBranch()
    {
        return $this->nameMainBranch;
    }

    /**
     * Set numMainBranch
     *
     * @param string $numMainBranch
     *
     * @return SprBranch
     */
    public function setNumMainBranch($numMainBranch)
    {
        $this->numMainBranch = $numMainBranch;

        return $this;
    }

    /**
     * Get numMainBranch
     *
     * @return string
     */
    public function getNumMainBranch()
    {
        return $this->numMainBranch;
    }
}
