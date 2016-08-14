<?php

/*
    Name: Mehmood Ayaz;
    Course: Web Software Engineering
    Project: ITB CDM work placement job application manager
*/

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\student;
use AppBundle\Entity\lecturer;
use AppBundle\Entity\employer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $lecturer = new lecturer();   //entity class name\
        return $this->createMyForm($lecturer, "lecturer", $request);
    }

    /**
     * @Route("/studentlogin", name="studentlogin")
     */
    public function studentloginAction(Request $request)
    {
        $student = new student();   //entity class name
        return $this->createMyForm($student, "student", $request);
    }

    /**
     * @Route("/employerlogin", name="employerlogin")
     */
    public function employerloginAction(Request $request)
    {
        $employer = new employer();   //entity class name
        return $this->createMyForm($employer, "employer", $request);  
    }  

    /**
    * @Route("/logout", name="logout")
    */
    public function logoutAction(Request $request)
    {
        $this->get('session')->clear();
        return $this->redirectToRoute('homepage');
    }

    protected function createMyForm($class, $name, $request){
        $form = $this->createFormBuilder($class)
        ->add('email', EmailType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Email', 'style' => 'margin-bottom:15px', 'required' => 'required')))
        ->add('password', PasswordType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Password', 'style' => 'margin-bottom:15px', 'required' => 'required')))
        ->add('login', SubmitType::class, array('label' => 'Login', 'attr' => array('class' => 'btn btn-primary', 'placeholder' => '', 'style' => 'margin-bottom:15px')))
        ->getForm();
        $form->handleRequest($request);
        $pname = null;
        if($form->isSubmitted() && $form->isValid()){
            //do login and redirect
            $email = $form['email']->getData();
            $password = $form['password']->getData();
            $entity = $this->getDoctrine()
            ->getRepository('AppBundle:'.$name)
            ->findOneBy(array('email' => $email, 'password' => md5($password)));
            if (!$entity) {
                $status = "Invalid email or Password!" ;
            } else {
                $status = "Success" ;
                $id = $entity->getId(); 
                $this->get('session')->set('id', $id);
                if($name == 'student'){
                    $student_type = $entity->getStatus();
                    $student_type_new = "";
                    if($student_type == "1"){
                        $student_type_new = "employedstudent";
                    }else{
                        $student_type_new = "unemployedstudent";
                    }
                    $this->get('session')->set('type', $student_type_new);
                }else{
                    $this->get('session')->set('type', $name);
                }
                $this->get('session')->set('islogin', 'true');
                $this->get('session')->set('name', $entity->getName());
                $pname = $entity->getName();
                // die($pname);
                return $this->redirectToRoute($name);
            }
        }else{
            $status = "first" ;

        }
        return  $this->render('default/login.html.twig', array('form' => $form->createView(),
            'status' => $status,
            'name' => $pname,
            'user' => $name ));

    }

}