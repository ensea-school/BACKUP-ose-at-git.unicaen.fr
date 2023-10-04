<?php

namespace Application\Model;

class TreeNode implements \IteratorAggregate, \ArrayAccess
{

    const ABSOLUTE_ID_SEPARATOR = '/';

    private mixed $id;

    private ?string $label = null;

    private ?int $ordre = null;

    private ?string $icon = null;

    private ?string $title = null;

    private ?TreeNode $parent = null;

    /**
     * @var TreeNode[]
     */
    private array $children = [];



    public function __construct(mixed $id)
    {
        $this->id = $id;
    }



    public function getId(): mixed
    {
        return $this->id;
    }



    public function getAbsoluteId(): string
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



    public function setId(mixed $id): self
    {
        $this->id = $id;

        return $this;
    }



    public function getLabel(): ?string
    {
        return $this->label;
    }



    public function setLabel(?string $label): TreeNode
    {
        $this->label = $label;

        return $this;
    }



    public function getOrdre(): ?int
    {
        return $this->ordre;
    }



    public function setOrdre(?int $ordre): TreeNode
    {
        $this->ordre = $ordre;

        return $this;
    }



    public function getIcon(): ?string
    {
        return $this->icon;
    }



    public function setIcon(?string $icon): TreeNode
    {
        $this->icon = $icon;

        return $this;
    }



    public function getTitle(): ?string
    {
        return $this->title;
    }



    public function setTitle(?string $title): TreeNode
    {
        $this->title = $title;

        return $this;
    }



    public function has(mixed $id): bool
    {
        return array_key_exists($id, $this->children);
    }



    public function hasChildren(): bool
    {
        return !empty($this->children);
    }



    public function get(mixed $id): ?TreeNode
    {
        if ($this->has($id)) {
            return $this->children[$id];
        }

        return null;
    }



    public function add(TreeNode $isd): self
    {
        $isd->__setParent($this);
        $this->children[$isd->getId()] = $isd;

        return self;
    }



    public function remove(mixed $id = null): self
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



    public function getParent(): ?TreeNode
    {
        return $this->parent;
    }



    public function __setParent(?TreeNode $parent = null): self
    {
        $this->parent = $parent;

        return $this;
    }



    /**
     * @param TreeNode[] $children
     *
     * @return $this
     */
    public function setChildren(array $children): self
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
    public function getChildren(): array
    {
        return $this->children;
    }



    public function order(): self
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



    public function getIterator() : \Traversable
    {
        return new \ArrayIterator($this->children);
    }



    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }



    public function offsetGet(mixed $offset): ?TreeNode
    {
        return $this->get($offset);
    }



    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->add($value);
    }



    public function offsetUnset(mixed $offset): void
    {
        $this->remove($offset);
    }



    public function findOneByAbsoluteId(string $absoluteId): ?TreeNode
    {
        return $this->findOneBy(['absoluteId' => $absoluteId]);
    }



    public function findOneBy(array $criteria): ?TreeNode
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
    public function findBy(array $criteria): array
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



    protected function match(array $criteria): bool
    {
        foreach ($criteria as $criterium => $value) {
            if (!$this->matchCriterium($criterium, $value)) return false;
        }

        return true;
    }



    protected function matchCriterium(string $criterium, string $value): bool
    {
        $method = 'get' . ucfirst($criterium);

        if (method_exists($this, $method)) {
            return $this->$method() == $value;
        }

        return false;
    }
}