<?php

namespace Tests\Evaneos;

use Evaneos\Entity\Quote;
use Evaneos\Entity\Template;
use Evaneos\Entity\User;
use Evaneos\Repository\DestinationRepository;
use Evaneos\TemplateManager;
use PHPUnit\Framework\TestCase;

final class TemplateManagerTest extends TestCase
{
    private static $content = <<<CONTENT
    
Bonjour {{firstName}},

Merci d'avoir contacté un agent local pour votre voyage {{country}}.

Bien cordialement,

L'équipe Evaneos.com
www.evaneos.com

CONTENT;


    private static $subject = 'Votre voyage avec une agence locale {{country}}';

    /**
     * @var User
     */
    private $currentUser;

    /**
     * Init the mocks
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->currentUser = $this->prophesize(User::class);
    }

    public function testGetTemplateComputed()
    {
        // init
        $templateManager = $this->init();

        $firstName = 'Fabien';
        $this->currentUser->getFirstname()->willReturn($firstName);

        $quote = new Quote(1, 2, 3, new \DateTime('2019-05-28'));
        $countryName = DestinationRepository::getInstance()->getByQuote($quote)->getCountryName();

        $template = new Template(1, $this->getSubjectTemplate(), $this->getContentTemplate());


        // exercice
        $message = $templateManager->getTemplateComputed($template, [
                'quote' => $quote
        ]);


        // Assert
        $this->assertEquals($this->getSubjectValue($countryName), $message->getSubject());
        $this->assertEquals($this->getContentValue($countryName, $firstName), $message->getContent());
    }

    private function init()
    {
        // TODO inject mocks of renderers with supports(Any)->shouldReturn(true)

        return new TemplateManager($this->currentUser->reveal());
    }

    private function getSubjectTemplate()
    {
        return str_replace('{{country}}', '[quote:destination_name]', static::$subject);
    }

    private function getSubjectValue($country)
    {
        return str_replace('{{country}}', $country, static::$subject);
    }

    private function getContentTemplate()
    {
        return str_replace(['{{country}}', '{{firstName}}'], ['[quote:destination_name]', '[user:first_name]'], static::$content);
    }

    private function getContentValue($country, $firstName)
    {
        return str_replace(['{{country}}', '{{firstName}}'], [$country, $firstName], static::$content);
    }
}
