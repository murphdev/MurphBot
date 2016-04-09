<?php

namespace Murph\Command\Parent;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

abstract class AbstractCommand
{
    protected $container;
    protected $parameters;
    protected $arrayUpdate;
    protected $chatId;
    protected $message;

    public function __construct(Container $container = null)
    {
        $this->container = $container;
        $this->message = $this->container->get('murph.sendmessage');
    }

    public function set($parameters, $arrayUpdate)
    {
        $this->parameters = $parameters;
        $this->arrayUpdate = $arrayUpdate;
        $c = count($arrayUpdate['result']);
        $this->chatId = $arrayUpdate['result'][$c-1]['message']['chat']['id'];
    }

    public function run()
    {
        if($this->message->send($this->getText(), $this->chatId)) {
            return true;
        }

        return false;
    }

    abstract public function getText();
}