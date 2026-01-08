<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Jmrashed\Zkteco\Lib\ZKTeco;
use Jmrashed\Zkteco\Lib\Helper\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ZkAttendance extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->database();
        $this->load->library('form_validation');
        //load the models
        $this->load->model('Xin_model');

        // Load Vendor Autoload
        require_once FCPATH . 'attendance_app/vendor/autoload.php';
    }

    public function index()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $data['breadcrumbs'] = 'Attendance App';
        $data['path_url'] = 'zk_attendance';
        $role_resources_ids = $this->Xin_model->user_role_resource();

        $data['subview'] = $this->load->view('zk_attendance/index', $data, TRUE);
        $this->load->view('layout_main', $data); //page load
    }

    public function connect()
    {
        // Clean buffer
        if (ob_get_length())
            ob_clean();
        header('Content-Type: application/json');

        // Increase memory and time for large logs
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $ip = $this->input->get('ip');
        $port = 4370; // Default port

        if (empty($ip)) {
            echo json_encode(['success' => false, 'message' => 'IP Address is required.']);
            return;
        }

        $zk = new ZKTeco($ip, $port);

        if ($zk->connect()) {
            try {
                // 1. Fetch Users
                $users = User::get($zk);

                // 2. Fetch Attendance
                $attendance_logs = $zk->getAttendance();

                $zk->disconnect();

                // 3. Save to Cache
                $cacheDir = FCPATH . 'attendance_app/cache';
                if (!is_dir($cacheDir)) {
                    mkdir($cacheDir, 0777, true);
                }

                file_put_contents($cacheDir . '/users.json', json_encode($users));
                file_put_contents($cacheDir . '/attendance_logs.json', json_encode($attendance_logs));

                $userCount = count($users);
                $logCount = count($attendance_logs);

                echo json_encode([
                    'success' => true,
                    'message' => "Synced successfully! (Users: $userCount, Logs: $logCount)"
                ]);

            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Sync failed: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Connection failed. Please check IP and network.']);
        }
    }

    public function get_users()
    {
        // Clean buffer to remove any previous output
        if (ob_get_length())
            ob_clean();
        header('Content-Type: application/json');

        $cacheDir = FCPATH . 'attendance_app/cache';
        $usersFile = $cacheDir . '/users.json';

        if (file_exists($usersFile)) {
            $content = file_get_contents($usersFile);
            // Check if file content is valid JSON
            $users = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['success' => false, 'message' => 'JSON Decode Error: ' . json_last_error_msg()]);
                return;
            }

            // Re-encode to ensure clean output, handling invalid UTF-8
            $options = defined('JSON_INVALID_UTF8_SUBSTITUTE') ? JSON_INVALID_UTF8_SUBSTITUTE : 0;
            echo json_encode(['success' => true, 'users' => $users], $options);
        } else {
            echo json_encode(['success' => false, 'message' => 'No users found in cache. Please sync first.']);
        }
    }

    public function export()
    {
        // Increase memory and time for large logs
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $start_date = $this->input->get('start_date');
        if (empty($start_date)) {
            $start_date = date('Y-m-d');
        }

        $end_date = $this->input->get('end_date');
        if (empty($end_date)) {
            $end_date = date('Y-m-d');
        }

        $filter_user_id = $this->input->get('user_id');
        if (empty($filter_user_id)) {
            $filter_user_id = '';
        }

        // Validate dates
        if (!$start_date || !$end_date) {
            die("Please select both Start Date and End Date.");
        }

        // Load Data from Cache
        $cacheDir = FCPATH . 'attendance_app/cache';
        $usersFile = $cacheDir . '/users.json';
        $attendanceFile = $cacheDir . '/attendance_logs.json';

        if (!file_exists($usersFile) || !file_exists($attendanceFile)) {
            die("<h1>No Data Found</h1><p>Please go back and click 'Connect & Sync' first.</p>");
        }

        $users = json_decode(file_get_contents($usersFile), true);
        $attendance_logs = json_decode(file_get_contents($attendanceFile), true);

        if (empty($users)) {
            die("<h1>No Users Found</h1><p>User cache is empty.</p>");
        }

        // Initialize Grouped Data for ALL Users
        $groupedData = [];

        // If a specific user is selected, only include that user
        $targetUsers = $users;
        if (!empty($filter_user_id)) {
            // Find the specific user in the array (array keys might not match userid directly if not indexed by it)
            $found = false;
            foreach ($users as $key => $u) {
                if ($u['userid'] == $filter_user_id) {
                    $targetUsers = [$key => $u];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                die("<h1>User Not Found</h1><p>Selected user ID does not exist in cache.</p>");
            }
        }

        // Generate date range
        $period = new DatePeriod(
            new DateTime($start_date),
            new DateInterval('P1D'),
            (new DateTime($end_date))->modify('+1 day')
        );

        // Initialize structure for every target user for every date
        foreach ($targetUsers as $u) {
            $uid = $u['userid'];
            $groupedData[$uid] = [];
            foreach ($period as $dt) {
                $dateStr = $dt->format('Y-m-d');
                $groupedData[$uid][$dateStr] = [
                    'name' => $u['name'],
                    'punches' => [],
                    'check_in' => null,
                    'check_out' => null,
                    'status' => 'Absent' // Default status
                ];
            }
        }

        // Fill in Attendance Data
        if (!empty($attendance_logs)) {
            $sample = reset($attendance_logs);
            $timeKey = isset($sample['timestamp']) ? 'timestamp' : (isset($sample['time']) ? 'time' : 'datetime');

            foreach ($attendance_logs as $log) {
                $timestamp = $log[$timeKey];
                $date = date('Y-m-d', strtotime($timestamp));
                $uid = $log['id'];

                // Skip if user is not in our target list (e.g. filtered out)
                if (!isset($groupedData[$uid])) {
                    continue;
                }
                // Skip if date is out of range
                if (!isset($groupedData[$uid][$date])) {
                    continue;
                }

                $groupedData[$uid][$date]['punches'][] = $timestamp;
                $groupedData[$uid][$date]['status'] = 'Present'; // Mark as present if any punch exists
            }
        }

        // Generate Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['User ID', 'Name', 'Date', 'Day', 'Check-In', 'Check-Out', 'Work Hours', 'All Punches', 'Status'];
        $sheet->fromArray($headers, NULL, 'A1');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

        $row = 2;

        // Sort by User ID
        ksort($groupedData);

        foreach ($groupedData as $uid => $dates) {
            // Sort by Date
            ksort($dates);

            foreach ($dates as $date => $data) {
                $punches = $data['punches'];
                sort($punches);

                $checkIn = null;
                $checkOut = null;
                $workHours = '';
                $status = $data['status'];

                if (!empty($punches)) {
                    $checkIn = $punches[0];
                    $checkOut = end($punches);

                    if (count($punches) == 1) {
                        $checkOut = null;
                        $status = 'Missing Out Punch';
                    }

                    if ($checkIn && $checkOut && $checkIn != $checkOut) {
                        $t1 = strtotime($checkIn);
                        $t2 = strtotime($checkOut);
                        $diff = $t2 - $t1;
                        $hours = floor($diff / 3600);
                        $minutes = floor(($diff % 3600) / 60);
                        $workHours = sprintf("%02d:%02d", $hours, $minutes);
                    }
                }

                $fmtCheckIn = $checkIn ? date('h:i A', strtotime($checkIn)) : '';
                $fmtCheckOut = $checkOut ? date('h:i A', strtotime($checkOut)) : '';
                $fmtPunches = array_map(function ($t) {
                    return date('h:i A', strtotime($t));
                }, $punches);
                $allPunchesStr = implode(', ', $fmtPunches);
                $dayName = date('l', strtotime($date));

                $sheet->setCellValue('A' . $row, $uid);
                $sheet->setCellValue('B' . $row, $data['name']);
                $sheet->setCellValue('C' . $row, $date);
                $sheet->setCellValue('D' . $row, $dayName);
                $sheet->setCellValue('E' . $row, $fmtCheckIn);
                $sheet->setCellValue('F' . $row, $fmtCheckOut);
                $sheet->setCellValue('G' . $row, $workHours);
                $sheet->setCellValue('H' . $row, $allPunchesStr);
                $sheet->setCellValue('I' . $row, $status);

                // Highlight Absent/Missing rows
                if ($status == 'Absent') {
                    $sheet->getStyle('A' . $row . ':I' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED));
                } elseif ($status == 'Missing Out Punch') {
                    $sheet->getStyle('I' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKRED));
                }

                $row++;
            }
        }

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        if ($row > 2) {
            $sheet->getStyle('A1:I' . ($row - 1))->applyFromArray($styleArray);
        }

        $filename = "Attendance_Report_" . $start_date . "_to_" . $end_date . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
