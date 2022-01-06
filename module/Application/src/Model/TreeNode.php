<?php

namespace Application\Model;

class TreeNode implements \IteratorAggregate, \ArrayAccess
{

    const ABSOLUTE_ID_SEPARATOR = '/';

    /**
     * @var mixed
     */
    private $id;

    /**
     * @var ?string
     */
    private $label;

    /**
     * @var ?int
     */
    private $ordre;

    /**
     * @var ?string
     */
    private $icon;

    /**
     * @var ?string
     */
    private $title;

    /**
     * @var TreeNode
     */
    private $parent;

    /**
     * @var TreeNode[]
     */
    private $children = [];



    /**
     * TreeNode constructor.
     *
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @return string
     */
    public function getAbsoluteId()
    {
        $ai = $this->getParent() ? $this->getParent()->getAbsoluteId(self::ABSOLUTE_ID_SEPARATOR) : '';
        if ($ai != '' && $this->getId()) {
            $ai .= self::ABSOLUTE_ID_SEPARATOR;
        }
        if ($this->getId()) {
            $ai .= $this->getId();
        }

        return $ai;
    }



    /**
     * @param mixed $id
     *
     * @return TreeNode
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }



    /**
     * @param string|null $label
     *
     * @return TreeNode
     */
    public function setLabel(?string $label): TreeNode
    {
        $this->label = $label;

        return $this;
    }



    /**
     * @return int|null
     */
    public function getOrdre(): ?int
    {
        return $this->ordre;
    }



    /**
     * @param int|null $ordre
     *
     * @return TreeNode
     */
    public function setOrdre(?int $ordre): TreeNode
    {
        $this->ordre = $ordre;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }



    /**
     * @param string|null $icon
     *
     * @return TreeNode
     */
    public function setIcon(?string $icon): TreeNode
    {
        $this->icon = $icon;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }



    /**
     * @param string|null $title
     *
     * @return TreeNode
     */
    public function setTitle(?string $title): TreeNode
    {
        $this->title = $title;

        return $this;
    }



    /**
     * @param $id
     *
     * @return bool
     */
    public function has($id)
    {
        return array_key_exists($id, $this->children);
    }



    /**
     * @return bool
     */
    public function hasChildren()
    {
        return !empty($this->children);
    }



    /**
     * @param $id
     *
     * @return TreeNode|null
     */
    public function get($id)
    {
        if ($this->has($id)) {
            return $this->children[$id];
        }

        return null;
    }



    /**
     * @param TreeNode $isd
     */
    public function add(TreeNode $isd)
    {
        $isd->__setParent($this);
        $this->children[$isd->getId()] = $isd;
    }



    /**
     * @param $id
     *
     * @return $this
     */
    public function remove($id = null)
    {
        if (null == $id && $this->getParent()) {
            return $this->getParent()->remove($this->getId());
        }

        if ($id instanceof TreeNode) {
            $id = $id->getId();
        }

        if ($this->has($id)) {
            $this->children[$id]->__setParent();
            unset($this->children[$id]);
        }

        return $this;
    }



    /**
     * @return TreeNode
     */
    public function getParent()
    {
        return $this->parent;
    }



    /**
     * @param TreeNode $parent
     *
     * @return TreeNode
     */
    public function __setParent(TreeNode $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }



    /**
     * @param array $children
     *
     * @return $this
     */
    public function setChildren(array $children)
    {
        $this->children = [];
        foreach ($children as $child) {
            if (!$child instanceof TreeNode) {
                throw new \Exception('Un fils n\'est pas de classe ' . __CLASS__);
            }
            $this->add($child);
        }

        return $this;
    }



    /**
     * @return TreeNode[]
     */
    public function getChildren()
    {
        return $this->children;
    }



    /**
     * @return $this
     */
    public function order()
    {
        uasort($this->children, function ($a, $b) {
            if ($a->getOrdre() && $b->getOrdre()) {
                return $a->getOrdre() - $b->getOrdre();
            } else {
                return $a->getLabel() > $b->getLabel() ? 1 : 0;
            }
        });

        return $this;
    }



    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->children);
    }



    /**
     * Whether a offset exists
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }



    /**
     * Offset to retrieve
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }



    /**
     * Offset to set
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->add($value);
    }



    /**
     * Offset to unset
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }



    /**
     * @param $absoluteId
     *
     * @return TreeNode|null
     */
    public function findOneByAbsoluteId($absoluteId)
    {
        return $this->findOneBy(['absoluteId' => $absoluteId]);
    }



    /**
     * @param array $criteria
     *
     * @return TreeNode|null
     */
    public function findOneBy(array $criteria)
    {
        if ($this->match($criteria)) return $this;

        foreach ($this->getChildren() as $isd) {
            if ($res = $isd->findOneBy($criteria)) return $res;
        }

        return null;
    }



    /**
     * @param array $criteria
     *
     * @return TreeNode[]
     */
    public function findBy(array $criteria)
    {
        $result = [];

        if ($this->match($criteria)) $result[] = $this;

        foreach ($this->getChildren() as $isd) {
            $cRes = $isd->findBy($criteria);
            foreach ($cRes as $iRes) {
                $result[] = $iRes;
            }
        }

        return $result;
    }



    /**
     * @param array $criteria
     *
     * @return bool
     */
    protected function match(array $criteria)
    {
        foreach ($criteria as $criterium => $value) {
            if (!$this->matchCriterium($criterium, $value)) return false;
        }

        return true;
    }



    /**
     * @param $criterium
     * @param $value
     *
     * @return bool
     */
    protected function matchCriterium($criterium, $value)
    {
        $method = 'get' . ucfirst($criterium);

        if (method_exists($this, $method)) {
            return $this->$method() == $value;
        }

        return false;
    }
}