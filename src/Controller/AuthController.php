<?php declare(strict_types=1);

namespace AlanVdb\Controller;

use Psr\Http\Message\ResponseInterface;
use AlanVdb\Model\Entity\User;
use AlanVdb\Exception\AuthException;

class AuthController extends AbstractController
{
    protected const TEMPLATE_LOGIN = 'login.twig';
    protected const TEMPLATE_PROFILE = 'profile.twig';

    public function login(): ResponseInterface
    {
        if ($this->request->getMethod() === 'POST') {
            return $this->processLogin();
        }

        $params = $this->getCommonTemplateParams();
        $document = $this->render(self::TEMPLATE_LOGIN, $params);
        return $this->view($document);
    }

    public function logout(): ResponseInterface
    {
        $this->session->remove('connected');
        $this->session->destroy();
        $this->session->set('flash', 'You have been logged out.');
        $this->session->set('flash_type', 'success');
        return $this->redirect('/');
    }

    public function profile() : ResponseInterface
    {
        $params = $this->getCommonTemplateParams();
        $params['user'] = (object) $this->session->get('user');
        $document = $this->render(self::TEMPLATE_PROFILE, $params);
        return $this->view($document);
    }

    private function processLogin(): ResponseInterface
    {
        try {
            $username = $this->request->getParsedBody()['username'] ?? '';
            $password = $this->request->getParsedBody()['password'] ?? '';

            if (empty($username)) {
                throw new AuthException('Username is required');
            }

            if (empty($password)) {
                throw new AuthException('Password is required');
            }

            $userRepository = $this->container->get('entityManager')->getRepository(User::class);
            $user = $userRepository->findOneBy(['username' => $username]);

            if (!$user) {
                throw new AuthException('Invalid credentials');
            }

            if (!password_verify($password, $user->password)) {
                throw new AuthException('Invalid credentials');
            }

            $this->session->set('user', [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email
            ]);
            $this->session->set('connected', true);
            $this->session->set('flash', 'Login successful!');
            $this->session->set('flash_type', 'success');

            return $this->redirect($this->container->get('uriGenerator')->generateUri('profile'));

        } catch (AuthException $e) {
            $this->session->set('flash', $e->getMessage());
            $this->session->set('flash_type', 'error');
            $this->session->set('old_input', [
                'username' => $username ?? ''
            ]);

            return $this->redirect($this->container->get('uriGenerator')->generateUri('home'));
        }
    }

    private function redirect(string $url): ResponseInterface
    {
        $responseFactory = $this->container->get('responseFactory');
        $response = $responseFactory->createResponse(302);
        return $response->withHeader('Location', $url);
    }
}
