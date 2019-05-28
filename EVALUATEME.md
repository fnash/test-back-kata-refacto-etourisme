# Refactoring Kata Execution

## En bref
Le test est très intéressant même si le code pique les yeux. 
J'ai eu beaucoup de plaisir à le faire même si j'ai pris du temps à basculer entre mon travail et le test.
Merci et chapeau au dev qui l'a proposé.


## Mon environnement
J'ai php 7.2 su mon poste aujourd'hui. 
php -v ===> php 7.2

Il m'a fallu installer php-dom et php-mbstring pour faire fonctionner l'exemple et les tests unitaires.
php example/example.php ===> KO
apt install php-dom php-mbstring
php example/example.php ===> OK
vendor/bin/phpunit ===> OK (1 test, 2 assertions)


## Déroulement de ce que j'ai fait

J'ai commencé par explorer le projet. J'ai réorganisé les namespaces, et rajouté des getters/setters aux entités.

Ensuite je me suis focalisé sur le TemplateManager pour comprendre ce qu'il fait aujourd'hui, et sur le README pour comprendre le besoin afin d'apporter une solution.

Cette classe passe essentiellement des données des entités dans les templates à transformer.

J'ai donc proposé, afin d'anticiper les changements futurs, des renderers spécialisés dans la transformation des données dans les templates, et selon le format.
J'ai commencé par la classe Quote comme 1er exemple d'implémentation.

L'idée principale est d'avoir une chaine d'objets renderers qui passent sur le template pour faire les transformations adéquates.
Le TemplateManager instancie par défaut tous les renderers disponibles.


# Système proposé au next developer
Donc concrètement, si demain on veut ajouter un nouveau placeholder à un template:
Il faut avoir (créer/mettre à jour) le renderer correspondant à l'entité.
Il doit obligatoirement implémenter l'interface RendererInterface, et appartenir au namespace Evaneos\Template\Renderer.
Et voilà!


# Remarques
J'ai essayé de mettre en place le critère format du template (html ou text).
Le TemplateManager déroule aujourd'hui tous les renderers avec succès peu importe le format, parce que les placeholders sont différents.
Je pense qu'on peut mieux faire et adresser un template par format. Donc à voir déjà au niveau des templates. Ca sera plus efficace et plus contrôlé si l'execution reste focalisée sur un seul format donné.


# TODO
La version HEAD execute bien le code de l'exemple.

Par contre, le test unitaire casse. Le UserHtmlRenderer n'arrive pas à fournir son rendu, tout simplement à cause de la méthode supports qui renvoie false avec le ProphecyObject.
Pour continuer, il faudrait injecter un tableau de mocks de renderers, avec la méthode supports qui renvoie true.

# Conclusion
Le test m'a pris vraiment beaucoup plus qu'une heure.
Suis-je inefficace ou j'ai fait un excès de zel?
Ce qui est sûr c'est que je sais proposer une conception, et que le code est mieux qu'il ne l'était.
