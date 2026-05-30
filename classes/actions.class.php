<?php
class Actions {
    private $conn;

    function __construct(){
        require_once(realpath(__DIR__.'/../db-connect.php'));
        $this->conn = $conn;
        $this->init_db();
    }

    private function init_db(){
        // Create users_tbl if it doesn't exist
        $this->conn->query("CREATE TABLE IF NOT EXISTS `users_tbl` (
          `id` int NOT NULL AUTO_INCREMENT,
          `username` varchar(50) NOT NULL UNIQUE,
          `password` text NOT NULL,
          `fullname` varchar(100) NOT NULL,
          `role` varchar(20) NOT NULL DEFAULT 'teacher',
          `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

        // Alter users_tbl to add avatar column if it doesn't exist
        $cols = $this->conn->query("SHOW COLUMNS FROM `users_tbl` LIKE 'avatar'");
        if($cols && $cols->num_rows == 0){
            $this->conn->query("ALTER TABLE `users_tbl` ADD COLUMN `avatar` varchar(255) DEFAULT NULL");
        }

        // Seed default user if table is empty
        $check = $this->conn->query("SELECT id FROM `users_tbl` LIMIT 1");
        if($check && $check->num_rows == 0){
            $password = password_hash('teacher123', PASSWORD_DEFAULT);
            $this->conn->query("INSERT INTO `users_tbl` (`username`, `password`, `fullname`, `role`) VALUES ('teacher', '{$password}', 'លោកគ្រូ វិបុល', 'teacher')");
        }
    }

    public function login(){
        extract($_POST);
        if(empty($username) || empty($password)){
            return ['status' => 'error', 'msg' => 'សូមបញ្ចូលឈ្មោះអ្នកប្រើប្រាស់ និងលេខសម្ងាត់!'];
        }
        $username = addslashes(htmlspecialchars($username));
        $check = $this->conn->query("SELECT * FROM `users_tbl` WHERE `username` = '{$username}'");
        if($check && $check->num_rows > 0){
            $user = $check->fetch_assoc();
            if(password_verify($password, $user['password'])){
                $_SESSION['user'] = $user;
                return ['status' => 'success'];
            }
        }
        return ['status' => 'error', 'msg' => 'ឈ្មោះអ្នកប្រើប្រាស់ ឬលេខសម្ងាត់ មិនត្រឹមត្រូវទេ!'];
    }

    public function logout(){
        if(isset($_SESSION['user'])){
            unset($_SESSION['user']);
        }
        $_SESSION['flashdata'] = ['type' => 'success', 'msg' => 'ចាកចេញពីប្រព័ន្ធដោយជោគជ័យ!'];
        return ['status' => 'success'];
    }

    public function get_user($id = ""){
        if(empty($id) && isset($_SESSION['user'])){
            $id = $_SESSION['user']['id'];
        }
        if(empty($id)) return null;
        $sql = "SELECT * FROM `users_tbl` where `id` = '{$id}'";
        $qry = $this->conn->query($sql);
        return $qry ? $qry->fetch_assoc() : null;
    }

    public function save_class(){
        foreach($_POST as $k => $v){
            if(!is_array($_POST[$k]) && !is_numeric($_POST[$k]) && !empty($_POST[$k])){
                $_POST[$k] = addslashes(htmlspecialchars($v));
            }
        }
        extract($_POST);

        if(!empty($id)){
            $check = $this->conn->query("SELECT id FROM `class_tbl` where `name` = '{$name}' and `id` != '{$id}' ");
            $sql = "UPDATE `class_tbl` set `name` = '{$name}' where `id` = '{$id}'";
        }else{
            $check = $this->conn->query("SELECT id FROM `class_tbl` where `name` = '{$name}' ");
            $sql = "INSERT `class_tbl` set `name` = '{$name}'";
        }
        if($check->num_rows > 0){
            return ['status' => 'error', 'msg' => 'Class Name Already Exists!'];
        }else{
            $qry = $this->conn->query($sql);
            if($qry){
                if(empty($id)){
                    $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "New Class has been added successfully!" ];
                }else{
                    $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "Class Data has been updated successfully!" ];
                }
                return ['status' => 'success'];
            }else{
                return ['status' => 'error', 'msg' => 'An error occurred!'];
            }
        }
    }

    public function delete_class(){
        extract($_POST);
        $delete = $this->conn->query("DELETE FROM `class_tbl` where id = '{$id}'");
        if($delete){
            $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "Class has been deleted successfully!" ];
            return ['status' => 'success'];
        }else{
            return ['status' => 'error', 'msg' => 'An error occurred!'];
        }
    }

    public function list_class(){
        $sql = "SELECT * FROM `class_tbl` order by name asc";
        $qry = $this->conn->query($sql);
        return $qry ? $qry->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function get_class($id){
        $sql = "SELECT * FROM `class_tbl` where id = '{$id}'";
        $qry = $this->conn->query($sql);
        return $qry ? $qry->fetch_assoc() : null;
    }

    public function save_student(){
        foreach($_POST as $k => $v){
            if(!is_array($_POST[$k]) && !is_numeric($_POST[$k]) && !empty($_POST[$k])){
                $_POST[$k] = addslashes(htmlspecialchars($v));
            }
        }
        extract($_POST);

        if(!empty($id)){
            $sql = "UPDATE `students_tbl` set `class_id` = '{$class_id}', `name` = '{$name}' where `id` = '{$id}'";
        }else{
            $sql = "INSERT `students_tbl` set `class_id` = '{$class_id}', `name` = '{$name}'";
        }
        $qry = $this->conn->query($sql);
        if($qry){
            if(empty($id)){
                $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "New Student has been added successfully!" ];
            }else{
                $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "Student Details has been updated successfully!" ];
            }
            return ['status' => 'success'];
        }else{
            return ['status' => 'error', 'msg' => 'An error occurred!'];
        }
    }

    public function delete_student(){
        extract($_POST);
        $delete = $this->conn->query("DELETE FROM `students_tbl` where id = '{$id}'");
        if($delete){
            $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "Student has been deleted successfully!" ];
            return ['status' => 'success'];
        }else{
            return ['status' => 'error', 'msg' => 'An error occurred!'];
        }
    }

    public function list_student(){
        $sql = "SELECT s.*, c.name as class FROM `students_tbl` s left join `class_tbl` c on s.class_id = c.id order by s.name asc";
        $qry = $this->conn->query($sql);
        return $qry ? $qry->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function get_student($id){
        $sql = "SELECT * FROM `students_tbl` where id = '{$id}'";
        $qry = $this->conn->query($sql);
        return $qry ? $qry->fetch_assoc() : null;
    }

    public function attendanceStudents($class_id, $class_date){
        if(empty($class_id) || empty($class_date)) return [];
        $sql = "SELECT s.*, a.status FROM `students_tbl` s LEFT JOIN `attendance_tbl` a ON s.id = a.student_id AND a.class_date = '{$class_date}' WHERE s.class_id = '{$class_id}' ORDER BY s.name ASC";
        $qry = $this->conn->query($sql);
        return $qry ? $qry->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function save_attendance(){
        extract($_POST);
        if(!isset($student_id) || !isset($status) || !isset($class_date)){
            return ['status' => 'error', 'msg' => 'Invalid data parameters!'];
        }
        foreach($student_id as $key => $sid){
            $stat = $status[$key];
            $check = $this->conn->query("SELECT id FROM `attendance_tbl` where student_id = '{$sid}' and class_date = '{$class_date}'");
            if($check && $check->num_rows > 0){
                $aid = $check->fetch_assoc()['id'];
                $this->conn->query("UPDATE `attendance_tbl` set status = '{$stat}' where id = '{$aid}'");
            }else{
                $this->conn->query("INSERT INTO `attendance_tbl` set student_id = '{$sid}', class_date = '{$class_date}', status = '{$stat}'");
            }
        }
        $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "Attendance Sheet has been saved successfully!" ];
        return ['status' => 'success'];
    }

    public function attendanceStudentsMonthly($class_id, $class_month){
        if(empty($class_id) || empty($class_month)) return [];
        $students_qry = $this->conn->query("SELECT * FROM `students_tbl` where class_id = '{$class_id}' order by name asc");
        if(!$students_qry) return [];
        $students = $students_qry->fetch_all(MYSQLI_ASSOC);
        
        foreach($students as $k => $row){
            $sid = $row['id'];
            $att_qry = $this->conn->query("SELECT class_date, status FROM `attendance_tbl` where student_id = '{$sid}' and class_date LIKE '{$class_month}-%'");
            $attendance = [];
            if($att_qry){
                while($att = $att_qry->fetch_assoc()){
                    $attendance[$att['class_date']] = $att['status'];
                }
            }
            $students[$k]['attendance'] = $attendance;
        }
        return $students;
    }

    public function get_dashboard_stats(){
        $stats = [];
        
        $classes_qry = $this->conn->query("SELECT count(id) as count FROM `class_tbl`");
        $stats['total_classes'] = $classes_qry ? $classes_qry->fetch_assoc()['count'] : 0;
        
        $students_qry = $this->conn->query("SELECT count(id) as count FROM `students_tbl`");
        $stats['total_students'] = $students_qry ? $students_qry->fetch_assoc()['count'] : 0;
        
        $date_qry = $this->conn->query("SELECT max(class_date) as max_date FROM `attendance_tbl`");
        $latest_date = ($date_qry && $date_qry->num_rows > 0) ? $date_qry->fetch_assoc()['max_date'] : null;
        $stats['latest_date'] = $latest_date;
        
        $stats['attendance_breakdown'] = ['total' => 0, 'present' => 0, 'late' => 0, 'absent' => 0];
        if(!empty($latest_date)){
            $breakdown_qry = $this->conn->query("SELECT status, count(id) as count FROM `attendance_tbl` where class_date = '{$latest_date}' group by status");
            if($breakdown_qry){
                while($row = $breakdown_qry->fetch_assoc()){
                    $status = (int)$row['status'];
                    $count = (int)$row['count'];
                    $stats['attendance_breakdown']['total'] += $count;
                    if ($status === 1) {
                        $stats['attendance_breakdown']['present'] = $count;
                    } elseif ($status === 2) {
                        $stats['attendance_breakdown']['late'] = $count;
                    } elseif ($status === 3) {
                        $stats['attendance_breakdown']['absent'] = $count;
                    }
                }
            }
        }

        $class_list = [];
        $class_list_query = $this->conn->query("SELECT c.`id`, c.`name`, COUNT(s.`id`) as `student_count` FROM `class_tbl` c LEFT JOIN `students_tbl` s ON c.`id` = s.`class_id` GROUP BY c.`id` ORDER BY c.`name` ASC");
        if ($class_list_query) {
            $class_list = $class_list_query->fetch_all(MYSQLI_ASSOC);
        }
        $stats['class_list'] = $class_list;
        
        $recent_sessions = [];
        $recent_query = $this->conn->query("SELECT a.`class_date`, c.`name` as `class_name`, c.`id` as `class_id`, COUNT(DISTINCT a.`student_id`) as `total_students`, SUM(CASE WHEN a.`status` = 1 THEN 1 ELSE 0 END) as `present_count`, SUM(CASE WHEN a.`status` = 2 THEN 1 ELSE 0 END) as `late_count`, SUM(CASE WHEN a.`status` = 3 THEN 1 ELSE 0 END) as `absent_count` FROM `attendance_tbl` a JOIN `students_tbl` s ON a.`student_id` = s.`id` JOIN `class_tbl` c ON s.`class_id` = c.`id` GROUP BY a.`class_date`, c.`id` ORDER BY a.`class_date` DESC, c.`name` ASC LIMIT 5");
        if ($recent_query) {
            $recent_sessions = $recent_query->fetch_all(MYSQLI_ASSOC);
        }
        $stats['recent_sessions'] = $recent_sessions;
        
        return $stats;
    }

    public function update_profile(){
        foreach($_POST as $k => $v){
            if(!is_array($_POST[$k]) && !is_numeric($_POST[$k]) && !empty($_POST[$k])){
                $_POST[$k] = addslashes(htmlspecialchars($v));
            }
        }
        extract($_POST);

        if(!isset($_SESSION['user']['id'])){
            return ['status' => 'error', 'msg' => 'សូមចូលប្រព័ន្ធជាមុនសិន!'];
        }
        $id = $_SESSION['user']['id'];

        if(empty($fullname)){
            return ['status' => 'error', 'msg' => 'សូមបញ្ចូលឈ្មោះពេញ!'];
        }

        if(!empty($old_password) && !empty($new_password)){
            $check = $this->conn->query("SELECT password FROM `users_tbl` WHERE id = '{$id}'");
            if($check && $check->num_rows > 0){
                $user = $check->fetch_assoc();
                if(password_verify($old_password, $user['password'])){
                    $password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                    $sql = "UPDATE `users_tbl` SET `fullname` = '{$fullname}', `password` = '{$password_hashed}' WHERE id = '{$id}'";
                } else {
                    return ['status' => 'error', 'msg' => 'លេខសម្ងាត់ចាស់មិនត្រឹមត្រូវឡើយ!'];
                }
            } else {
                return ['status' => 'error', 'msg' => 'រកមិនឃើញគណនីរបស់អ្នកទេ!'];
            }
        } else {
            $sql = "UPDATE `users_tbl` SET `fullname` = '{$fullname}' WHERE id = '{$id}'";
        }

        $qry = $this->conn->query($sql);
        if($qry){
            if(isset($_FILES['avatar']) && $_FILES['avatar']['tmp_name'] != ''){
                $filename = time() . '_' . $_FILES['avatar']['name'];
                $upload_dir = __DIR__.'/../assets/uploads/avatars';
                if(!is_dir($upload_dir)){
                    mkdir($upload_dir, 0777, true);
                }
                $upload_path = $upload_dir . '/' . $filename;
                
                $resized = $this->resize_image($_FILES['avatar']['tmp_name'], $upload_path, 300, 300);
                
                if(!$resized){
                    move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_path);
                }
                
                $avatar_db_path = "assets/uploads/avatars/" . $filename;
                $this->conn->query("UPDATE `users_tbl` SET `avatar` = '{$avatar_db_path}' WHERE id = '{$id}'");
                $_SESSION['user']['avatar'] = $avatar_db_path;
            }

            $_SESSION['user']['fullname'] = $fullname;
            $_SESSION['flashdata'] = ['type' => 'success', 'msg' => 'ព័ត៌មានផ្ទាល់ខ្លួនត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ!'];
            return ['status' => 'success'];
        } else {
            return ['status' => 'error', 'msg' => 'មានកំហុសក្នុងការរក្សាទុកទិន្នន័យ!'];
        }
    }

    private function resize_image($source_path, $target_path, $max_width = 300, $max_height = 300){
        if(!extension_loaded('gd') && !function_exists('gd_info')){
            return false;
        }

        list($orig_width, $orig_height, $image_type) = @getimagesize($source_path);
        if(!$orig_width || !$orig_height){
            return false;
        }
        
        if ($orig_width <= $max_width && $orig_height <= $max_height) {
            if ($source_path !== $target_path) {
                return copy($source_path, $target_path);
            }
            return true;
        }

        $aspect_ratio = $orig_width / $orig_height;
        if ($max_width / $max_height > $aspect_ratio) {
            $new_width = $max_height * $aspect_ratio;
            $new_height = $max_height;
        } else {
            $new_width = $max_width;
            $new_height = $max_width / $aspect_ratio;
        }

        $new_image = imagecreatetruecolor($new_width, $new_height);
        if(!$new_image) return false;

        switch ($image_type) {
            case IMAGETYPE_GIF:
                $source_image = @imagecreatefromgif($source_path);
                break;
            case IMAGETYPE_JPEG:
                $source_image = @imagecreatefromjpeg($source_path);
                break;
            case IMAGETYPE_PNG:
                $source_image = @imagecreatefrompng($source_path);
                imagealphablending($new_image, false);
                imagesavealpha($new_image, true);
                break;
            case IMAGETYPE_WEBP:
                if (function_exists('imagecreatefromwebp')) {
                    $source_image = @imagecreatefromwebp($source_path);
                } else {
                    $source_image = false;
                }
                break;
            default:
                $source_image = false;
                break;
        }
        
        if (!$source_image) {
            imagedestroy($new_image);
            return false;
        }
        
        imagecopyresampled($new_image, $source_image, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height);
        
        $saved = false;
        switch ($image_type) {
            case IMAGETYPE_GIF:
                $saved = imagegif($new_image, $target_path);
                break;
            case IMAGETYPE_JPEG:
                $saved = imagejpeg($new_image, $target_path, 80);
                break;
            case IMAGETYPE_PNG:
                $saved = imagepng($new_image, $target_path, 8);
                break;
            case IMAGETYPE_WEBP:
                $saved = imagewebp($new_image, $target_path, 75);
                break;
        }
        
        imagedestroy($new_image);
        imagedestroy($source_image);
        return $saved;
    }

    function __destruct()
    {
        if($this->conn)
        $this->conn->close(); 
    }
}
