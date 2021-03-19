<?php

//getting the dboperation class
require_once '../includes/DbOperation.php';

//function validating all the paramters are available
//we will pass the required parameters to this function
function isTheseParametersAvailable($params)
{
    //assuming all parameters are available
    $available = true;
    $missingparams = "";

    foreach ($params as $param) {
        if (!isset($_POST[$param]) || strlen($_POST[$param]) <= 0) {
            $available = false;
            $missingparams = $missingparams . ", " . $param;
        }
    }

    //if parameters are missing
    if (!$available) {
        $response = array();
        $response['error'] = true;
        $response['message'] = 'Parameters ' . substr($missingparams, 1, strlen($missingparams)) . ' missing';

        //displaying error
        echo json_encode($response);

        //stopping further execution
        die();
    }
}

//an array to display response
$response = array();

//if it is an api call
//that means a get parameter named api call is set in the URL
//and with this parameter we are concluding that it is an api call
if (isset($_GET['apicall'])) {

    switch ($_GET['apicall']) {
        /*-----------------------------------------------Student Register---------------------------------------------------*/
        case 'sturegister':
            isTheseParametersAvailable(array('s_name', 's_enroll_no', 's_phone', 's_address', 's_email', 's_password', 's_city', 's_state', 's_semester', 's_department', 's_course'));
            $db = new DbOperation();
            $result = $db->StudentRegistration($_POST['s_name'], $_POST['s_enroll_no'], $_POST['s_phone'], $_POST['s_address'], $_POST['s_email'], $_POST['s_password'], $_POST['s_city'], $_POST['s_state'], $_POST['s_semester'], $_POST['s_department'], $_POST['s_course']);

            if ($result) {
                $response['error'] = true;
                $response['message'] = "Registeration Successfull";
            } else {
                $response['error'] = true;
                $response['message'] = "Error Occured In Registeration";
            }
            break;
        /*-----------------------------------------------Student Register---------------------------------------------------*/
        case 'teachregister':
            isTheseParametersAvailable(array('t_name', 't_phone', 't_address', 't_email', 't_password', 't_city', 't_state', 't_department', 't_course'));
            $db = new DbOperation();
            $result = $db->TeacherRegistration($_POST['t_name'], $_POST['t_phone'], $_POST['t_address'], $_POST['t_email'], $_POST['t_password'], $_POST['t_city'], $_POST['t_state'], $_POST['t_department'], $_POST['t_course']);

            if ($result) {
                $response['error'] = true;
                $response['message'] = "Registeration Successfull";
            } else {
                $response['error'] = true;
                $response['message'] = "Error Occured In Registeration";
            }
            break;
        /*-----------------------------------------------Student Login---------------------------------------------------*/

        case 'studentlogin':

            isTheseParametersAvailable(array('s_email', 's_pwd'));
            $db = new DbOperation();
            $result = $db->StudentLogin($_POST['s_email'], $_POST['s_pwd']);


            if ($result) {
                $response['error'] = false;
                $response['message'] = 'Login successfully';
                $response['student'] = $db->getStudentData($_POST['s_email'], $_POST['s_pwd']);


            } else {

                $response['error'] = true;
                $response['message'] = 'email or Password is Invalid';

            }
            break;
        /*-----------------------------------------------Parent Login---------------------------------------------------*/

        case 'teacherlogin':

            isTheseParametersAvailable(array('t_email', 't_pwd'));
            $db = new DbOperation();
            $result = $db->TeacherLogin($_POST['t_email'], $_POST['t_pwd']);


            if ($result) {

                $response['error'] = false;
                $response['message'] = 'Login successfully';
                $response['teacher'] = $db->getTeacherData($_POST['t_email'], $_POST['t_pwd']);

            } else {

                $response['error'] = true;
                $response['message'] = 'email or Password is Invalid';

            }
            break;
        /*----------------------------------------------- Time Table DATA---------------------------------------------------*/

        case 'time_table':

            isTheseParametersAvailable(array('day', 'department', 'course'));
            $db = new DbOperation();


            $response['error'] = false;
            $response['message'] = 'Data Fetched';
            $response['timetable'] = $db->getTimeTabedata($_POST['day'], $_POST['department'], $_POST['course']);


            break;
        /*-----------------------------------------------Department DATA---------------------------------------------------*/
        case 'department':
//            isTheseParametersAvailable(array('dummy'));

            $db = new DbOperation();
            $response['error'] = false;
            $response['message'] = 'Data Fetched';
            $response['department'] = $db->getDepartmentData();

            break;
        /*----------------------------------------------- Course Data---------------------------------------------------*/
        case 'course':

            isTheseParametersAvailable(array('dep_id'));
            $db = new DbOperation();

            $response['error'] = false;
            $response['message'] = 'Data Fetched';
            $response['course'] = $db->getCourseData($_POST['dep_id']);

            break;
        /*----------------------------------------------- Semester Data---------------------------------------------------*/
        case 'sem':
            isTheseParametersAvailable(array('dep_id', 'c_id'));
            $db = new DbOperation();

            $response['error'] = false;
            $response['message'] = false;
            $response['semester'] = $db->getSemesterdata($_POST['dep_id'], $_POST['c_id']);

            break;
    }

} else {
    //if it is not api call
    //pushing appropriate values to response array
    $response['error'] = true;
    $response['message'] = 'Invalid API Call';
}

//displaying the response in json structure
echo json_encode($response);