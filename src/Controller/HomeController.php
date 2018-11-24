<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Topic;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $topics = $this->getDoctrine()->getRepository(Topic::class)->findAll();
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('home/index.html.twig', ['categories' => $categories, 'topics' => $topics, 'posts' => $posts, 'users' => $users]);
    }

}
