<?php declare(strict_types=1);

namespace AlanVdb\Model\Manager;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractManager
{
    protected EntityManagerInterface $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
