<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Thread;
use App\Entity\Topic;
use App\Form\NewThreadType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ThreadController
 * @package App\Controller
 * @Route("/topic/{topic}")
 */
class ThreadController extends AbstractController
{

    /**
     * @Route("/new", name="new_thread")
     */
   public function newThread(Request $request, $topic) {

       $thread = new Thread();
       $form = $this->createForm(NewThreadType::class, $thread);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()) {
           $user = $this->getUser();
           $title = $thread->getTitle();
           $content = $thread->getContent();
           $top = $this->getDoctrine()->getRepository(Topic::class)->find($topic);
           $now = new \DateTime('now');
           $thread->setAuthor($user);
           $thread->setTopic($top);
           $thread->setTitle($title);
           $thread->setContent($content);
           $thread->setCreatedAt($now);
           $top->addThread($thread);

           $em = $this->getDoctrine()->getManager();
           $em->persist($thread);
           $em->flush();
           return $this->redirectToRoute('topic', array('topic' => $topic));
       }
       return $this->render('thread/new.html.twig', ['form' => $form->createView()]);
   }

   /**
    * @Route("/{thread}", name="thread")
    */
   public function thread(Request $request, $thread) {
       $thr = $this->getDoctrine()->getRepository(Thread::class)->find($thread);
       $reply = new Post();
       $form = $this->createFormBuilder($reply)
           ->add('content', TextareaType::class, ['label' => false, 'attr' => ['class' => 'ckeditor']])
           ->add('Envoyer', SubmitType::class, ['attr' => ['class' => 'btn btn-info']])
           ->getForm();

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()) {
           $user = $this->getUser();
           $content = $reply->getContent();
           $thr = $this->getDoctrine()->getRepository(Thread::class)->find($thread);
           $now = new \DateTime('now');
           $reply->setAuthor($user);
           $reply->setContent($content);
           $reply->setThread($thr);
           $reply->setCreatedAt($now);
           $thr->addReply($reply);

           $em = $this->getDoctrine()->getManager();
           $em->persist($reply);
           $em->flush();
           return $this->redirectToRoute('thread', array('thread' => $thread, 'topic' => $thr->getTopic()->getId()));
       }
       return $this->render('thread/index.html.twig', ['thread' => $thr, 'form' => $form->createView()]);
   }
}
