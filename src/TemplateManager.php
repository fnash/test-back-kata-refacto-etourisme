<?php

namespace Evaneos;

use Evaneos\Context\ApplicationContext;
use Evaneos\Entity\Quote;
use Evaneos\Entity\Template;
use Evaneos\Entity\User;
use Evaneos\Repository\DestinationRepository;
use Evaneos\Repository\QuoteRepository;
use Evaneos\Repository\SiteRepository;

class TemplateManager
{
    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone($tpl);
        $replaced
            ->setSubject($this->computeText($replaced->getSubject(), $data))
            ->setContent($this->computeText($replaced->getContent(), $data))
        ;

        return $replaced;
    }

    private function computeText($text, array $data)
    {
        $APPLICATION_CONTEXT = ApplicationContext::getInstance();

        /* @var $quote Quote */
        $quote = (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : null;

        if ($quote)
        {
            $_quoteFromRepository = QuoteRepository::getInstance()->getById($quote->getId());
            $usefulObject = SiteRepository::getInstance()->getById($quote->getSiteId());
            $destinationOfQuote = DestinationRepository::getInstance()->getById($quote->getDestinationId());

            if(strpos($text, '[quote:destination_link]') !== false){
                $destination = DestinationRepository::getInstance()->getById($quote->getDestinationId());
            }

            $containsSummaryHtml = strpos($text, '[quote:summary_html]');
            $containsSummary     = strpos($text, '[quote:summary]');

            if ($containsSummaryHtml !== false || $containsSummary !== false) {
                if ($containsSummaryHtml !== false) {
                    $text = str_replace(
                        '[quote:summary_html]',
                        Quote::renderHtml($_quoteFromRepository),
                        $text
                    );
                }
                if ($containsSummary !== false) {
                    $text = str_replace(
                        '[quote:summary]',
                        Quote::renderText($_quoteFromRepository),
                        $text
                    );
                }
            }

            (strpos($text, '[quote:destination_name]') !== false) and $text = str_replace('[quote:destination_name]',$destinationOfQuote->getCountryName(),$text);
        }

        if (isset($destination))
            $text = str_replace('[quote:destination_link]', $usefulObject->getUrl() . '/' . $destination->getCountryName() . '/quote/' . $_quoteFromRepository->getId(), $text);
        else
            $text = str_replace('[quote:destination_link]', '', $text);

        /*
         * USER
         * [user:*]
         */
        $_user  = (isset($data['user'])  and ($data['user']  instanceof User))  ? $data['user']  : $APPLICATION_CONTEXT->getCurrentUser();
        if($_user) {
            (strpos($text, '[user:first_name]') !== false) and $text = str_replace('[user:first_name]'       , ucfirst(mb_strtolower($_user->getFirstname())), $text);
        }

        return $text;
    }
}
