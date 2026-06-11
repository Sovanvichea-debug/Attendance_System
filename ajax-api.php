<?php 
session_start();
require_once('classes/actions.class.php');
$actionClass = new Actions();
$action = $_GET['action'] ?? "";
$response = [];
switch($action){
    case 'save_class':
        $response = $actionClass->save_class();
        break;
    case 'delete_class':
        $response = $actionClass->delete_class();
        break;
    case 'save_student':
        $response = $actionClass->save_student();
        break;
    case 'import_students':
        $response = $actionClass->import_students();
        break;
    case 'delete_student':
        $response = $actionClass->delete_student();
        break;
    case 'reset_student_ids':
        $response = $actionClass->reset_student_ids();
        break;
    case 'save_attendance':
        $response = $actionClass->save_attendance();
        break;
    case 'login':
        $response = $actionClass->login();
        break;
    case 'logout':
        $response = $actionClass->logout();
        break;
    case 'update_profile':
        $response = $actionClass->update_profile();
        break;
    case 'save_settings':
        $response = $actionClass->save_settings();
        break;
    case 'student_qr_scan':
        $response = $actionClass->student_qr_scan();
        break;
    case 'get_live_attendance':
        $class_id = $_GET['class_id'] ?? '';
        $class_date = $_GET['class_date'] ?? '';
        $response = $actionClass->attendanceStudents($class_id, $class_date);
        break;
    default:
        $response = ["status" => "error", "msg" => "Undefined API Action!"];
        break;
}

echo json_encode($response);
?>