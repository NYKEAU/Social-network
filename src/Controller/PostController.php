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
use App\Repository\UserRepository;

class PostController extends AbstractController
{
    #[Route('/posts', name: 'create_post', methods: ['POST'])] // Utiliser uniquement POST pour la création
    public function createPost(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
    
        $data = json_decode($request->getContent(), true);
    
        // Find the User entity based on the user_id from the request
        $user = $userRepository->find($data['user_id']);
        if (!$user) {
            throw $this->createNotFoundException('No user found for id '.$data['user_id']);
        }
    
        // Replace the user_id in the request data with the User entity
        $data['user_id'] = $user;
    
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

    // Add get method to retrieve all posts
    #[Route('/posts_receive', name: 'get_posts', methods: ['GET'])]
    public function getPosts(EntityManagerInterface $entityManager): JsonResponse
    {
        $posts = $entityManager->getRepository(Post::class)->findAll();
        $postsArray = [];

        foreach ($posts as $post) {
            $postsArray[] = [
                'id' => $post->getId(),
                'content' => $post->getContent(),
                'img_link' => $post->getImgLink(),
                'like_number' => $post->getLikeNumber(),
                'username' => $post->getUserId()->getUsername(),
            ];
        }

        return new JsonResponse($postsArray);
    }
}