<?php

namespace App\Entity;

/**
 * LoadFile
 */
class LoadFile
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $originalName;

    /**
     * @var string
     */
    private $uploadName;

    /**
     * @var string
     */
    private $typeFile;

    /**
     * @var string
     */
    private $descriptionFile;


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
     * Set originalName
     *
     * @param string $originalName
     *
     * @return LoadFile
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * Get originalName
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * Set uploadName
     *
     * @param string $uploadName
     *
     * @return LoadFile
     */
    public function setUploadName($uploadName)
    {
        $this->uploadName = $uploadName;

        return $this;
    }

    /**
     * Get uploadName
     *
     * @return string
     */
    public function getUploadName()
    {
        return $this->uploadName;
    }

    /**
     * Set typeFile
     *
     * @param string $typeFile
     *
     * @return LoadFile
     */
    public function setTypeFile($typeFile)
    {
        $this->typeFile = $typeFile;

        return $this;
    }

    /**
     * Get typeFile
     *
     * @return string
     */
    public function getTypeFile()
    {
        return $this->typeFile;
    }

    /**
     * Set descriptionFile
     *
     * @param string $descriptionFile
     *
     * @return LoadFile
     */
    public function setDescriptionFile($descriptionFile)
    {
        $this->descriptionFile = $descriptionFile;

        return $this;
    }

    /**
     * Get descriptionFile
     *
     * @return string
     */
    public function getDescriptionFile()
    {
        return $this->descriptionFile;
    }
    /**
     * @var string
     */
    private $typeDoc;


    /**
     * Set typeDoc
     *
     * @param string $typeDoc
     *
     * @return LoadFile
     */
    public function setTypeDoc($typeDoc)
    {
        $this->typeDoc = $typeDoc;

        return $this;
    }

    /**
     * Get typeDoc
     *
     * @return string
     */
    public function getTypeDoc()
    {
        return $this->typeDoc;
    }
    /**
     * @var \DateTime
     */
    private $uploadDate;

    /**
     * @var \DateTime
     */
    private $processingDate;


    /**
     * Set uploadDate
     *
     * @param \DateTime $uploadDate
     *
     * @return LoadFile
     */
    public function setUploadDate($uploadDate)
    {
        $this->uploadDate = $uploadDate;

        return $this;
    }

    /**
     * Get uploadDate
     *
     * @return \DateTime
     */
    public function getUploadDate()
    {
        return $this->uploadDate;
    }

    /**
     * Set processingDate
     *
     * @param \DateTime $processingDate
     *
     * @return LoadFile
     */
    public function setProcessingDate($processingDate)
    {
        $this->processingDate = $processingDate;

        return $this;
    }

    /**
     * Get processingDate
     *
     * @return \DateTime
     */
    public function getProcessingDate()
    {
        return $this->processingDate;
    }
}
