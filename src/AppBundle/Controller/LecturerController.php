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

use AppBundle\Entity\comment_all;
use AppBundle\Entity\jobs;
use AppBundle\Entity\employer;
use AppBundle\Entity\comments_individual;
use AppBundle\Entity\student;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class LecturerController extends Controller
{
    /**
     * @Route("/lecturer", name="lecturer")
     */
    public function lecturerAction(Request $request)
    {
    	return $this->render('lecturer/index.html.twig');     
    }


    /**
     * @Route("/lecturer/applicants", name="lecturer_applicants")
     */
    public function lecture_applicantsAction(Request $request)
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
                ->findBy(array('jobId' => $value->getid()));
                $applicants  =  array();
                foreach ($job_obj as $key => $value2) {
                    # code...
                    $student = $this->getDoctrine()
                    ->getRepository('AppBundle:student')
                    //fetching data from db
                    ->findOneBy(array('id' => $value2->getStudentId()));
                    $href = ( $student->getStatus() ? ""  : $request->getSchemeAndHttpHost()."/lecturer/applicants/".$student->getid() ) ;
                    array_push($applicants, array('student_id' => $student->getid() ,
                      'name' => $student->getName()  ,  'email'  => $student->getEmail() ,
                        'phone'  =>  $student->getPhone(), 'address' => $student->getAddress(),
                         'img' => $student->getImg(), 'cv' => $student->getCv(),
                          'status' => ($student->getStatus() ? "Employed" : "Unemployed"),
                          'href' =>  $href ));
                }
                $obj  = array('id' => $value->getid() ,  'title' => $value->getTitle()  ,  'description'  =>   $value->getDescription() ,  'deadline'  =>  $date , 'applicants' => $applicants );
                array_push($jobs, $obj);
            }
        }
        return $this->render('lecturer/applicants.html.twig',['jobs' => $jobs]);   
    }




    /**
     * @Route("/lecturer/applicants/{id}", name="lecturer_applicants_id")
     */
    public function lecture_applicantsIdAction(Request $request, $id)
    {


    }




    /**
     * @Route("/lecturer/students", name="lecturer_students")
     */
    public function lecturer_studentsAction(Request $request)
    {
    	$employed = array();
    	$unemployed = array();
    	$db = $this->getDoctrine()
    	->getRepository('AppBundle:student')
    	->findBy(array('status' => "1"));
    	foreach ($db as $key => $value) {
    		$data  = array(
    			'id'        	=> $value->getId() ,
    			'name'  	    => $value->getName(),
    			'email'     	=> $value->getEmail(),
    			'designation'	=> $value->getDesignation(),
    			'phone'     	=> $value->getPhone(),
    			'address'  		=>  $value->getAddress(),
    			'img'  			=>  $value->getImg(),
    			);
    		array_push($employed, $data);
    	}
    	$db = $this->getDoctrine()
    	->getRepository('AppBundle:student')
    	->findBy(array('status' => "0"));
    	foreach ($db as $key => $value) {
    		$data  = array(
    			'id'        	=> $value->getId() ,
    			'name'  		=> $value->getName(),
    			'email'     	=> $value->getEmail(),
    			'designation'	=> $value->getDesignation(),
    			'phone'     	=> $value->getPhone(),
    			'address'  		=>  $value->getAddress(),
    			'img'  			=>  $value->getImg(),
    			);
    		array_push($unemployed, $data);
    	}

    	$comment_all = new comment_all();
    	$comment_general = $this->createFormBuilder($comment_all)
    	->add('comment', TextType::class, array('attr' => array('class' => 'form-control', 'placeholder' => '', 'style' => 'margin-bottom:15px', 'required' => 'required')))
    	->add('Submit', SubmitType::class, array('label' => 'Submit', 'attr' => array('class' => 'btn btn-primary', 'placeholder' => '', 'style' => 'margin-bottom:15px')))
    	->getForm();
    	$comment_general->handleRequest($request);
    	if($comment_general->isSubmitted() && $comment_general->isValid()){
    		$comment = $comment_general['comment']->getData();
            $comment_all = new comment_all();
            $comment_all->setLecturerId($this->get('session')->get('id'));
            $comment_all->setComment($comment);
            $comment_all->setType("general");
            $comment_all->setDate(new \Datetime);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment_all);
            $em->flush();
    	}
        
        $comment_all2 = new comment_all();
    	$comment_section = $this->createFormBuilder($comment_all2)
    	->add('comment', TextType::class, array('attr' => array('class' => 'form-control', 'placeholder' => '', 'style' => 'margin-bottom:15px', 'required' => 'required')))
    	->add('Submit', SubmitType::class, array('label' => 'Submit', 'attr' => array('class' => 'btn btn-primary', 'placeholder' => '', 'style' => 'margin-bottom:15px')))
    	->getForm();
    	$comment_section->handleRequest($request);
    	if($comment_section->isSubmitted() && $comment_section->isValid()){
    		$comment = $comment_section['comment']->getData();

            $comment_all = new comment_all();
            $comment_all->setLecturerId($this->get('session')->get('id'));
            $comment_all->setComment($comment);
            $comment_all->setType("specific");
            $comment_all->setDate(new \Datetime);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment_all);
            $em->flush();


    	}

    	return $this->render('lecturer/students.html.twig', array(
    		'comment_general' => $comment_general->createView(),
    		'comment_section' => $comment_section->createView(),
    		'unemployed' => $unemployed,
    		'employed' => $employed,
    		'user' => "lecturer" ));   
    }
    /**
     * @Route("/lecturer/student/{id}", name="lecturer_student_id")
     */
    public function lecturer_student_idAction(Request $request, $id)
    {
    	$db = $this->getDoctrine()
    	->getRepository('AppBundle:student')
    	->findOneBy(array('id' => (int)$id));
        if(count($db)){
        	$student_name = $db->getName();
        	$cv = $db->getCv();
        }else{
            $student_name = null;
            $cv = null;
            
        }

    	$comment_individual = new comments_individual();
    	$comment_general = $this->createFormBuilder($comment_individual)
    	->add('comment', TextType::class, array('attr' => array('class' => 'form-control', 'placeholder' => '', 'style' => 'margin-bottom:15px', 'required' => 'required')))
    	->add('Submit', SubmitType::class, array('label' => 'Submit', 'attr' => array('class' => 'btn btn-primary', 'placeholder' => '', 'style' => 'margin-bottom:15px')))
    	->getForm();
    	$comment_general->handleRequest($request);
    	if($comment_general->isSubmitted() && $comment_general->isValid()){
    		$comment = $comment_general['comment']->getData();
    	}
    	$comment_section = $this->createFormBuilder($comment_individual)
    	->add('comment', TextType::class, array('attr' => array('class' => 'form-control', 'placeholder' => '', 'style' => 'margin-bottom:15px', 'required' => 'required')))
    	->add('Submit', SubmitType::class, array('label' => 'Submit', 'attr' => array('class' => 'btn btn-primary', 'placeholder' => '', 'style' => 'margin-bottom:15px')))
    	->getForm();
    	$comment_section->handleRequest($request);
    	if($comment_section->isSubmitted() && $comment_section->isValid()){
    		$comment = $comment_section['comment']->getData();
    	}

    	return $this->render('lecturer/student_detail.html.twig', array(
    		'comment_general' => $comment_general->createView(),
    		'comment_section' => $comment_section->createView(),
    		'cv' => $cv,
    		'student_name' => $student_name,
    		'user' => "lecturer" ));        
    }    
    /**
     * @Route("/lecturer/student_new", name="lecturer_student_new")
     */
    public function lecturer_student_newAction(Request $request)
    {
        $student = new student();
        $status = null;
        $form = $this->createFormBuilder($student)
        ->add('name', TextType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'name', 'style' => 'margin-bottom:15px', 'required' => 'required')))
        ->add('email', EmailType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'email', 'style' => 'margin-bottom:15px', 'required' => 'required')))
        ->add('password', PasswordType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'password', 'style' => 'margin-bottom:15px', 'required' => 'required')))
        ->add('phone', NumberType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'phone', 'style' => 'margin-bottom:15px', 'required' => 'required')))
        ->add('address', TextType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'address', 'style' => 'margin-bottom:15px', 'required' => 'required')))
        ->add('designation', TextType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'designation', 'style' => 'margin-bottom:15px', 'required' => 'required')))
        ->add('img', FileType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'name', 'style' => 'margin-bottom:15px', 'required' => 'required')))
        ->add('Submit', SubmitType::class, array('label' => 'Submit', 'attr' => array('class' => 'btn btn-primary', 'placeholder' => '', 'style' => 'margin-bottom:15px')))
        ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $name = $form['name']->getData();
            $email = $form['email']->getData();
            $password = $form['password']->getData();
            $phone = $form['phone']->getData();
            $address = $form['address']->getData();
            $designation = $form['designation']->getData();
            $filename = $email.'.jpg';
            $img = $filename;
            $status = "0";

            $cv = '<div style="text-align: right;"><img src="file:///C:/Users/Job/AppData/Local/Temp/msohtmlclip1/01/clip_image001.gif">Curriculum Vitae of John Doe</div><div><img src="file:///C:/Users/Job/AppData/Local/Temp/msohtmlclip1/01/clip_image003.jpg" style="color: gray; font-family: &quot;Lucida Sans Unicode&quot;, sans-serif;"></div><div><span style="color: gray; font-family: &quot;Lucida Sans Unicode&quot;, sans-serif; font-size: 22pt;">John Doe</span></div><div><span style="color: black; font-family: &quot;Lucida Sans Unicode&quot;, sans-serif;">Sales Manager</span></div><div><span style="font-family: &quot;Lucida Sans Unicode&quot;, sans-serif; font-size: 10pt;">&nbsp;</span></div><div><span style="font-family: &quot;Lucida Sans Unicode&quot;, sans-serif; font-size: 10pt;">Address 1</span></div><div><span style="font-family: &quot;Lucida Sans Unicode&quot;, sans-serif; font-size: 10pt;">Address 2</span></div><div><span style="font-family: &quot;Lucida Sans Unicode&quot;, sans-serif; font-size: 10pt;">Address 3</span></div><div><span style="font-family: &quot;Lucida Sans Unicode&quot;, sans-serif; font-size: 10pt;">Tel: phone no here</span></div><div><span style="font-family: &quot;Lucida Sans Unicode&quot;, sans-serif; font-size: 10pt;">Email: </span><span style="font-family: &quot;Lucida Sans Unicode&quot;, sans-serif; font-size: 10pt; color: gray;">email@email.com</span></div><div><span style="color: gray; font-family: &quot;Lucida Sans Unicode&quot;, sans-serif;">&nbsp;</span></div><div style="text-align: center;">PROFILE</div><div>&nbsp;</div><div><span style="font-family: &quot;Lucida Sans Unicode&quot;, sans-serif;">See section on personal profiles</span></div><div><span style="color: gray; font-family: &quot;Lucida Sans Unicode&quot;, sans-serif;">&nbsp;</span></div><div>RELEVANT SKILLS</div><div>&nbsp;</div><div><span style="color: gray; font-family: &quot;Lucida Sans Unicode&quot;, sans-serif;">&nbsp;</span></div><div><span style="color: windowtext;">EDUCATION</span></div><div>&nbsp;</div><div><i style="font-family: &quot;Arial Narrow&quot;, sans-serif; font-size: 11pt;">Qualification title here</i><span style="font-family: &quot;Arial Narrow&quot;, sans-serif; font-size: 11pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Academic Institution Name - City, State &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2001-2004</span></div><div><span style="font-family: &quot;Arial Narrow&quot;, sans-serif;">&nbsp;</span></div><div><i style="font-family: &quot;Arial Narrow&quot;, sans-serif; font-size: 11pt;">Qualification title here</i><span style="font-family: &quot;Arial Narrow&quot;, sans-serif; font-size: 11pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Academic Institution Name - City, State&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1975-1980 </span></div><div><span style="font-family: &quot;Arial Narrow&quot;, sans-serif;">&nbsp;</span></div><div><span style="font-family: &quot;Lucida Sans Unicode&quot;, sans-serif; font-size: 14pt;">WORK EXPERIENCE</span></div><div>&nbsp;</div><div><i style="font-family: &quot;Arial Narrow&quot;, sans-serif; font-size: 11pt;">Job Title Here</i><span style="font-family: &quot;Arial Narrow&quot;, sans-serif; font-size: 11pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Business Name - Location&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2001-2002 </span></div><div><b>&nbsp;</b></div><div><i style="font-family: &quot;Arial Narrow&quot;, sans-serif; font-size: 11pt;">Job Title Here</i><span style="font-family: &quot;Arial Narrow&quot;, sans-serif; font-size: 11pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Business Name - Location&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2003-2004</span></div><div><span style="font-family: &quot;Arial Narrow&quot;, sans-serif;">&nbsp;</span></div><div>OTHER SKILLS</div><div style="text-align: center;"><span style="color: gray; font-family: &quot;Lucida Sans Unicode&quot;, sans-serif;">&nbsp;</span></div><ul><li style="text-align: justify;"><span style="font-family: &quot;Arial Narrow&quot;, sans-serif;">Hobbies or other skills that are not detailed in      the CV.</span></li></ul><div><span style="color: gray; font-family: &quot;Lucida Sans Unicode&quot;, sans-serif;">&nbsp;</span></div><div>REFERENCES</div><div><span style="font-family: &quot;Arial Narrow&quot;, sans-serif;">&nbsp;</span></div><div><span style="font-family: &quot;Arial Narrow&quot;, sans-serif;">Available upon request.</span></div><div>&nbsp;</div>';



            $student = new student();
            $student->setName($name);
            $student->setEmail($email);
            $student->setPassword(md5($password));
            $student->setPhone($phone);
            $student->setAddress($address);
            $student->setImg($img);
            $student->setDesignation($designation);
            $student->setStatus($status);
            $student->setCv($cv);
            $student->setDate(new \Datetime);
            $em = $this->getDoctrine()->getManager();
            $em->persist($student);
            $em->flush();
            $status = "success";
            $uploadDir = $this->container->getParameter('images_directory');
            foreach($request->files as $uploadedFile) {
               $file = $uploadedFile['img']->move($uploadDir, $filename);
            }
        }
        return $this->render('lecturer/student_new.html.twig',array(
            'form' => $form->createView(), 'status' => $status));  
    }
    /**
     * @Route("/lecturer/job/new", name="lecturer_job_new")
     */
    public function lecturer_job_newAction(Request $request)
    {
    	$employers = array();
    	$db = $this->getDoctrine()
    	->getRepository('AppBundle:employer')
    	->findAll();
    	foreach ($db as $key => $value) {
    		$employers[$value->getName()] =  $value->getId();
    	}
        $status = null;
        $jobs = new jobs();
        $job_form = $this->createFormBuilder($jobs)
        ->add('title', TextType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'name', 'style' => 'margin-bottom:15px', 'required' => 'required')))
        ->add('employerId', ChoiceType::class, array('choices' => $employers, 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px', 'required' => 'required')))
        ->add('deadline', DateType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'email', 'style' => 'margin-bottom:15px', 'required' => 'required')))
        ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'description', 'style' => 'margin-bottom:15px', 'required' => 'required')))
        ->add('Submit', SubmitType::class, array('label' => 'Submit', 'attr' => array('class' => 'btn btn-primary', 'placeholder' => '', 'style' => 'margin-bottom:15px')))
        ->getForm();
        $job_form->handleRequest($request);
        if($job_form->isSubmitted() && $job_form->isValid()){           
            $title = $job_form['title']->getData();
            $deadline = $job_form['deadline']->getData();
            $description = $job_form['description']->getData();
            $employerId = $job_form['employerId']->getData();


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
    	return $this->render('lecturer/job_new.html.twig',array(
    		'job_form' => $job_form->createView(), 'status' => $status));  
    }
    /**
     * @Route("/lecturer/employer/new", name="lecturer_employer_new")
     */
    public function lecturer_employer_newAction(Request $request)
    {
    	$employer = new employer();
        $status = null;
    	$employer_form = $this->createFormBuilder($employer)
    	->add('name', TextType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'name', 'style' => 'margin-bottom:15px', 'required' => 'required')))
    	->add('email', EmailType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'email', 'style' => 'margin-bottom:15px', 'required' => 'required')))
    	->add('password', PasswordType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'password', 'style' => 'margin-bottom:15px', 'required' => 'required')))
    	->add('Submit', SubmitType::class, array('label' => 'Submit', 'attr' => array('class' => 'btn btn-primary', 'placeholder' => '', 'style' => 'margin-bottom:15px')))
    	->getForm();
    	$employer_form->handleRequest($request);
    	if($employer_form->isSubmitted() && $employer_form->isValid()){
    		$name = $employer_form['name']->getData();
    		$email = $employer_form['email']->getData();
    		$password = $employer_form['password']->getData();


            $employer = new employer();
            $employer->setName($name);
            $employer->setEmail($email);
            $employer->setPassword(md5($password));
            $employer->setDate(new \Datetime);
            $em = $this->getDoctrine()->getManager();
            $em->persist($employer);
            $em->flush();
            $status = "success";


    	}
    	return $this->render('lecturer/employer_new.html.twig',array(
    		'employer_form' => $employer_form->createView(), 'status' => $status));     
    }
}