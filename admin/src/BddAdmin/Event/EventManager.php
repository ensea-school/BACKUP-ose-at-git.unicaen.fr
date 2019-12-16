<?php

namespace BddAdmin\Event;

class EventManager
{
    /**
     * @var self
     */
    private static $main;

    protected      $events = [];



    /**
     * @return EventManager
     */
    public static function getMain(): self
    {
        if (!self::$main) {
            self::$main = new self;
        }

        return self::$main;
    }



    /**
     * @param             $sender
     * @param string|null $action
     * @param null        $data
     *
     * @return Event
     */
    public function sendEvent($sender, ?string $action = null, $data = null): Event
    {
        $event = new Event($sender, $action, $data);
        $this->send($event);

        return $event;
    }



    /**
     * @param Event $event
     *
     * @return bool
     */
    public function send(Event $event)
    {
        foreach ($this->events as $listener) {
            if ($this->match($listener, $event)) {
                $listener['callback']($event);
            }
        }

        return true;
    }



    private function match(array &$listener, Event $event): bool
    {
        if (empty($listener['sender']) || empty($event->sender) || $listener['sender'] === $event->sender) {
            if (empty($listener['action']) || empty($event->action) || $listener['action'] === $event->action) {
                return true;
            }
        }

        return false;
    }



    /**
     * @param             $sender
     * @param string|null $action
     * @param             $callback
     *
     * @return EventManager
     */
    public function listen($sender, ?string $action, $callback): self
    {
        $this->events[] = compact('sender', 'action', 'callback');

        return $this;
    }
}