<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use AppBundle\Entity\Todo;



class TodoController extends Controller
{
    
    /**
     * @Route("/", name="todo")
     */
    public function showAction()
    {
        $date = date('d-m-Y');
        $user = $this->get('security.token_storage')->getToken()->getUsername();

        $todos = $this->getDoctrine()->getRepository('AppBundle:Todo')->findBy(array('username' => $user));
        return $this->render('todo/show.html.twig',[
            'todos' => $todos,
            'user' => $user,
            'date' => $date
        ]);
    }
    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('description', TextType::class)
            ->add('priority', ChoiceType::class, array('choices'   => array('low' => 'Low', 'normal' => 'Normal' , 'high' => 'High')))
            ->add('add', SubmitType::class)
            ->getForm();
        
        $form->handleRequest($request);

        

        if ($form->isSubmitted())
        {
            $user = $this->get('security.token_storage')->getToken()->getUsername();
            $date = date('d-m-Y');
            $add = $form['description']->getData();
            $priority = $form['priority']->getData();
            $em = $this->getDoctrine()->getManager();

            $todo = new Todo();
            $todo->setUsername($user);
            $todo->setDescription($add);
            $todo->setPriority($priority);
            $todo->setDate($date);


            $em->persist($todo);
            $em->flush();
            $this->addFlash(
                'notice',
                'Todo Added'
            );
            return $this->redirectToRoute('todo');
       }

        return $this->render('todo/add.html.twig',[
            'add_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}")
     */
    public function editAction($id, Request $request)
    {

        $todo = $this->getDoctrine()->getRepository('AppBundle:Todo')->find($id);

        $form = $this->createFormBuilder()
            ->add('discription' , TextType::class)
            ->add('priority', ChoiceType::class, array('choices'   => array('low' => 'Low', 'normal' => 'Normal' , 'high' => 'High'),))
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $date = date('d-m-Y');
            $edit = $form['discription']->getData();
            $priority = $form['priority']->getData();
            $em = $this->getDoctrine()->getManager();

            $todo->setDiscription($edit);
            $todo->setPriority($priority);
            $todo->setDate($date);
            $em->flush();
            
            return $this->redirectToRoute('todo');
        }

        return $this->render('todo/edit.html.twig',[
            'edit_form' => $form->createView(),
            'todo' => $todo
        ]);
    }
    /**
     * @Route("/delete/{id}")
     */
    public function deleteeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $todo = $em->getRepository('AppBundle:Todo')->find($id);

    
        $em->remove($todo);
        $em->flush();
        
        return $this->redirectToRoute('todo');
    }
}
