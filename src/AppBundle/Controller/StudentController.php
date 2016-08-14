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
use AppBundle\Entity\job_applicants;
use AppBundle\Entity\comment_all;
use AppBundle\Entity\student;
use AppBundle\Entity\comments_individual;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
    /**
     * @Route("/student", name="student")
     */
    public function studentAction(Request $request)
    {

        return $this->render('student/index.html.twig');     
    }

    // for employed student contoller start
    /**
     * @Route("/student/cv", name="student_cv")
     */
    public function studentCvAction(Request $request)
    {
        $db = $this->getDoctrine()
        ->getRepository('AppBundle:student')
        ->findOneBy(array('id' => (int)$this->get('session')->get('id')));
        if(count($db)){
            $student_name = $db->getName();
            $cv = $db->getCv();
        }else{
            $student_name = null;
            $cv = null;
        }
        return $this->render('student/cv.html.twig', array(
            'cv' => $cv,
            'student_name' => $student_name,
            'user' => "student" )); 
    }

    /**
     * @Route("/student/jd", name="student_jd")
     */
    public function studentJdAction(Request $request)
    {

        $job_applicants = $this->getDoctrine()
        ->getRepository('AppBundle:job_applicants')
        ->findOneBy(array('studentId' => (int)$this->get('session')->get('id')));
        $job_id = $job_applicants->getJobId();
        $jobs = $this->getDoctrine()
        ->getRepository('AppBundle:jobs')
        ->findOneBy(array('id' => (int)$job_id));

        return $this->render('student/jd.html.twig', array(
            'title' => $jobs->getTitle(),
            'description' => $jobs->getDescription(),
            'user' => "student" )); 
    }

    // for employed student contoller end



    // for unemployed student contoller start

    /**
    * @Route("/student/cv/save", name="student_cv_save" )
    */
    public function cvSaveAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('AppBundle:student')
        ->findOneBy(array('id' => (int)$this->get('session')->get('id')));
        $student->setCv($request->request->get('cv'));
        $em->persist($student);
        $em->flush();
        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/html');
        $response->setContent("success");
        return  $response;      

    }



	/**
     * @Route("/student/comments", name="student_comments")
     */
    public function studentCommentsAction(Request $request)
    {
        $comments_general_all = array();
        $comments_specific_all = array();
        $comments_general_ind = array();
        $comments_specific_ind = array();
        $comments_all_obj = $this->getDoctrine()
        ->getRepository('AppBundle:comment_all')
        ->findAll(); //fetching data from db
        foreach ($comments_all_obj as $key => $value) {
            $comments = array();
            if($value->getType() == 'general'){
                $comment  = array('comment'  => $value->getComment(), 'date' => $value->getDate());
                array_push($comments_general_all, $comment);
            }else
            {
                $comment  = array('comment'  => $value->getComment(), 'date' => $value->getDate());
                array_push($comments_specific_all, $comment);
            }
        }
        $comments_ind_obj = $this->getDoctrine()
        ->getRepository('AppBundle:comments_individual')
        ->findAll(); //fetching data from db
        foreach ($comments_ind_obj as $key => $value) {
            $comments = array();
            if($value->getType() == 'general'){
                $comment  = array('comment'  => $value->getComment(), 'date' => $value->getDate());
                array_push($comments_general_ind, $comment);
            }else
            {
                $comment  = array('comment'  => $value->getComment(), 'date' => $value->getDate());
                array_push($comments_specific_ind, $comment);
            }
        }

        return $this->render('student/comments.html.twig' , [
            'comments_general_all'  =>   $comments_general_all ,
            'comments_specific_all' =>   $comments_specific_all,
            'comments_general_ind'  =>   $comments_general_ind,
            'comments_specific_ind' =>   $comments_specific_ind] );     
    }

    /**
     * @Route("/student/jobs", name="student_jobs")
     */
    public function studentJobsAction(Request $request)
    {

        $employedstudents = $this->getDoctrine()
        ->getRepository('AppBundle:jobs') 
        ->findAll(); 
        $jobs =  array();
        if(count($employedstudents) > 0){
            foreach ($employedstudents as $key => $value) {
            # code... 
                $deadlinedate =  $value->getDeadline();
                $deadlinedate =  $deadlinedate->format('Y-m-d');
                $currentdate = new \Datetime("now");
                $currentdate = $currentdate->format('Y-m-d');
                if(strtotime($deadlinedate) > strtotime($currentdate)){
                    $obj  = array('id' => $value->getId() ,  'title' => $value->getTitle()   ,'description' => $value->getDescription()  ,  'deadline'  => $deadlinedate);
                    array_push($jobs, $obj);
                }
            }
        }
        return $this->render('student/jobs.html.twig',[ 'jobs' => $jobs,]);     
    }


    /**
     * @Route("/student/apply/{id}", name="student_apply")
     */
    public function studentApplyAction(Request $request, $id)
    {
        $this->get('session')->set('type', 'employedstudent');
        $job_applicants = new job_applicants();
        $job_applicants->setStudentId($this->get('session')->get('id'));
        $job_applicants->setJobId($id);
        $job_applicants->setDate(new \Datetime);
        $em = $this->getDoctrine()->getManager();
        $em->persist($job_applicants);
        $em->flush();
        return $this->redirectToRoute('student');

    }


	/**
     * @Route("/student/cv/update", name="student_cv_update")
     */
    public function studentCvUpdateAction(Request $request)
    {
        $db = $this->getDoctrine()
        ->getRepository('AppBundle:student')
        ->findOneBy(array('id' => (int)$this->get('session')->get('id')));
        if(count($db)){
            $student_name = $db->getName();
            $cv = $db->getCv();
        }else{
            $student_name = null;
            $cv = null;
        }
        return $this->render('student/cv_update.html.twig', array(
            'cv' => $cv,
            'student_name' => $student_name,
            'user' => "student" )); 
    }

    // for unemployed student contoller end
}