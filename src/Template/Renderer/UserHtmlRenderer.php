<?php

namespace Evaneos\Template\Renderer;

use Evaneos\Entity\User;

class UserHtmlRenderer implements RendererInterface
{
    /**
     * @param string $templateString
     * @param User   $user
     *
     * @return string
     */
    public function render($templateString, $user)
    {
        return str_replace('[user:first_name]', ucfirst(mb_strtolower($user->getFirstname())), $templateString);
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supports($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        return User::class === $class;
    }
}
