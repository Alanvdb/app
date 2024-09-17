<?php declare(strict_types=1);

namespace AlanVdb\Model\Entity;

abstract class AbstractEntity extends \AlanVdb\ORM\Entity\AbstractEntity
{
    protected ValidatorFactory $validatorFactory;
    protected array $errors = [];
    protected array $validators = [];

    public function __construct()
    {
        $this->validatorFactory = new ValidatorFactory();
    }

    public function getErrors() : array
    {
        return $this->errors;
    }

    public function validate() : bool
    {
        foreach ($this->validators as $attribute => $validators) {
            $errors = [];

            foreach ($validators as $validator) {

                if (!$validator->validate($this->$attribute)) {
                    $errors[] = $validator->getErrorMessage();
                }
            }
            if (!empty($errors)) {
                $this->errors[$attribute] = $errors;
            }
        }
        return empty($this->errors);
    }
}
