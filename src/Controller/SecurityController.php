<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON data'], Response::HTTP_BAD_REQUEST);
        }
        
        $email = $data['_email'] ?? '';
        $password = $data['_password'] ?? '';

        if (empty($email) || empty($password)) {
            return new JsonResponse(['error' => 'Username, email, and password are required'], Response::HTTP_BAD_REQUEST);
        }

        // Logique d'authentification ici
        $error = $authenticationUtils->getLastAuthenticationError();

        if ($error) {
            return new JsonResponse(['error' => $error->getMessage()], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->getUser(); // Récupérer l'utilisateur actuellement authentifié
        $username = $user ? $user->getUsername() : ''; // Récupérer le nom d'utilisateur de l'utilisateur
    
        // Créer une réponse JSON avec le nom d'utilisateur inclus
        return new JsonResponse([
            'message' => 'User logged in successfully',
            'last_username' => $username,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
