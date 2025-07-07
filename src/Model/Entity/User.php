<?php declare(strict_types=1);

namespace AlanVdb\Model\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use AlanVdb\Validator\Definition\ValidatorFactoryInterface;

use InvalidArgumentException;

#[Entity]
#[Table(name: 'users')]
class User extends AbstractEntity
{
    #[Column(type: 'string', length: 32, unique: true)]
    public ?string $username = null;

    #[Column(type: 'string', length: 255, unique: true)]
    public ?string $email = null;

    #[Column(type: 'string', length: 2000)]
    public ?string $password = null;

    public function __construct(ValidatorFactoryInterface $validatorFactory)
    {
        parent::__construct($validatorFactory);
        $this->validators = [
            'username' => [
                $this->validatorFactory->createStringLengthValidator(4, 32),
                $this->validatorFactory->createRegexPatternValidator('`^[a-zA-Z0-9\-_]+$`', 'can contain letters, digits, hyphen and underscore')
            ],
            'email' => [
                $this->validatorFactory->createEmailValidator(),
                $this->validatorFactory->createStringLengthValidator(8, 255)
            ],
            'password' => [
                $this->validatorFactory->createStringLengthValidator(8, 2000)
            ]
        ];
    }
}
