<?php

namespace Evaneos\Template\Renderer;

interface RendererInterface
{
    /**
     * @param string $templateString
     * @param mixed  $data
     *
     * @return string
     */
    public function render($templateString, $data);

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supports($class);
}
