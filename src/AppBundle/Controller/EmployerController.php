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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use AppBundle\Entity\jobs;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmployerController extends Controller
{
    /**
     * @Route("/employer", name="employer")
     */
    public function employerAction(Request $request)
    {
        return $this->render('employer/index.html.twig');     
    }


    /**
     * @Route("/employer/applicants", name="employer_applicants")
     */
    public function employerApplicantsAction(Request $request)
    {
        $jobs_obj = $this->getDoctrine()
        ->getRepository('AppBundle:jobs')
        //fetching data from db
        ->findAll();
        $jobs =  array();
        foreach ($jobs_obj as $key => $value) {
            $date =  $value->getDeadline();
            $date =  $date->format('Y-m-d');
            $curdate =  new \DateTime();
            $curdate =  $curdate->format('Y-m-d');
            if( strtotime($date) < strtotime($curdate) ){
                $job_obj = $this->getDoctrine()
                ->getRepository('AppBundle:job_applicants')
                    //fetching data from db
                ->findBy(array('jobId' => $value->getid(), 'status' => '1' ));
                $applicants  =  array();
                foreach ($job_obj as $key => $value2) {
                        # code...
                    $student = $this->getDoctrine()
                    ->getRepository('AppBundle:student')
                        //fetching data from db
                    ->findOneBy(array('id' => $value2->getStudentId()));
                    array_push($applicants, array('student_id' => $student->getid() ,
                      'name' => $student->getName()  ,  'email'  => $student->getEmail() ,
                      'phone'  =>  $student->getPhone(), 'address' => $student->getAddress(),
                      'img' => $student->getImg(), 'cv' => $student->getCv() ));
                }
                $obj  = array('id' => $value->getid() ,  'title' => $value->getTitle()  ,  'description'  =>   $value->getDescription() ,  'deadline'  =>  $date , 'applicants' => $applicants );
                array_push($jobs, $obj);
            }
        }
        return $this->render('employer/applicants.html.twig',['jobs' => $jobs]);   
    }

    /**
     * @Route("/employer/applicants/{id}", name="employer_applicants_cv")
     */
    public function employerApplicantsCvAction(Request $request, $id)
    {
       $cv_obj = $this->getDoctrine()
       ->getRepository('AppBundle:student')
       ->findOneBy(array('id' => (int)$id));
       if( count($cv_obj) > 0 ){
        $response = new Response($this->render('employer/cv.html.twig', [ 'cv' => $cv_obj->getCv()]));
        $response->headers->set('Content-type' , 'application/doc');
        // $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,'cv.pdf');
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'cv.doc'
            );
        $response->headers->set('Content-Disposition', $disposition);
        return $response;
        // return $this->render('employer/cv.html.twig', [ 'cv' => $cv_obj->getCv()]);
        }
    

    }



    /**
     * @Route("/employer/job/send", name="employer_job_send")
     */
    public function employerJobSendAction(Request $request)
    {
        $status = null;
        $jobs = new jobs();
        $job_form = $this->createFormBuilder($jobs)
        ->add('title', TextType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'name', 'style' => 'margin-bottom:15px', 'required' => 'required')))
        ->add('deadline', DateType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'email', 'style' => 'margin-bottom:15px', 'required' => 'required')))
        ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'description', 'style' => 'margin-bottom:15px', 'required' => 'required')))
        ->add('Submit', SubmitType::class, array('label' => 'Submit', 'attr' => array('class' => 'btn btn-primary', 'placeholder' => '', 'style' => 'margin-bottom:15px')))
        ->getForm();
        $job_form->handleRequest($request);
        if($job_form->isSubmitted() && $job_form->isValid()){           
            $title = $job_form['title']->getData();
            $deadline = $job_form['deadline']->getData();
            $description = $job_form['description']->getData();
            $employerId = $this->get('session')->get('id');

            $jobs = new jobs();
            $jobs->setTitle($title);
            $jobs->setDescription($description);
            $jobs->setEmployerId($employerId);
            $jobs->setDateCreated(new \Datetime);
            $jobs->setDeadline($deadline);
            $em = $this->getDoctrine()->getManager();
            $em->persist($jobs);
            $em->flush();
            $status = "success";
        } 
        return $this->render('employer/job_send.html.twig',array(
            'job_form' => $job_form->createView(), 'status' => $status));  
    }

       
}