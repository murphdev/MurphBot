<?php

namespace Murph\BotBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Murph\BotBundle\Entity\AvailableCommand;

class RemoveCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('murph:remove:command')
            ->setDescription('Remove available action')
            ->addOption('label', null, InputOption::VALUE_REQUIRED, 'label of command (e.g /help, /search)')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $label = $input->getOption('label');
        $output->writeln("Removing $label ...");
        if(!$label) {
            throw new \Exception('Invalid Input');
        }

        $em = $this->getContainer()->get('doctrine')->getManager();

        $availableCommand = $em
                                ->getRepository('MurphBotBundle:AvailableCommand')
                                ->findOneBy(
                                    array('label' => $label)
                            );

        $em->remove($availableCommand);
        $em->flush();
        
        $output->writelne('Done!');
    }
}
