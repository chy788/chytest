<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Provisioning for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Provisioning\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ePals\Lookup\CountryLookup;
use ePals\Lookup\SchoolTypeLookup;
use ePals\Lookup\GradeLookup;
use ePals\Lookup\AgeRangeLookup;
use ePals\Lookup\RoleLookup;
use ePals\Base\User;
use ePals\UserAttribute;

require_once ('class.phpmailer.php');
require_once ('module.php');

//$apiDir = dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/epalsAPIs/php/classes/';
//require_once ($apiDir . 'SessionBroker.php');
//require_once ($apiDir . 'Session.php');
//use EPalsAPIs\User

class ProvisioningController extends AbstractActionController {

    public function indexAction() {
        $view = new ViewModel();
        $view->setTerminal(true);
        return $view;
    }

    /*
     * ajax save user base info 
     */

    public function ajaxPostBaseInfoAction() {

        $post_data = $this->getRequest()->getPost();
        $response = $this->getResponse();
        //do ...
        $role = $post_data['role'];
        $step = $post_data['currentStep'] - 1;
        $err = '';
        $re = new \Register();
        
        switch ($role) {
            case 'teacher':
                $err = $re->add_teacher($post_data);
                break;
            case 'teacher_homeschool':
                $err = $re->add_teacher($post_data);
                break;
            case 'student':
                $err = $re->add_student($post_data);
                break;
            case 'parent':
                $err = $re->add_parent($post_data);
                break;
            default:
                echo "the role is not available";
        }

        if (!$err) {
            $err = $post_data;
        }

        $response->setContent(\Zend\Json\Json::encode($err));
        return $response;
    }

    public function ajaxregistAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            var_dump($post_data);
            $post_data = $request->getPost();
            $role = $post_data['role'];
            $step = $post_data['currentStep'] - 1;
            $err = '';
            $re = new \Register();
            switch ($role) {
                case 'teacher':
                    $err = $re->add_teacher_attr($post_data);
                    break;
                case 'teacher_homeschool':
                    $err = $re->add_hsteacher_attr($post_data);
                    break;
                case 'student':
                    $err = $re->add_student_attr($post_data);
                    break;
                case 'parent':
                    $err = $re->par_add_attr($post_data);
                    break;
                default:
                    echo "the role is not available";
            }
            if (!$err) {
                $err = $post_data;
            }

            $response->setContent(\Zend\Json\Json::encode($err));
        }
        return $response;
    }

    /**
     * ajax return roles
     * @return \Zend\View\Model\ViewModel
     * teacher : Teacher
     * teacher_homeschool : Home-School Teacher
     * student : Student
     * parent : Parent / Guardian
     * mentor : Mentor
     */
    public function ajaxLoadRolesAction() {
        $response = $this->getResponse();

        // get roles
        $roles = new RoleLookup();
        $roles->set_hostname("http://steve.dev.epals.com");
        $roles->set_app_id("example");
        $roles->set_app_key("123");
        $roles->load();
        $all_roles = $roles->getAllRoles();

        // encode roles and return
        $response->setContent(\Zend\Json\Json::encode($all_roles));
        return $response;
    }

    /**
     * ajax return countrys
     * @return \Zend\View\Model\ViewModel
     */
    public function ajaxLoadCountrysAction() {
        $response = $this->getResponse();

        // get roles
        $countries = new CountryLookup();
        $countries->set_hostname("http://steve.dev.epals.com");
        $countries->set_app_id("example");
        $countries->set_app_key("123");

        $countries->load();
        $all_countries = $countries->getAllCountries();

        // encode roles and return
        $response->setContent(\Zend\Json\Json::encode($all_countries));
        return $response;
    }

    /**
     * ajax return SchoolType
     * @return \Zend\View\Model\ViewModel
     */
    public function ajaxLoadSchoolTypeAction() {
        $response = $this->getResponse();

        // get roles
        $schooltypes = new SchoolTypeLookup();
        $schooltypes->set_hostname("http://steve.dev.epals.com");
        $schooltypes->set_app_id("example");
        $schooltypes->set_app_key("123");
        $schooltypes->load();

        $all_schooltypes = $schooltypes->getAllSchoolTypes();

        // encode roles and return
        $response->setContent(\Zend\Json\Json::encode($all_schooltypes));
        return $response;
    }

    /**
     * ajax return AgeRange
     * @return \Zend\View\Model\ViewModel
     */
    public function ajaxLoadAgeRangeAction() {
        $response = $this->getResponse();

        // get roles
        $ages = new AgeRangeLookup();
        $ages->set_hostname("http://steve.dev.epals.com");
        $ages->set_app_id("example");
        $ages->set_app_key("123");
        $ages->load();

        $all_ages = $ages->getAllAgeRanges();

        // encode roles and return
        $response->setContent(\Zend\Json\Json::encode($all_ages));
        return $response;
    }

    /**
     * ajax return Grade
     * @return \Zend\View\Model\ViewModel
     */
    public function ajaxLoadGradeAction() {
        $response = $this->getResponse();

        // get roles
        $grades = new GradeLookup();
        $grades->set_hostname("http://steve.dev.epals.com");
        $grades->set_app_id("example");
        $grades->set_app_key("123");
        $grades->load();

        $all_grades = $grades->getAllGrades();

        // encode roles and return
        $response->setContent(\Zend\Json\Json::encode($all_grades));
        return $response;
    }

    //if user exists
    public function ifUserNotExistAction() {
        $username = $_GET['username'];
        $response = $this->getResponse();

        $email = $username . '@epals.com';

        $flag = new User();
        // $flag->set_SIS_Server("http://dev01.neuedu.dev.ec2.epals.net:8080/sis/");
        //$flag->set_PM_Server("http://dev02.neuedu.dev.ec2.epals.net:8080/BasicESB");
        $result = $flag->userExists($email);

        $response->setContent(\Zend\Json\Json::encode(!$result));
        return $response;
    }

    public function DashboardAction() {
        $username = $_GET['username'];

        $userAttribute = new UserAttribute();
        $userAttribute->set_ElasticSearch_Server("http://apidev.dev.epals.com:9200");
        $userAttribute->set_SIS_Server("http://dev01.neuedu.dev.ec2.epals.net:8080/sis/");
        $userAttribute->loadUserAttribute($username . "@epals.com");
        $all_attr = $userAttribute->getAll();

        $viewInfo = array('message' => 'Welcome you visit our site ! ');
        return new ViewModel($viewInfo);
    }

    public function mailAction($email) {
        require 'class.phpmailer.php';

        $mail = new PHPMailer;

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup server
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'jswan';                            // SMTP username
        $mail->Password = 'secret';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

        $mail->From = 'from@example.com';
        $mail->FromName = 'Mailer';
        $mail->addAddress('josh@example.net', 'Josh Adams');  // Add a recipient
        $mail->addAddress('ellen@example.com');               // Name is optional
        $mail->addReplyTo('info@example.com', 'Information');
        $mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');

        $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Here is the subject';
        $mail->Body = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            exit;
        }

        echo 'Message has been sent';
    }

}
