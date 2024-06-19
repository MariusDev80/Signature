<?php 

namespace Signature\Controller;

use Signature\Service\Container;

abstract class AbstractController 
{
    public final function __construct(
        protected Container $container,
    ) { }

    protected function render(string $template, array $args = []): void
    {
        $this->container->getViewRenderer()->render($template, $args);
    }

    protected function redirect(string $path, int $code = 301)
    { 
        header(
            sprintf(
                "Location: %s://%s/%s",
                $_SERVER['REQUEST_SCHEME'],
                $_SERVER['HTTP_HOST'],
                ltrim($path, '/')
            ),
            true,
            $code
        );
        exit();
    }

    protected function getPublicDir() : string
    {
        return dirname(getcwd()).'/public/';
    }

    protected function validateField(
        string $name,
        string $regex,
        bool $required = true,
        string $libelle = '',
    ) : ?string 
    {   
        $libelle = $libelle ? $libelle : $name;

        $defaultMessages = [
            'login' => 'Un trigramme est composé de 3 lettres (ex: tri)',
            'password' => 'Minimum 8 caractères, identiques pour les deux champs',
            'numPro' => '10 chiffres de suite (ex: 0123456789)',
        ];

        if (!isset($_POST[$name])) {
            return "Le formulaire n'est pas valide";
        }

        if ($required && !$_POST[$name]) {
            return "Le champ $libelle n'est pas rempli";
        }

        if (!$_POST[$name]) {
            return null;
        }

        $value = $_POST[$name];

        if (!preg_match($regex,$value)){
            return $defaultMessages[$name] ?? "Saisie du champ $libelle invalide";

        } 

        return null;
    }

    protected function addMessage(string $level, string $message): void
    {
        $_SESSION['messages'][] = [
            'level' => $level,
            'message' => $message,
        ];
    }
}
