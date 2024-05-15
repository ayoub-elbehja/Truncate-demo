<?php

declare(strict_types=1);

namespace App\Service;

use DOMDocument;

class Truncate
{
    // Déclaration des propriétés de la classe
    public $maxLength = 100; // Longueur maximale par défaut
    public $textLength = 0; // Longueur du texte en cours
    public $readMore = false; // Indicateur si le texte a été tronqué
    public $newText = ""; // Texte tronqué

    // Constructeur de la classe
    public function __construct()
    {
        // Pas de logique particulière dans le constructeur
    }

    // Fonction récursive pour parcourir et tronquer les noeuds DOM
    public function shownode($x)
    {
        // Parcours de tous les noeuds enfants du noeud passé en paramètre
        foreach ($x->childNodes as $p) {
            // Vérification si le texte a déjà été tronqué, si non, poursuite du traitement
            if (!$this->readMore) {
                $style = ''; // Initialisation du style HTML
                $text = ''; // Initialisation du texte à ajouter

                // Si le noeud est un texte
                if ($p->nodeName == "#text") {
                    $text = $p->nodeValue; // Récupération du texte
                } elseif ($p->getAttribute('style')) {
                    $style = ' style="' . $p->getAttribute('style') . '"'; // Récupération du style HTML
                }

                // Ajout de la balise ouvrante si le noeud n'est pas un texte
                $this->newText .= $p->nodeName != '#text' ? '<' . $p->nodeName . $style . '>' : '';

                // Si le noeud a des enfants, appel récursif de la fonction shownode()
                if ($this->hasChild($p)) {
                    $this->shownode($p);
                } elseif ($p->nodeType == XML_ELEMENT_NODE) {
                    $text = $p->nodeValue; // Récupération du texte du noeud
                }

                // Vérification si le texte doit être tronqué
                if (!$this->readMore && ($this->textLength + strlen($text)) > $this->maxLength) {
                    $text = substr($text, 0, ($this->maxLength - $this->textLength)) . "..."; // Troncature du texte
                    $this->readMore = true; // Marquage indiquant que le texte a été tronqué
                }
                $this->textLength += strlen($text); // Mise à jour de la longueur totale du texte

                // Ajout du texte au résultat final
                $this->newText .= $text;

                // Ajout de la balise fermante si le noeud n'est pas un texte
                $this->newText .= $p->nodeName != '#text' ? '</' . $p->nodeName . '>' : '';
            }
        }
    }

    // Fonction pour vérifier si un noeud a des enfants de type élément
    public function hasChild($p)
    {
        if ($p->hasChildNodes()) {
            foreach ($p->childNodes as $c) {
                if ($c->nodeType == XML_ELEMENT_NODE) {
                    return true; // Le noeud a des enfants de type élément
                }
            }
        }
        return false; // Le noeud n'a pas d'enfants de type élément
    }

    // Fonction principale pour obtenir le texte tronqué
    public function getShortText($text, $maxLength): string
    {
        $this->maxLength = $maxLength; // Mise à jour de la longueur maximale
        $this->newText = ''; // Réinitialisation du texte tronqué
        $x = new DOMDocument(); // Création d'un objet DOMDocument
        libxml_use_internal_errors(true); // Activation de la gestion des erreurs libxml
        $x->loadHTML('<?xml encoding="utf-8" ?><div>' . $text . '</div>'); // Chargement du texte HTML dans le DOMDocument
        $this->shownode($x->getElementsByTagName('div')->item(0)); // Appel de la fonction récursive pour traiter les noeuds
        //$x->saveHTML();
        return $this->readMore ? $this->newText : ''; // Retourne le texte tronqué s'il a été tronqué, sinon une chaîne vide
    }
}
