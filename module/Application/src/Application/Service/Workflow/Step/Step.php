<?php

namespace Application\Service\Workflow\Step;

use Application\Acl\ComposanteRole;
use Common\Exception\LogicException;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Descriptions of Step
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class Step
{
    /**
     * Retourne cette étape dans un format lisible.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLabel() . "[{$this->getKey()}]";
    }

    /**
     * @var integer
     */
    private $index;

    /**
     * @return integer
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param integer $index
     * @return self
     */
    public function setIndex($index)
    {
        $this->index = $index;
        return $this;
    }

    /**
     * @var string
     */
    private $key;

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return self
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @var array
     */
    private $labels;

    /**
     * @return array
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     *
     * @param \Zend\Permissions\Acl\Role\RoleInterface $role
     * @return string
     * @throws LogicException
     */
    public function getLabel(RoleInterface $role = null)
    {
        $key = null;

        if ($role) {
            $key = $role->getRoleId();
            if ($role instanceof ComposanteRole) {
                $key = ComposanteRole::ROLE_ID;
            }
        }

        if (!$key || !isset($this->labels[$key])) {
            $key = 'default';
        }

        if (!isset($this->labels[$key])) {
            throw new LogicException("Aucun label n'a été spécifié pour l'étape '" . get_class() . "' et la clé '$key'.");
        }

        return $this->labels[$key];
    }

    /**
     *
     * @param string $label
     * @param string $key
     * @return self
     */
    public function setLabel($label, $key = null)
    {
        if (!$key) {
            $key = 'default';
        }

        $this->labels[$key] = $label;

        return $this;
    }

    /**
     * @param array $labels
     * @return self
     */
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
//        return $this->descriptions;
    }

    protected function setDescriptions($descriptions)
    {
        $this->descriptions = $descriptions;
        return $this;
    }

    public function getDescription(RoleInterface $role)
    {
        $roleId = $role->getRoleId();
        if ($role instanceof ComposanteRole) {
            $roleId = ComposanteRole::ROLE_ID;
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
    private $routeParams = [];

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

    /**
     * @var bool
     */
    private $crossable = null;

    public function getCrossable()
    {
        return $this->crossable;
    }

    public function setCrossable($crossable = true)
    {
        $this->crossable = $crossable;
        return $this;
    }

    /**
     * @var bool
     */
    private $visible = false;

    public function getVisible()
    {
        return $this->visible;
    }

    public function setVisible($visible = true)
    {
        $this->visible = $visible;
        return $this;
    }

    /**
     * @var \Application\Entity\Db\WfEtape
     */
    private $wfEtape = false;

    public function getWfEtape()
    {
        return $this->wfEtape;
    }

    public function setWfEtape($wfEtape = true)
    {
        $this->wfEtape = $wfEtape;
        return $this;
    }
}