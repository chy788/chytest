<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use ePals\Tenant;
use ePals\Course;
use ePals\Teacher;
use ePals\Student;
use ePals\School;
use ePals\Parental;
use ePals\UserAttribute;

class Register {

    //function to create a teacher
    public function add_teacher($arr) {
        if (!$arr['username']) {
            echo 'username cannot be null';
            return false;
        } else if (!$arr['email']) {
            echo 'email cannot be null';
            return false;
        } else if (!$arr['firstname']) {
            echo 'firstname cannot be null';
            return false;
        } else if (!$arr['lastname']) {
            echo 'lastname cannot be null';
            return false;
        } else if (!$arr['password']) {
            echo 'password cannot be null';
            return false;
        } else if ($arr['password'] != $arr['password_vertify']) {
            echo 'password not match';
            return false;
        } else if ($arr['email'] != $arr['email_vertify']) {
            echo 'mail not match';
            return false;
        }
        try {
            $t = new Teacher();
            $t->set_SIS_Server("http://dev01.neuedu.dev.ec2.epals.net:8080/sis/");
            $uid = uniqid("", true);
            $t->setAccount($arr['username'] . "@epals.com");
            $t->setEPalsEmail($arr['username'] . "@mail.epals.com");
            $t->setExternalEmail($arr['email']);
            $t->setUserId($uid);
            $t->setFirstName($arr['firstname']);
            $t->setLastName($arr['lastname']);
            $t->setPassword($arr['password']);
            $t->add();
        } catch (Exception $ex) {
            return 'failed';
        }
        return true;
    }

    //function to add extra information to a teacher
    public function add_teacher_attr($post_data) {
        $s = new Register();
        try {
            $schoolArray = array("Code" => $post_data['school_code'], "Name" => $post_data['school_name'], "Number" => $post_data['school_safe_number'], "Type" => $post_data['school_type'], "Zip" => $post_data['school_zip'], "Address" => $post_data['school_address'], "Country" => $post_data['country']);
            $school_name = $post_data['school_name'];
            $account = $post_data['username'] . "@epals.com";
            $role = 'Teacher';
            $s->add_school($school_name, $schoolArray, $account, $role);
        } catch (Exception $ex) {
            return 'failed';
        }
        try {

            $up = new UserAttribute();
            $up->set_ElasticSearch_Server("apidev.dev.epals.com:9200");
            $up->set_SIS_Server("http://dev01.neuedu.dev.ec2.epals.net:8080/sis/");
            $up->loadUserAttribute($post_data['username'] . "@epals.com");

            $up->add("Gender", $post_data['gender']); // XXXX comes from UI
            $up->add("Birthday", $post_data['birthday']);
            $up->add("Title", $post_data['title']);
            $up->add("Country", $post_data['country']);

            $up->add("YearsExperience", $post_data['years_of_xp']); // XXXX comes from UI
            $up->add("AcademicDegree", $post_data['degree']); // XXXX comes from UI
            $up->add("MachingAvailability", $post_data['match']); // XXXX comes from UI
            $up->add("MoreInfo", $post_data['more_about_you']);

            $up->add("school_type", $post_data['school_type']); // XXXX comes from UI
            $up->add("students_language", $post_data['students_language']); // XXXX comes from UI
        } catch (Exception $ex) {
            return 'failed';
        }
        try {
            $s->add_course($post_data);
        } catch (Exception $ex) {
            return 'failed';
        }
    }

    //function to add extra information to a home-teacher
    public function add_hsteacher_attr($post_data) {
        try {
            $up = new UserAttribute();
            $up->set_ElasticSearch_Server("apidev.dev.epals.com:9200");
            $up->set_SIS_Server("http://dev01.neuedu.dev.ec2.epals.net:8080/sis/");
            $up->loadUserAttribute($post_data['username'] . "@epals.com");
            $up->add("Gender", $post_data['gender']); // XXXX comes from UI
            $up->add("Birthday", $post_data['birthday']);
            $up->add("Title", $post_data['title']);
            $up->add("Country", $post_data['country']);

            $up->add("ExtraRole", "HomeSchoolTeacher"); // this ExtraRole preference would differentiate between home-school teacher and regular teacher
            if ($post_data['teaching_environment'] == 'open') {
                $up->add("ClosedTeachingEnvironment", true); // true/false comes from UI selection
            } else {
                $up->add("ClosedTeachingEnvironment", false); // true/false comes from UI selection
            }
            $up->add("mail_first_name", $post_data['mail_first_name']);
            $up->add("mail_last_name", $post_data['mail_last_name']);
            $up->add("address_line_1", $post_data['address_line_1']);
            $up->add("address_line_2", $post_data['address_line_2']);
            $up->add("mail_city", $post_data['mail_city']);
            $up->add("mail_state", $post_data['mail_state']);
            $up->add("mail_zip", $post_data['mail_zip']);
            $up->add("mail_country", $post_data['mail_country']);
        } catch (Exception $ex) {
            return 'failed';
        }
        try {
            $s->add_course($post_data);
        } catch (Exception $ex) {
            return false;
        }
    }

    //function to create a student
    public function add_student($arr) {
        if (!$arr['username']) {
            echo 'username cannot be null';
            return false;
        } else if (!$arr['email']) {
            echo 'email cannot be null';
            return false;
        } else if (!$arr['firstname']) {
            echo 'firstname cannot be null';
            return false;
        } else if (!$arr['lastname']) {
            echo 'lastname cannot be null';
            return false;
        } else if (!$arr['password']) {
            echo 'password cannot be null';
            return false;
        } else if ($arr['password'] != $arr['password_vertify']) {
            echo 'password not match';
            return false;
        } else if ($arr['email'] != $arr['email_vertify']) {
            echo 'mail not match';
            return false;
        }
        try {
            $student = new Student();
            $student->set_SIS_Server("http://dev01.neuedu.dev.ec2.epals.net:8080/sis/");
            $uid = uniqid("", true);
            $student->setAccount($arr['username'] . "@epals.com");
            $student->setEPalsEmail($arr['username'] . "@mail.epals.com"); // XXXX comes from UI
            $student->setExternalEmail($arr['email']); // YYYY comes from UI
            $student->setUserId($uid);
            $student->setFirstName($arr['firstname']);
            $student->setLastName($arr['lastname']);
            $student->setPassword($arr['password']);
            $student->add();
        } catch (Exception $ex) {
            return 'failed';
        }

        return true;
    }

    //function to add extra information to a student
    public function add_student_attr($post_data) {

        $s = new \Register();
        try {
            $schoolArray = array("Code" => $post_data['school_code'], "Name" => $post_data['school_name'], "Number" => $post_data['school_safe_number'], "Type" => $post_data['school_type'], "Zip" => $post_data['school_zip'], "Address" => $post_data['school_address'], "Country" => $post_data['country'], "Grade" => $post_data['grade']);
            $school_name = $post_data['school_name'];
            $account = $post_data['username'] . "@epals.com";
            $role = $post_data['role'];
            $s->add_school($school_name, $schoolArray, $account, $role);
        } catch (Exception $ex) {
            return 'failed';
        }
        try {

            $up = new UserAttribute();
            $up->set_ElasticSearch_Server("apidev.dev.epals.com:9200");
            $up->set_SIS_Server("http://dev01.neuedu.dev.ec2.epals.net:8080/sis/");
            $up->loadUserAttribute($post_data['username'] . "@epals.com");
            $up->add("FavoriteSubject", ''); // XXXX comes from UI
            $up->add("Interests", ''); // XXXX comes from UI
            $up->add("Gender", $post_data['gender']); // XXXX comes from UI
            $up->add("Birthday", $post_data['birthday']);
            $up->add("Country", $post_data['country']); // XXXX comes from UI
            $up->add("school_type", $post_data['school_type']); // XXXX comes from UI
            $up->add("students_language", $post_data['students_language']); // XXXX comes from UI
            $up->add("FavoriteSubject", $post_data['jsonArr']["grade"]); // 
            $up->add("Interests", $post_data['jsonArr']["interests"]); // 
        } catch (Exception $ex) {
            return 'failed';
        }
    }

    //function to crate a parent
    public function add_parent($arr) {


        try {
            $p = new Parental();
            $p->set_SIS_Server("http://dev01.neuedu.dev.ec2.epals.net:8080/sis/");
            $uid = uniqid("", true);
            $p->setAccount($arr['username'] . "@epals.com");   // epals.com is the unique identifier for the tenant/district created in step 1), XXXX comes from the UI, but please make sure XXXX is unique across the tenant/district
            $p->setEPalsEmail($arr['username'] . "@mail.epals.com");  // XXXX comes from the UI
            $p->setExternalEmail($arr['email']);   // YYYY comes from UI
            $p->setUserId($uid);  // ZZZZ is a auto-generated integer that is unique
            $p->setFirstName($arr['firstname']); //FNAME comes from UI
            $p->setLastName($arr['lastname']); //LNAME comes from UI
            $p->setPassword($arr['password']); // PASSWORD comes from UI
            $p->add();
        } catch (Exception $ex) {
            return 'failed';
        }

        return true;
    }

    //function to create a school
    public function add_school($school_name, $school_info, $account, $role) {
        $school = new School();
        $school->set_SIS_Server("http://dev01.neuedu.dev.ec2.epals.net:8080/sis/");
        $school->set_PM_Server("http://dev02.neuedu.dev.ec2.epals.net:8080/BasicESB");
        $exists = $school->exists("epals.com", $school_name); // epals.com is the Tenant/District ID, XXXX is the school name parsed from UI that we want to create under the tenant/district
        if ($exists) {
            $school->loadSchool("epals.com", $school_name);
        } else {
            $sid = $school_name;
            //$schoolArray = array("Code" => $post_data['school_code'], "Name" => $post_data['school_name'], "Number" => $post_data['school_safe_number'], "Type" => $post_data['school_type'], "Zip" => $post_data['school_zip'], "Address" => $post_data['school_address'], "Country" => $post_data['country']);
            $school->setOptionsString(json_encode($school_info));
            $school->setDescription("Description");
            $school->setSchoolId($sid); //ZZZZ is an auto-generated integer that is unique 
            $school->setName($school_name);  //XXXX comes from the UI
            $school->add("epals.com"); // epals.com is the Tenant/District ID
        }
        $school->loadSchool("epals.com", $school_name);
        $school->addUserToSchool($account, $role);
    }

    //function to add extra information to a parent
    public function par_add_attr($post_data) {
        foreach ($post_data['psubArr'] as $arr) {
            $student = new Student();
            $student->set_SIS_Server("http://dev01.neuedu.dev.ec2.epals.net:8080/sis/");
            try {
                $uid = uniqid("", true);
                $student->setAccount($arr['student_username'] . "@epals.com");
                $student->setEPalsEmail($arr['student_username'] . "@mail.epals.com"); // XXXX comes from UI
                //$student->setExternalEmail($arr['email']); // YYYY comes from UI
                $student->setUserId($uid);
                $student->setFirstName($arr['student_first']);
                $student->setLastName($arr['student_last']);
                $student->setPassword($arr['student_password']);
                $student->add();

                $up = new UserAttribute();
                $up->set_ElasticSearch_Server("apidev.dev.epals.com:9200");
                $up->set_SIS_Server("http://dev01.neuedu.dev.ec2.epals.net:8080/sis/");
                $up->loadUserAttribute($arr['student_username'] . "@epals.com");

                $up->add("Birthday", $arr['student_birthday']);
            } catch (Exception $ex) {
                print ("Add student for parent Failed!\n");
            }
            $s = new \Register();
            try {
                $schoolArray = array("Code" => $arr['school_code'], "Name" => $arr['school_name'], "Number" => $arr['school_safe_number'], "Type" => $arr['school_type'], "Zip" => $arr['school_zip'], "Address" => $arr['student_school_address'], "Country" => $arr['country'], "Grade" => $arr['grade']);
                $school_name = $arr['student_school_name'];
                $account = $arr['student_username'] . "@epals.com";
                $role = 'Student';

                $s->add_school_p($school_name, $schoolArray, $account, $role, $post_data['username'], 'parent');
            } catch (Exception $ex) {
                return 'failed';
            }
        }
    }

    //function to create a course
    public function add_course($post_data) {
        foreach ($post_data['jsonArr'] as $key) {
            $course = new Course();
            $course->set_SIS_Server("http://dev01.neuedu.dev.ec2.epals.net:8080/sis/");
            $course->set_PM_Server("http://dev02.neuedu.dev.ec2.epals.net:8080/BasicESB");

            $course->setTitle($key['subjectName']);
            $course->add("epals.com", $post_data['school_name']);
        }
    }

    //function to create a school for parents
    public function add_school_p($school_name, $school_info, $account, $role, $p_account, $p_role) {
        $school = new School();
        $school->set_SIS_Server("http://dev01.neuedu.dev.ec2.epals.net:8080/sis/");
        $school->set_PM_Server("http://dev02.neuedu.dev.ec2.epals.net:8080/BasicESB");
        $exists = $school->exists("epals.com", $school_name); // epals.com is the Tenant/District ID, XXXX is the school name parsed from UI that we want to create under the tenant/district
        if ($exists) {
            $school->loadSchool("epals.com", $school_name);
        } else {
            $sid = $school_name;
            //$schoolArray = array("Code" => $post_data['school_code'], "Name" => $post_data['school_name'], "Number" => $post_data['school_safe_number'], "Type" => $post_data['school_type'], "Zip" => $post_data['school_zip'], "Address" => $post_data['school_address'], "Country" => $post_data['country']);
            $school->setOptionsString(json_encode($school_info));
            $school->setDescription("Description");
            $school->setSchoolId($sid); //ZZZZ is an auto-generated integer that is unique 
            $school->setName($school_name);  //XXXX comes from the UI
            $school->add("epals.com"); // epals.com is the Tenant/District ID
        }
        $school->loadSchool("epals.com", $school_name);
        $school->addUserToSchool($account, $role);
        $school->addUserToSchool($p_account, $p_role);
    }

}

?>
