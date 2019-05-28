<?php

namespace Tests\Evaneos;

use Evaneos\Context\ApplicationContext;
use Evaneos\Entity\Quote;
use Evaneos\Entity\Template;
use Evaneos\Repository\DestinationRepository;
use Evaneos\TemplateManager;
use PHPUnit\Framework\TestCase;

final class TemplateManagerTest extends TestCase
{
    /**
     * Init the mocks
     */
    protected function setUp(): void
    {
    }

    /**
     * Closes the mocks
     */
    protected function tearDown(): void
    {
    }

    public function testGetTemplateComputed()
    {
        $faker = \Faker\Factory::create();

        $expectedDestination = DestinationRepository::getInstance()->getById($faker->randomNumber());
        $expectedUser = ApplicationContext::getInstance()->getCurrentUser();

        $quote = new Quote($faker->randomNumber(), $faker->randomNumber(), $faker->randomNumber(), $faker->date());

        $template = new Template(
            1,
            'Votre voyage avec une agence locale [quote:destination_name]',
            "
Bonjour [user:first_name],

Merci d'avoir contacté un agent local pour votre voyage [quote:destination_name].

Bien cordialement,

L'équipe Evaneos.com
www.evaneos.com
");
        $templateManager = new TemplateManager();

        $message = $templateManager->getTemplateComputed(
            $template,
            [
                'quote' => $quote
            ]
        );

        $this->assertEquals('Votre voyage avec une agence locale ' . $expectedDestination->getCountryName(), $message->getSubject());
        $this->assertEquals("
Bonjour " . $expectedUser->getFirstname() . ",

Merci d'avoir contacté un agent local pour votre voyage " . $expectedDestination->getCountryName() . ".

Bien cordialement,

L'équipe Evaneos.com
www.evaneos.com
", $message->getContent());
    }
}
