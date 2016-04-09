<?php

namespace Murph\Command;

use Murph\Command\Parent\AbstractCommand;
use Murph\BotBundle\Entity\User;

class Start extends AbstractCommand
{
    public function run()
    {
        $save = $this->_saveChatId($this->chatId, $this->arrayUpdate);
        if(!$save) {
            return false;
        }
        $keyboard = $this->createKeyboard();

        $this->message->send($this->getText(), $this->chatId, $keyboard);
    }

    public function getText()
    {
        $text = "Thanks to use me! For more info type /help or visit the website : www.murph.it";

        return $text;
    }

    public function _saveChatId($chatId, $arrayUpdate)
    {
        if($this->_isJustExist($chatId)) {
            return false;
        } else {
            $name = $arrayUpdate['result'][0]['message']['from']['first_name'];
            $lastname = $arrayUpdate['result'][0]['message']['from']['last_name'];
            $em = $this->container->get('doctrine')->getManager();

            if($name && $chatId) {
                $user = new User();
                $user->setName($name);
                $user->setLastname($lastname);
                $user->setChatId($chatId);

                $em->persist($user);
                $em->flush();
            }

            return true;
        }
    }

    public function _isJustExist($chatId)
    {
        $em = $this->container->get('doctrine');
        $user = $em
            ->getRepository('MurphBotBundle:User')
            ->findBy(
                array('chatId' => $chatId)
            )
        ;

        if(!$user) {
            return false;
        }

        return true;
    }

    public function createKeyboard()
    {
        $replyMarkup = array(
            'keyboard' => array(
                array('/help')
            )
        );

        return json_encode($replyMarkup);
    }
}