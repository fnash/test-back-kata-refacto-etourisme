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
    /**
     * @var User
     */
    private $currentUser;

    /**
     * Init the mocks
     */
    protected function setUp(): void
    {
        $this->currentUser = $this->prophesize(User::class);
    }

    /**
     * Closes the mocks
     */
    protected function tearDown(): void
    {
    }

    public function testGetTemplateComputed()
    {
        $firstName = 'Fabien';

        $this->currentUser->getFirstname()->willReturn($firstName);

        $faker = \Faker\Factory::create();

        $expectedDestination = DestinationRepository::getInstance()->getById($faker->randomNumber());

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
        $templateManager = new TemplateManager($this->currentUser->reveal(), TemplateManager::TARGET_ENTITIES);

        $message = $templateManager->getTemplateComputed(
            $template,
            [
                'quote' => $quote
            ]
        );

        $this->assertEquals('Votre voyage avec une agence locale ' . $expectedDestination->getCountryName(), $message->getSubject());
        $this->assertEquals("
Bonjour " . $firstName . ",

Merci d'avoir contacté un agent local pour votre voyage " . $expectedDestination->getCountryName() . ".

Bien cordialement,

L'équipe Evaneos.com
www.evaneos.com
", $message->getContent());
    }
}
