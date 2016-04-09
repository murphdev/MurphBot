<?php

namespace Murph\BotBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Murph\MurphBotBundle\Command\MurphBotCommand as Murph;
use Murph\MurphBotBundle\Action\FactoryCreator;

class SendNotificationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('murph:bot:send:notification')
            ->setDescription('Send Notification to all active user')
            ->addOption('notification', null, InputOption::VALUE_REQUIRED, 'Specify notification to send', 'fortune')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $notification = $input->getOption('notification');
        
        if($notification == 'fortune') {
            $creator = new FactoryCreator();
            $action = $creator->getCommand('/fortune');
            $notification = $action->getText(null);
        }

        $em = $this->getContainer()->get('doctrine');
        $allUser = $em
                    ->getRepository('MurphBotBundle:User')
                    ->findAll()
                ;

        foreach($allUser as $user) {
            $this->_sendNotification($user, $notification);
        }

        $output->writeln('Done!');

        return;
    }

    public function _sendNotification($user, $notification)
    {
        try {
            file_get_contents(Murph::API_URL . "sendmessage?chat_id=" . $user->getChatId() . "&text=" . $notification);
        } catch(\Exception $ex) {
            return false;
        }
    }
}
