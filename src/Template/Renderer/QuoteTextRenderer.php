<?php

namespace Evaneos\Template\Renderer;

use Evaneos\Entity\Quote;

class QuoteTextRenderer implements RendererInterface
{
    /**
     * @param string $templateString
     * @param Quote  $quote
     *
     * @return string
     */
    public function render($templateString, $quote)
    {
        return str_replace('[quote:summary]', $quote->getId(), $templateString);
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
