<?php

namespace BddAdmin\Event;

class Event
{
    public $sender;

    /**
     * @var string|null
     */
    public $action;

    public $data;

    public $return;



    public function __construct($sender = null, ?string $action = null, $data = null)
    {
        $this->sender = $sender;
        $this->action = $action;
        $this->data   = $data;
    }



    public function getData(?string $key = null)
    {
        if (empty($key) || !is_array($this->data)) {
            return $this->data;
        }
        if (!isset($this->data['key'])) {
            return null;
        }

        return $this->data['key'];
    }



    public function setData($key, $value): self
    {
        if (!is_array($this->data)) {
            $this->data = [];
        }
        $this->data[$key] = $value;

        return $this;
    }



    public function getReturn(?string $key = null)
    {
        if (empty($key) || !is_array($this->return)) {
            return $this->return;
        }
        if (!isset($this->return['key'])) {
            return null;
        }

        return $this->return['key'];
    }



    public function setReturn($key, $value): self
    {
        if (!is_array($this->return)) {
            $this->return = [];
        }
        $this->return[$key] = $value;

        return $this;
    }
}