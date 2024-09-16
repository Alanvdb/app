<?php declare(strict_types=1);

namespace AlanVdb\App\Entity;

use AlanVdb\ORM\Entity\AbstractEntity;
use AlanVdb\App\Validator\StringLengthValidator;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;

use InvalidArgumentException;

#[Entity]
#[Table(name: 'users')]
class User extends AbstractEntity
{
    #[Column(type: 'string', length: 32)]
    protected ?string $username = null;

    #[Column(type: 'string', length: 255)]
    protected ?string $email = null;

    /**
     * @return string|null Username
     */
    public function getUsername() : ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @throws InvalidArgumentException
     */
    public function setUsername(string $username) : void
    {
        $nameLength = strlen($username);
        if ($nameLength < 4) {
            throw new InvalidArgumentException('Username must contain 4 characters minimum.');
        } elseif ($nameLength > 32) {
            throw new InvalidArgumentException('Username must contain 32 characters maximum.');
        }
        $this->username = $username;
    }

    public function getEmail() : ?string
    {
        return $this->email;
    }

    public function setEmail(string $email) : void
    {
        $this->email = $email;
    }
}
