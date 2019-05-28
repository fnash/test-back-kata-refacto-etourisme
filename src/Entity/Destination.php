<?php

namespace Evaneos\Entity;

class Destination
{
    /**
     * @var
     */
    private $id;

    /**
     * @var string
     */
    private $countryName;

    /**
     * @var string
     */
    private $conjunction;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $computerName;

    public function __construct($id, $countryName, $conjunction, $computerName)
    {
        $this->id = $id;
        $this->countryName = $countryName;
        $this->conjunction = $conjunction;
        $this->computerName = $computerName;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * @param string $countryName
     *
     * @return self
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;

        return $this;
    }

    /**
     * @return string
     */
    public function getConjunction()
    {
        return $this->conjunction;
    }

    /**
     * @param string $conjunction
     *
     * @return self
     */
    public function setConjunction($conjunction)
    {
        $this->conjunction = $conjunction;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getComputerName()
    {
        return $this->computerName;
    }

    /**
     * @param string $computerName
     *
     * @return self
     */
    public function setComputerName($computerName)
    {
        $this->computerName = $computerName;

        return $this;
    }
}
