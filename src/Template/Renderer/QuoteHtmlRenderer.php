<?php

namespace Evaneos\Template\Renderer;

use Evaneos\Entity\Quote;
use Evaneos\Repository\DestinationRepository;
use Evaneos\Repository\SiteRepository;

class QuoteHtmlRenderer implements RendererInterface
{
    /**
     * @param string $templateString
     * @param Quote  $quote
     *
     * @return string
     */
    public function render($templateString, $quote)
    {
        $quoteDestination = DestinationRepository::getInstance()->getByQuote($quote);
        $quoteSite = SiteRepository::getInstance()->getByQuote($quote);

        $templateString = str_replace(
            '[quote:summary_html]',
            '<p>'.$quote->getId().'</p>',
            $templateString
        );

        $templateString = str_replace('[quote:destination_name]', $quoteDestination->getCountryName(), $templateString);

        $replaceDestination = '';
        if ($quoteDestination) {
            $replaceDestination = $quoteSite->getUrl().'/'.$quoteDestination->getCountryName().'/quote/'.$quote->getId();
        }

        $templateString = str_replace('[quote:destination_link]', $replaceDestination, $templateString);

        return $templateString;
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supports($class)
    {
        return Quote::class === $class;
    }
}
