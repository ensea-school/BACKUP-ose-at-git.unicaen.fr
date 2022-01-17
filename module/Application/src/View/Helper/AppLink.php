<?php

namespace Application\View\Helper;

use Application\Entity\Db\Traits\AnneeAwareTrait;
use Laminas\I18n\View\Helper\AbstractTranslatorHelper;
use Laminas\Router\RouteMatch;
use Laminas\Router\RouteStackInterface;
use UnicaenApp\Exception\LogicException;
use Laminas\Form\Element\Select;

/**
 * Aide de vue dessinant le titre (de niveau 1) de l'application sous forme d'un lien
 * pointant vers la page d'accueil.
 *
 * Si l'on se trouve déjà sur la page d'accueil, ce n'est pas un lien.
 *
 * Possibilité d'inclure une courte description de l'application comme sous-titre.
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AppLink extends AbstractTranslatorHelper
{
    use AnneeAwareTrait;

    /**
     * @var string
     */
    protected $homeRouteName = 'home';

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $subtitle;

    /**
     * @var RouteStackInterface
     */
    protected $router;

    /**
     * @var RouteMatch.
     */
    protected $routeMatch;

    /**
     * Liste des années disponibles
     *
     * @var array[]
     */
    protected $annees;



    /**
     * Point d'entrée.
     *
     * @param string $title
     * @param string $subtitle
     *
     * @return string Code HTML
     */
    public function __invoke($title = null, $subtitle = null)
    {
        $this->setTitle($title)
            ->setSubtitle($subtitle);

        return $this->render();
    }



    /**
     * Retourne le code généré par cette aide de vue.
     *
     * @return string Code HTML
     */
    protected function render()
    {
        if (!$this->title) {
            throw new LogicException("Aucun titre spécifié.");
        }

        $appName = $this->title;
        $appDesc = null;

        $select = new Select;
        $select->setName('annee');
        $select->setAttribute('id', 'annee');
        $select->setAttribute('data-url', $this->router->assemble([], ['name' => $this->homeRouteName]));
        $select->setAttribute('onchange', 'Util.changementAnnee(this.value);');
        $select->setValueOptions($this->getAnnees());
        $select->setValue($this->getAnnee()->getId());

        $appAnnee = '<span class="annee">' . $this->getView()->formSelect($select) . '</span>';

        if ($this->subtitle) {
            $appDesc = sprintf('<span>%s</span>',
                $this->getTranslator()->translate($this->subtitle, $this->getTranslatorTextDomain()));
        }

        if (!$this->routeMatch || $this->homeRouteName != $this->routeMatch->getMatchedRouteName()) {
            $out = sprintf('<a class="navbar-brand" href="%s" title="%s"><h1 class="title">%s%s</h1></a>%s',
                $this->router->assemble([], ['name' => $this->homeRouteName]),
                $this->getTranslator()->translate("Page d'accueil de l'application", $this->getTranslatorTextDomain()),
                $appName,
                $appDesc,
                $appAnnee);
        } else {
            $out = sprintf('<a class="navbar-brand"><h1 class="title">%s%s</h1></a>%s',
                $appName,
                $appDesc,
                $appAnnee);
        }

        return $out;
    }



    /**
     * Spécifie le titre.
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }



    /**
     * Spécifie le sous-titre.
     *
     * @param string $subtitle
     *
     * @return self
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }



    /**
     * Set the router to use for assembling.
     *
     * @param RouteStackInterface $router
     *
     * @return self
     */
    public function setRouter(RouteStackInterface $router)
    {
        $this->router = $router;

        return $this;
    }



    /**
     * Set route match returned by the router.
     *
     * @param RouteMatch $routeMatch
     *
     * @return self
     */
    public function setRouteMatch(RouteMatch $routeMatch)
    {
        $this->routeMatch = $routeMatch;

        return $this;
    }



    /**
     * Get route match returned by the router.
     *
     * @return RouteMatch
     */
    public function getRouteMatch()
    {
        return $this->routeMatch;
    }



    /**
     * Spécifie le nom de la route correspondant à la page d'accueil.
     *
     * @param string $homeRouteName
     *
     * @return self
     */
    public function setHomeRouteName($homeRouteName)
    {
        $this->homeRouteName = $homeRouteName;

        return $this;
    }



    /**
     *
     * @return array[]
     */
    function getAnnees()
    {
        return $this->annees;
    }



    /**
     *
     * @param array[] $annees
     *
     * @return self
     */
    function setAnnees($annees)
    {
        $this->annees = $annees;

        return $this;
    }

}