<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

use App\Entity\Maquina;

class MaquinaFixtures extends Fixture
{
    private $logger;

    public const PREFIX = 'maquina-';
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    public function load(ObjectManager $manager)
    {
        $this->logger->info('Inicio creacion fixture MaquinaFixtures');

        $entidades = [
            array(
              'nombre'=>'maquina 1'
            ),
            array(
              'nombre'=>'maquina 2'
            ),
        ];
        foreach ($entidades as $entidad) {
            $e = new Maquina();
            $e->setNombre($entidad['nombre']);
            $manager->persist($e);
            
            $this->addReference(self::PREFIX . $e->getNombre(), $e);
        }
        $manager->flush();

        $this->logger->info('Fin creacion fixture MaquinaFixtures');
    }
    
}

