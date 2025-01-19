


# AlanVdb/App

**AlanVdb/App** is a modular PHP framework designed for modern web application development, featuring PSR-compliant components, robust routing, and integration with Doctrine ORM and Twig templating.


## Installation

To create a new project, use **Composer**:

```bash
composer create-project alanvdb/app your-project-name
```


## Configuration

After installation, configure the environment variables in the `.env` file located in the root directory of your project.

### Example `.env` File

```ini
# Debug Mode
DEBUG_MODE="true"

# Router Configuration
ROUTES_CONFIG="config/routes.php"

# Database Configuration: SQLite (default)
DB_DRIVER="sqlite"
DB_PATH="memory/database.sqlite"

# Or using MySQL database driver (uncomment and configure):
# DB_DRIVER="pdo_mysql"
# DB_HOST="127.0.0.1"
# DB_NAME="your_database"
# DB_USER="your_username"
# DB_PASSWORD="your_password"

# Doctrine ORM Configuration
ENTITY_DIRECTORIES="src/Model/Entity"
MODEL_PROXY_NAMESPACE="Model\\Proxy"
MODEL_PROXY_DIRECTORY="memory/cache/proxies"

# Twig Configuration
ACTIVATE_TEMPLATE_CACHE="false"
TEMPLATES_DIRECTORY="../assets/views"
RENDERER_CACHE_DIRECTORY="../memory/cache/templates"
```

### Switching Between SQLite and MySQL

1. **For SQLite**:  
   - Keep the default values for `DB_DRIVER` and `DB_PATH`.

2. **For MySQL**:  
   - Uncomment and set values for:
     - `DB_DRIVER` (use `pdo_mysql`).
     - `DB_HOST` (e.g., `127.0.0.1`).
     - `DB_NAME` (your database name).
     - `DB_USER` (your MySQL username).
     - `DB_PASSWORD` (your MySQL password).


## Routing

Routes are defined in `config/routes.php`. Below is an example configuration:

```php
<?php declare(strict_types=1);

namespace App;

use AlanVdb\Controller\MainController;
use AlanVdb\Controller\BlogController;

return [
    ['home', 'GET', '/', [MainController::class, 'index']],
    ['blog', 'GET', '/blog', [BlogController::class, 'index']],
    ['blogPost', 'GET', '/blog/{slug}', [BlogController::class, 'show']],
];
```


## Example Controller: `MainController`

The `MainController` provides an example of how to handle HTTP requests and render templates using Twig. It includes a method to centralize common template parameters.

```php
<?php declare(strict_types=1);

namespace AlanVdb\Controller;

use Psr\Http\Message\ResponseInterface;

class MainController extends AbstractController
{
    public function index(): ResponseInterface
    {
        $params = $this->getCommonTemplateParams();
        $document = $this->twig->render('home.twig', $params);
        return $this->createResponse($document);
    }

    protected function getCommonTemplateParams(): array
    {
        return [
            'uriGenerator' => $this->request->getAttribute('uriGenerator'),
        ];
    }
}
```


## Database Configuration and Commands

### Create a Database Entity

Define your entities using PHP metadata attributes. Here's an example:

```php
<?php declare(strict_types=1);

namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'blogs')]
class Blog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    // Getters and setters...
}
```

### Generate the Database Schema

After creating or updating your entities, run the following command to generate the database schema:

```bash
bin/doctrine orm:schema-tool:create
```

### Update the Schema (If Needed)

If you modify an entity, update the database schema with:

```bash
bin/doctrine orm:schema-tool:update --force
```


## Running the Application

Start the development server:

```bash
php -S localhost:8000 -t public
```

Visit `http://localhost:8000` in your browser.


## License

This project is licensed under the **MIT License**.
