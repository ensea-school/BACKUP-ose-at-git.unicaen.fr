<?php

use Unicaen\OpenDocument\Calc;

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

class Adresse implements \UnicaenVue\Axios\AxiosExtractorInterface {
    protected int $numero = 1;
    protected string $rue = 'Allée des mésanges';
    protected int $cp = 14000;
    protected string $ville = 'Caen';

    public function getNumero(): int
    {
        return $this->numero;
    }

    public function getRue(): string
    {
        return $this->rue;
    }

    public function getCp(): int
    {
        return $this->cp;
    }

    public function getVille(): string
    {
        return $this->ville;
    }



    public function axiosDefinition(): array
    {
        return ['cp', 'ville'];
    }

}

class Personne
{
    protected string $nom = 'Dupont';
    protected string $prenom = 'Robert';
    protected int $age = 42;
    protected Adresse $adresse;

    public function __construct()
    {
        $this->adresse = new Adresse();
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function isMajeur(): bool
    {
        return $this->age >= 18;
    }

    public function getAdresse(): Adresse
    {
        return $this->adresse;
    }


}

$personnes = [new Personne(),new Personne()];

$properties = ['nom', 'prenom', 'isMajeur', 'adresse'];
$extracted = \UnicaenVue\Axios\AxiosExtractor::extract($personnes,$properties);
var_dump($extracted);