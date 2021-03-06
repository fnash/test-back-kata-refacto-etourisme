<?php

require_once dirname(__DIR__ ). '/vendor/autoload.php';

use Faker\Factory;
use Evaneos\Entity\Template;
use Evaneos\TemplateManager;
use Evaneos\Entity\Quote;
use Evaneos\Context\ApplicationContext;

$faker = Factory::create();

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

$currentUser = ApplicationContext::getInstance()->getCurrentUser();

$templateManager = new TemplateManager($currentUser);

$message = $templateManager->getTemplateComputed($template, [
        'quote' => new Quote($faker->randomNumber(), $faker->randomNumber(), $faker->randomNumber(), $faker->date())
]);

echo $message->getSubject() . "\n" . $message->getContent();
