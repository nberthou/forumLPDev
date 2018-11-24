<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23/11/18
 * Time: 15:55
 */

namespace App\Controller;



use App\Entity\Topic;
use App\Form\TopicType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TopicController
 * @package App\Controller
 * @Route("/topic")
 */
class TopicController extends AbstractController
{

    /**
     * @Route("/", name="topic")
     */
    public function index() {

    }
    /**
     * @Route("/new", name="new_topic")
     */
    public function newTopic(Request $request) {
        $topic = new Topic();
        $form = $this->createForm(TopicType::class, $topic);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $title = $topic->getTitle();
            $description = $topic->getDescription();
            $category = $topic->getCategory();
            $topic->setTitle($title);
            $topic->setDescription($description);
            $topic->setCategory($category);
            $em = $this->getDoctrine()->getManager();
            $em->persist($topic);
            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('topic/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{topic}", name="topic")
     */
    public function topic($topic) {
        $top = $topic;
        return $this->render('topic/index.html.twig');
    }
}