<?php declare(strict_types=1);

namespace AlanVdb\App\Manager;

use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    protected EntityManagerInterface $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
