<?php

namespace App\Entity;

/**
 * Errorloadreestr
 */
class Errorloadreestr
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $keyField;

    /**
     * @var string
     */
    private $typereestr;

    /**
     * @var string
     */
    private $error;


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
     * Set keyField
     *
     * @param string $keyField
     *
     * @return Errorloadreestr
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
     * Set typereestr
     *
     * @param string $typereestr
     *
     * @return Errorloadreestr
     */
    public function setTypereestr($typereestr)
    {
        $this->typereestr = $typereestr;

        return $this;
    }

    /**
     * Get typereestr
     *
     * @return string
     */
    public function getTypereestr()
    {
        return $this->typereestr;
    }

    /**
     * Set error
     *
     * @param string $error
     *
     * @return Errorloadreestr
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Get error
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
    /**
     * @var string
     */
    private $numbranch;


    /**
     * Set numbranch
     *
     * @param string $numbranch
     *
     * @return Errorloadreestr
     */
    public function setNumbranch($numbranch)
    {
        $this->numbranch = $numbranch;

        return $this;
    }

    /**
     * Get numbranch
     *
     * @return string
     */
    public function getNumbranch()
    {
        return $this->numbranch;
    }
}
