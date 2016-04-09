<?php

namespace Murph\BotBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Murph\BotBundle\Entity\AvailableCommand;

class NewCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('murph:new:command')
            ->setDescription('Create new command')
            ->addOption('label', null, InputOption::VALUE_REQUIRED, 'label of command (e.g /help, /search)')
            ->addOption('text', null, InputOption::VALUE_REQUIRED, 'description of command')
            ->addOption('show', null, InputOption::VALUE_REQUIRED, 'show your command in help message (supported true false)', 'true')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $label = $input->getOption('label');
        $output->writeln("Adding $label ...");
        if(!$label) {
            throw new \Exception('Invalid Input');
        }
        $text = $input->getOption('text');
        if(!$text) {
            throw new \Exception('Invalid Input');
        }

        $em = $this->getContainer()->get('doctrine')->getManager();

        $availableCommand = new AvailableCommand();

        $availableCommand->setLabel($label);
        $availableCommand->setText($text);
        if($input->getOption('show') == 'true') {
            $availableCommand->setShowHelp(true);
        }
        $em->persist($availableCommand);
        $em->flush();

        $output->writeln('Done!');
    }
}
