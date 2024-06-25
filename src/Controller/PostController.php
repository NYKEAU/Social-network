<?php
// src/Controller/PostController.php
namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class PostController extends AbstractController
{
    #[Route('/posts', name: 'create_post', methods: ['POST'])] // Utiliser uniquement POST pour la création
    public function createPost(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        dump($data);

        if (!is_array($data)) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($post);
            dump($form->getData());
            $entityManager->persist($post);
            $entityManager->flush();

            return new JsonResponse(['status' => 'Post created'], Response::HTTP_CREATED);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
    }
}