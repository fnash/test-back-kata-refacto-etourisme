<?php

namespace Evaneos\Entity;

class Quote
{
    /**
     * @var
     */
    private $id;

    /**
     * @var
     */
    private $siteId;

    /**
     * @var
     */
    private $destinationId;

    /**
     * @var \Datetime
     */
    private $dateQuoted;

    public function __construct($id, $siteId, $destinationId, $dateQuoted)
    {
        $this->id = $id;
        $this->siteId = $siteId;
        $this->destinationId = $destinationId;
        $this->dateQuoted = $dateQuoted;
    }

    public static function renderHtml(Quote $quote)
    {
        return '<p>'.$quote->id.'</p>';
    }

    public static function renderText(Quote $quote)
    {
        return (string) $quote->id;
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
     * @return mixed
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @param mixed $siteId
     *
     * @return self
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDestinationId()
    {
        return $this->destinationId;
    }

    /**
     * @param mixed $destinationId
     *
     * @return self
     */
    public function setDestinationId($destinationId)
    {
        $this->destinationId = $destinationId;

        return $this;
    }

    /**
     * @return \Datetime
     */
    public function getDateQuoted()
    {
        return $this->dateQuoted;
    }

    /**
     * @param \Datetime $dateQuoted
     *
     * @return self
     */
    public function setDateQuoted($dateQuoted)
    {
        $this->dateQuoted = $dateQuoted;

        return $this;
    }
}
