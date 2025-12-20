# ZKTeco Attendance Integration Plan

The goal is to enable the HRM system to sync attendance data directly from ZKTeco biometric devices. Since Composer is not available, we will implement a standalone PHP library for device communication.

## User Review Required

> [!IMPORTANT] > **Network Requirement**: The hosting server must have network access to the ZKTeco device. If the site is hosted on a live server (Hostinger) and the device is in a local office behind a NAT/Firewall, a **VPN** or **Port Forwarding** is required, OR the device must support **ADMS** (Push Data) to a public URL.
>
> This plan assumes a **Direct Sync** approach (Pull) which works best if the server and device are on the same network (e.g., local XAMPP setup) or properly routed.

## Proposed Changes

### 1. New Library: `Zkteco_lib`

**Location:** `application/libraries/Zkteco_lib.php`

- Implement a class to handle UDP/TCP connection to ZKTeco devices.
- Methods: `connect()`, `disconnect()`, `getAttendance()`, `clearAttendance()`.
- Based on standard ZK protocol (zklib).

### 2. New Controller: `Zkteco`

**Location:** `application/controllers/Zkteco.php`

- `index()`: Show configuration page (Device IP, Port).
- `sync()`: Connect to device, fetch logs, and insert into database.
- `test_connection()`: Verify connectivity.

### 3. Database Updates

- No schema changes expected if we map ZK data to existing `xin_attendance_time` table.
- We might need a settings table for ZK Device IP/Port, or just store it in the existing `xin_system_setting` or a new `xin_zkteco_settings` table.
  - _Decision_: For simplicity, we'll create a small configuration file or add columns to `xin_system_setting`. Let's add a new table `xin_zkteco_settings` for cleanliness.

#### [NEW] Table: `xin_zkteco_settings`

```sql
CREATE TABLE `xin_zkteco_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_ip` varchar(50) NOT NULL,
  `device_port` int(11) NOT NULL DEFAULT '4370',
  `last_sync` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

### 4. Views

**Location:** `application/views/zkteco/`

- `dialog_zkteco.php`: Modal for settings.
- `zkteco_list.php`: Page to view device status and trigger sync.

### 5. Model Updates

**Location:** `application/models/Timesheet_model.php`

- Add `check_existing_attendance($employee_id, $date, $time)` to prevent duplicate entries during sync.

## Verification Plan

### Manual Verification

1.  **Test Connection**:
    - Go to ZKTeco settings page.
    - Enter a dummy IP (or real one if available).
    - Click "Test Connection".
    - _Expected_: Success or Timeout message.
2.  **Sync Data**:
    - Click "Sync Attendance".
    - _Expected_: System fetches logs (mocked if no device) and inserts them into `xin_attendance_time`.
    - Verify in "Attendance List" that new records appear.
