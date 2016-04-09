<?php

namespace Murph\BotBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadAvailableCommandData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface{

    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $finder = new Finder();
        $finder->in('web/bundles/murphbot/fixtures/sql');
        $finder->name('availablecommand.sql');
        foreach( $finder as $file ){
            $content = $file->getContents();
            $stmt = $this->container->get('doctrine.orm.entity_manager')->getConnection()->prepare($content);
            $stmt->execute();
        }
    }

    public function getOrder()
    {
        return 1;
    }
}