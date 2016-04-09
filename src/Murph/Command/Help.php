<?php

namespace Murph\Command;

use Murph\Command\Parent\AbstractCommand;

class Help extends AbstractCommand
{
    private $_supportedCmd;
    
    public function run()
    {
        $availableCmd = $this->container->get('doctrine')->getManager()
            ->getRepository('MurphBotBundle:AvailableCommand')
            ->findAll()
        ;

        $this->_supportedCmd = $availableCmd;

        if($this->message->send($this->getText(), $this->chatId)) {
            return true;
        }

        return false;
    }
    public function getText()
    {
        $text = '';

        foreach($this->_supportedCmd as $cmd) {
            if($cmd->getShowHelp()) {
                $text .=
                    $cmd->getLabel() . ' ' .
                    $cmd->getText() . "\n"
                ;
            }
        }

        return $text;
    }
}
