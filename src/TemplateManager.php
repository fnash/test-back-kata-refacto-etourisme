<?php

namespace Evaneos;

use Evaneos\Entity\Destination;
use Evaneos\Entity\Quote;
use Evaneos\Entity\Template;
use Evaneos\Entity\User;
use Evaneos\Repository\DestinationRepository;
use Evaneos\Repository\QuoteRepository;
use Evaneos\Repository\SiteRepository;
use Evaneos\Template\Renderer\RendererFactory;
use Evaneos\Template\Renderer\RendererInterface;

class TemplateManager
{
    const TARGET_ENTITIES = [
        Quote::class,
//        Destination::class,
//        User::class
    ];

    /**
     * @var User
     */
    private $currentUser;

    /**
     * @var array
     */
    private $renderers = [];

    public function __construct(User $currentUser, array $targetEntities)
    {
        $this->currentUser = $currentUser;
        $this->targetEntities = $targetEntities;

        $this->initRenderers();
    }

    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone($tpl);

        /* @var $renderer RendererInterface */
        foreach ($this->renderers as $renderer) {
            foreach ($data as $key => $entity) {
                if ($renderer->supports(get_class($entity))) {
                    $replaced
                        ->setSubject($renderer->render($replaced->getSubject(), $entity))
                        ->setContent($renderer->render($replaced->getContent(), $entity))
                    ;
                }
            }
        }

        return $replaced;
    }

    private function computeText($text, array $data)
    {
        /* @var $quote Quote */
        $quote = (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : null;

        if ($quote)
        {
            $_quoteFromRepository = QuoteRepository::getInstance()->getById($quote->getId());
            $site = SiteRepository::getInstance()->getById($quote->getSiteId());
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

        /* @var $destination Destination */
        if (isset($destination))
            $text = str_replace('[quote:destination_link]', $site->getUrl() . '/' . $destination->getCountryName() . '/quote/' . $_quoteFromRepository->getId(), $text);
        else
            $text = str_replace('[quote:destination_link]', '', $text);

        /*
         * USER
         * [user:*]
         */

        /* @var $user User */
        $user  = (isset($data['user'])  and ($data['user']  instanceof User))  ? $data['user']  : $this->currentUser;
        if($user) {
            (strpos($text, '[user:first_name]') !== false) and $text = str_replace('[user:first_name]'       , ucfirst(mb_strtolower($user->getFirstname())), $text);
        }

        return $text;
    }

    private function initRenderers()
    {
        /* @var $renderer RendererInterface */
        foreach ($this->targetEntities as $entityFqcn) {
            foreach (RendererFactory::FORMATS as $format) {
                $this->renderers[] = RendererFactory::get($entityFqcn, $format);
            }
        }
    }
}
