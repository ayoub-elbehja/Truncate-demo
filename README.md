# Truncate-demo

Cette application Symfony en PHP permet de tronquer un texte HTML tout en conservant la structure des balises HTML.

## Installation

1. Clonez le dépôt : `git clone https://github.com/votre-utilisateur/votre-projet.git`
2. Installez les dépendances : `composer install`

## Utilisation

Utilisez la classe `Truncate` pour tronquer un texte HTML avec la méthode `getShortText`.

Exemple :

```php
use App\Service\Truncate;

// Créez une instance de Truncate
$truncate = new Truncate();

// Texte à tronquer
$texte = "<p>Lorem ipsum dolor sit amet...</p>";

// Longueur maximale du texte tronqué
$maxLength = 100;

// Obtenez le texte tronqué
$texteTronque = $truncate->getShortText($texte, $maxLength);
