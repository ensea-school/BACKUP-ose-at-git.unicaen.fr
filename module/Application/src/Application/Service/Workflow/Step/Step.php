<?php

namespace Application\Service\Workflow\Step;

use SplObjectStorage;

/**
 * Descriptions of Step
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class Step
{
    /**
     * @var int
     */
    private $index;
    
    public function getIndex()
    {
        return $this->index;
    }

    public function setIndex($index)
    {
        $this->index = $index;
        return $this;
    }
    
    /**
     * @var SplObjectStorage
     */
    private $labels;
    
    public function getLabels()
    {
//        if (null === $this->labels) {
//            $this->labels = new \SplObjectStorage();
//        }
        return $this->labels;
    }
    
    public function getLabel(\Zend\Permissions\Acl\Role\RoleInterface $role)
    {
        $roleId = $role->getRoleId();
        if ($role instanceof \Application\Acl\ComposanteRole) {
            $roleId = \Application\Acl\ComposanteRole::ROLE_ID;
        }
        if (!isset($this->labels[$roleId])) {
            if (!isset($this->labels['default'])) {
                throw new \Common\Exception\LogicException("Aucun label par défaut n'a été spécifié pour l'étape '" . get_class() . "'.");
            }
            return $this->labels['default'];
        }
        return $this->labels[$roleId];
    }

    protected function setLabels($labels)
    {
        $this->labels = $labels;
        return $this;
    }
    
    /**
     * @var string[]
     */
    private $descriptions;
    
    public function getDescriptions()
    {
        return <<<EOS
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed sem libero. Nam urna magna, fringilla et blandit aliquam, condimentum a velit. Vivamus sollicitudin blandit augue ut dapibus. Vivamus faucibus quis massa id tempus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor suscipit mauris, in suscipit quam lacinia ut. Nam ac mollis enim, quis tincidunt sem.</p>
<p>Aenean in fermentum dolor. Donec quis odio condimentum, mollis est ut, semper magna. Donec ornare euismod justo, et auctor mi rutrum ut. Vestibulum consequat orci vel nulla facilisis, quis vulputate elit tristique. Nulla nec convallis turpis. Integer nulla nisl, mollis quis magna a, accumsan bibendum felis. Integer fringilla pretium imperdiet. Duis pharetra nisi orci, vel vehicula lectus vehicula eu. Nullam placerat fringilla urna in faucibus. Pellentesque imperdiet interdum arcu sit amet venenatis. Maecenas nulla nunc, blandit a sem vel, vulputate pretium neque. Sed hendrerit nisi orci, eget aliquam justo malesuada quis. Sed ultricies risus sed justo egestas, vel cursus quam bibendum. Etiam eget convallis metus. Phasellus ac lacinia tellus.</p>
<p>Pellentesque venenatis nisi et turpis commodo dapibus. Integer bibendum quis massa ac rutrum. Mauris dolor arcu, luctus pulvinar ligula eu, aliquet dapibus risus. Nulla nec lorem non purus tempor rhoncus mattis non lorem. Duis vehicula arcu eu bibendum sodales. Proin vitae turpis a neque tempus ornare. In hac habitasse platea dictumst. Morbi molestie egestas pellentesque. Fusce sit amet aliquam massa. Sed interdum sapien vel nibh egestas blandit. Nunc placerat ipsum ut dignissim sollicitudin. Fusce malesuada porta libero. Sed mi lectus, commodo a facilisis vel, eleifend ac tortor. Suspendisse auctor sem in massa auctor, id sollicitudin nisi dapibus.</p>
EOS;
        return $this->descriptions;
    }

    protected function setDescriptions($descriptions)
    {
        $this->descriptions = $descriptions;
        return $this;
    }
    
    public function getDescription(\Zend\Permissions\Acl\Role\RoleInterface $role)
    {
        $roleId = $role->getRoleId();
        if ($role instanceof \Application\Acl\ComposanteRole) {
            $roleId = \Application\Acl\ComposanteRole::ROLE_ID;
        }
        if (!isset($this->descriptions[$roleId])) {
//            throw new \Common\Exception\LogicException("Description not set for role '$roleId'!");
            return "";
        }
        return $this->descriptions[$roleId];
    }
    
    /**
     * @var string
     */
    private $route;
    
    public function getRoute()
    {
        return $this->route;
    }

    protected function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }
    
    /**
     * @var array
     */
    private $routeParams = array();
    
    public function getRouteParams()
    {
        return $this->routeParams;
    }

    protected function setRouteParams($routeParams)
    {
        $this->routeParams = $routeParams;
        return $this;
    }
    
    /**
     * @var bool
     */
    private $done = false;
    
    public function getDone()
    {
        return $this->done;
    }

    public function setDone($done = true)
    {
        $this->done = $done;
        return $this;
    }
    
    /**
     * @var bool
     */
    private $isCurrent = false;
    
    public function getIsCurrent()
    {
        return $this->isCurrent;
    }

    public function setIsCurrent($isCurrent = true)
    {
        $this->isCurrent = $isCurrent;
        return $this;
    }
}