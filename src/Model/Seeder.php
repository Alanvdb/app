<?php

namespace AlanVdb\Model;

use Doctrine\ORM\EntityManagerInterface;
use AlanVdb\Validator\Factory\ValidatorFactory;

class Seeder
{
    private EntityManagerInterface $em;
    private $validatorFactory;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->validatorFactory = new ValidatorFactory();
    }

    public function run(object $data) : void
    {
        echo "{$data->entity} seeding...";
        $entityClass = "AlanVdb\\Model\\Entity\\{$data->entity}";

        if (!class_exists($entityClass)) {
            throw new \RuntimeException("Invalid entity class $entityClass");
        }

        $count = 0;

        foreach ($data->data as $entityData) {
            $entity = new $entityClass($this->validatorFactory);

            foreach ($data->fields as $field) {
                if ($field === 'password') {
                    $entity->$field = password_hash($entityData[array_search($field, $data->fields)], PASSWORD_BCRYPT);
                } else {
                    $entity->$field = $entityData[array_search($field, $data->fields)];
                }
            }
            $this->em->persist($entity);
            $count++;
        }

        $this->em->flush();
        echo " $count created !\n";
    }
}
