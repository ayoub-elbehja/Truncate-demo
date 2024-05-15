<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Truncate;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TruncateController extends AbstractController
{
    /**
     * @Route("/truncate", name="truncate")
     */
    public function truncate(Request $request, Truncate $truncate): Response
    {
        // Exemple de texte à tronquer avec des balises HTML
        $text = "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p><p>Phasellus vehicula urna eget magna pharetra, vel tempor ex vulputate.</p><p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae;</p><p>Nam vel lacus non augue venenatis iaculis.</p>";

        // Longueur maximale du texte tronqué
        $maxLength = 100;

        // Utilisation de la fonction getShortText pour tronquer le texte
        $shortText = $truncate->getShortText($text, $maxLength);

        // Retourne le texte tronqué en tant que réponse HTTP
        return $this->render('truncate/index.html.twig', [
            'shortText' => $shortText,
        ]);
    }
}
