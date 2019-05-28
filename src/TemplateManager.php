<?php

namespace Evaneos;

use Evaneos\Entity\Quote;
use Evaneos\Entity\Template;
use Evaneos\Entity\User;
use Evaneos\Template\Renderer\RendererFactory;
use Evaneos\Template\Renderer\RendererInterface;
use Evaneos\Template\Renderer\RendererNotFoundexception;

class TemplateManager
{
    const TARGET_ENTITIES = [
        Quote::class,
        User::class
    ];

    /**
     * @var User
     */
    private $currentUser;

    /**
     * @var array
     */
    private $renderers = [];

    public function __construct(User $currentUser, array $renderers = [])
    {
        $this->currentUser = $currentUser;

        if (count($renderers)) {
            $this->renderers = $renderers;
        } else {
            $this->initRenderers();
        }
    }

    public function getTemplateComputed(Template $template, array $data)
    {
        if (!array_key_exists('user', $data)) {
            $data['user'] = $this->currentUser;
        }

        /* @var $renderer RendererInterface */
        foreach ($this->renderers as $renderer) {
            foreach ($data as $key => $entity) {
                if ($renderer->supports($entity)) {
                    $template
                        ->setSubject($renderer->render($template->getSubject(), $entity))
                        ->setContent($renderer->render($template->getContent(), $entity))
                    ;
                }
            }
        }

        return $template;
    }

    /**
     * @param array $renderers
     *
     * @return self
     */
    public function setRenderers(array $renderers)
    {
        $this->renderers = $renderers;

        return $this;
    }

    private function initRenderers()
    {
        /* @var $renderer RendererInterface */
        foreach (static::TARGET_ENTITIES as $entityFqcn) {
            foreach (RendererFactory::FORMATS as $format) {
                try {
                    $this->renderers[] = RendererFactory::get($entityFqcn, $format);
                } catch (RendererNotFoundException $exception) {
                    // TODO log this
                }
            }
        }
    }
}
