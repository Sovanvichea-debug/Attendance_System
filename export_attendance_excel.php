<?php
session_start();
require_once('classes/actions.class.php');

// Session check
if(!isset($_SESSION['user'])){
    header('Location: ./?page=login');
    exit;
}

$actionClass = new Actions();
$class_id = $_GET['class_id'] ?? "";
$class_month = $_GET['class_month'] ?? "";

if(empty($class_id) || empty($class_month)){
    die("Invalid request parameters.");
}

$classData = $actionClass->get_class($class_id);
if(!$classData){
    die("Class not found.");
}
$className = $classData['name'];

$studentList = $actionClass->attendanceStudentsMonthly($class_id, $class_month);
$monthLastDay = date("t", strtotime("{$class_month}-01")) ;
$monthName = date("F Y", strtotime($class_month));

// Clean output buffer
if (ob_get_level()) {
    ob_end_clean();
}

// Headers to force download as excel (HTML format with .xls extension)
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Attendance_Report_" . str_replace(" ", "_", $className) . "_" . $class_month . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// HTML header with styling to display Khmer text properly and styled spreadsheet
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--[if gte mso 9]>
<xml>
  <x:ExcelWorkbook>
    <x:ExcelWorksheets>
      <x:ExcelWorksheet>
        <x:Name>Attendance Report</x:Name>
        <x:WorksheetOptions>
          <x:DisplayGridlines/>
        </x:WorksheetOptions>
      </x:ExcelWorksheet>
    </x:ExcelWorksheets>
  </x:ExcelWorkbook>
</xml>
<![endif]-->
<style>
  body {
      font-family: 'Segoe UI', 'Khmer OS Battambang', 'Arial', sans-serif;
  }
  table {
      border-collapse: collapse;
      width: 100%;
  }
  th, td {
      border: 1px solid #cbd5e1;
      padding: 8px 10px;
      text-align: center;
      font-size: 11pt;
  }
  th {
      background-color: #4f46e5;
      color: #ffffff;
      font-weight: bold;
  }
  .title-header {
      font-size: 16pt;
      font-weight: bold;
      color: #1e1b4b;
      text-align: left;
  }
  .meta-header {
      font-size: 11pt;
      color: #475569;
      text-align: left;
  }
  .student-name {
      text-align: left;
      font-weight: bold;
      background-color: #f8fafc;
  }
  /* Colors for different attendance statuses */
  .status-p {
      background-color: #d1fae5;
      color: #065f46;
      font-weight: bold;
  }
  .status-l {
      background-color: #fef3c7;
      color: #92400e;
      font-weight: bold;
  }
  .status-a {
      background-color: #fee2e2;
      color: #991b1b;
      font-weight: bold;
  }
  .status-e {
      background-color: #e0f2fe;
      color: #075985;
      font-weight: bold;
  }
  .status-none {
      color: #94a3b8;
  }
  .summary-hdr {
      background-color: #e2e8f0;
      color: #0f172a;
      font-weight: bold;
  }
  .summary-val {
      background-color: #f1f5f9;
      font-weight: bold;
      color: #0f172a;
  }
  .legend-table {
      margin-top: 20px;
      border-collapse: collapse;
  }
  .legend-table td {
      border: 1px solid #cbd5e1;
      padding: 6px 12px;
      font-size: 10pt;
  }
  .legend-title {
      font-weight: bold;
      background-color: #f8fafc;
  }
</style>
</head>
<body>

  <!-- Report Header -->
  <table>
    <tr>
      <td colspan="<?= $monthLastDay + 5 ?>" class="title-header" style="border: none; text-align: left;">របាយការណ៍វត្តមានប្រចាំខែ / Monthly Attendance Report</td>
    </tr>
    <tr>
      <td colspan="<?= $monthLastDay + 5 ?>" class="meta-header" style="border: none; text-align: left;">
        <strong>ថ្នាក់ / Class:</strong> <?= htmlspecialchars($className) ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <strong>ខែ / Month:</strong> <?= date("F Y", strtotime($class_month)) ?>
      </td>
    </tr>
    <tr style="height: 15px;"><td colspan="<?= $monthLastDay + 5 ?>" style="border: none;"></td></tr>
  </table>

  <!-- Report Table -->
  <table>
    <thead>
      <tr>
        <th style="min-width: 200px; text-align: left;">សិស្ស / Students</th>
        <?php for($i=1; $i <= $monthLastDay; $i++): ?>
          <th><?= $i ?></th>
        <?php endfor; ?>
        <th class="summary-hdr">TP</th>
        <th class="summary-hdr">TL</th>
        <th class="summary-hdr">TA</th>
        <th class="summary-hdr">TE</th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($studentList) && is_array($studentList)): ?>
        <?php foreach($studentList as $row): ?>
          <tr>
            <td class="student-name"><?= htmlspecialchars($row['name']) ?></td>
            <?php 
            $tp = 0;
            $tl = 0;
            $ta = 0;
            $te = 0;
            ?>
            <?php for($i=1; $i <= $monthLastDay; $i++): ?>
              <?php 
                $day_key = $class_month . "-" . str_pad($i, 2, "0", STR_PAD_LEFT);
                $status = $row['attendance'][$day_key] ?? null;
                switch($status){
                    case 1:
                        echo "<td class='status-p'>P</td>";
                        $tp += 1;
                        break;
                    case 2:
                        echo "<td class='status-l'>L</td>";
                        $tl += 1;
                        break;
                    case 3:
                        echo "<td class='status-a'>A</td>";
                        $ta += 1;
                        break;
                    case 4:
                        echo "<td class='status-e'>E</td>";
                        $te += 1;
                        break;
                    default:
                        echo "<td class='status-none'>-</td>";
                }
              ?>
            <?php endfor; ?>
            <td class="summary-val"><?= $tp ?></td>
            <td class="summary-val"><?= $tl ?></td>
            <td class="summary-val"><?= $ta ?></td>
            <td class="summary-val"><?= $te ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="<?= $monthLastDay + 5 ?>">No student records found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <!-- Legend Section -->
  <br>
  <table class="legend-table" style="width: auto;">
    <tr>
      <td class="legend-title" colspan="2">Legend / សម្គាល់</td>
    </tr>
    <tr>
      <td class="status-p" style="width: 40px;">P</td>
      <td>វត្តមាន / Present</td>
    </tr>
    <tr>
      <td class="status-l" style="width: 40px;">L</td>
      <td>យឺត / Late</td>
    </tr>
    <tr>
      <td class="status-a" style="width: 40px;">A</td>
      <td>អវត្តមាន / Absent</td>
    </tr>
    <tr>
      <td class="status-e" style="width: 40px;">E</td>
      <td>ច្បាប់ / Excused</td>
    </tr>
  </table>

</body>
</html>
