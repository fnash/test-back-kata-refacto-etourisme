<?php

namespace Evaneos\Repository;

use Evaneos\Entity\Quote;
use Evaneos\Entity\Site;
use Evaneos\Helper\SingletonTrait;
use \Faker as Faker;

class SiteRepository implements Repository
{
    use SingletonTrait;

    private $url;

    /**
     * SiteRepository constructor.
     *
     */
    public function __construct()
    {
        // DO NOT MODIFY THIS METHOD
        $this->url = Faker\Factory::create()->url;
    }

    /**
     * @param int $id
     *
     * @return Site
     */
    public function getById($id)
    {
        // DO NOT MODIFY THIS METHOD
        return new Site($id, $this->url);
    }


    /**
     * @param Quote $quote
     *
     * @return Site
     */
    public function getByQuote(Quote $quote)
    {
        return $this->getById($quote->getSiteId());
    }
}
