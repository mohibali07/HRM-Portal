-- ---------------------------------------------------------
		--
		-- SIMPLE SQL Dump
		-- 
		-- nawa (at) yahoo (dot) com
		--
		-- Host Connection Info: Localhost via UNIX socket
		-- Generation Time: May 15, 2018 at 20:39 PM ( Asia/Kolkata )
		-- PHP Version: 7.0.30
		--
		-- ---------------------------------------------------------


		SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
		SET time_zone = "+00:00";
		/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
		/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
		/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
		/*!40101 SET NAMES utf8 */;
		

		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_advance_salaries`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_advance_salaries` (
  `advance_salary_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `month_year` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `advance_amount` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `one_time_deduct` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `monthly_installment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `total_paid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `reason` text COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `is_deducted_from_salary` int(11) NOT NULL,
  `created_at` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`advance_salary_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_announcements`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_announcements` (
  `announcement_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `start_date` varchar(200) NOT NULL,
  `end_date` varchar(200) NOT NULL,
  `company_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `published_by` int(11) NOT NULL,
  `summary` text NOT NULL,
  `description` text NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`announcement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_attendance_time`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_attendance_time` (
  `time_attendance_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `attendance_date` varchar(255) NOT NULL,
  `clock_in` varchar(255) NOT NULL,
  `clock_out` varchar(255) NOT NULL,
  `clock_in_out` varchar(255) NOT NULL,
  `time_late` varchar(255) NOT NULL,
  `early_leaving` varchar(255) NOT NULL,
  `overtime` varchar(255) NOT NULL,
  `total_work` varchar(255) NOT NULL,
  `total_rest` varchar(255) NOT NULL,
  `attendance_status` varchar(100) NOT NULL,
  PRIMARY KEY (`time_attendance_id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_award_type`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_award_type` (
  `award_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `award_type` varchar(200) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`award_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_awards`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_awards` (
  `award_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `award_type_id` int(11) NOT NULL,
  `gift_item` varchar(200) NOT NULL,
  `cash_price` varchar(200) NOT NULL,
  `award_photo` varchar(255) NOT NULL,
  `award_month_year` varchar(200) NOT NULL,
  `award_information` text NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`award_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_companies`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_companies` (
  `company_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `trading_name` varchar(255) NOT NULL,
  `registration_no` varchar(255) NOT NULL,
  `government_tax` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `website_url` varchar(255) NOT NULL,
  `address_1` text NOT NULL,
  `address_2` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `country` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_company_info`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_company_info` (
  `company_info_id` int(11) NOT NULL AUTO_INCREMENT,
  `logo` varchar(255) NOT NULL,
  `logo_second` varchar(255) NOT NULL,
  `sign_in_logo` varchar(255) NOT NULL,
  `favicon` varchar(255) NOT NULL,
  `website_url` text NOT NULL,
  `starting_year` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_email` varchar(255) NOT NULL,
  `company_contact` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address_1` text NOT NULL,
  `address_2` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `country` int(11) NOT NULL,
  `updated_at` varchar(255) NOT NULL,
  PRIMARY KEY (`company_info_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_company_policy`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_company_policy` (
  `policy_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`policy_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_company_type`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_company_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_contract_type`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_contract_type` (
  `contract_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`contract_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_countries`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_countries` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(255) NOT NULL,
  `country_name` varchar(255) NOT NULL,
  `country_flag` varchar(255) NOT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=MyISAM AUTO_INCREMENT=246 DEFAULT CHARSET=utf8;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_currencies`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_currencies` (
  `currency_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `symbol` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`currency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_database_backup`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_database_backup` (
  `backup_id` int(11) NOT NULL AUTO_INCREMENT,
  `backup_file` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`backup_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_departments`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_departments` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_name` varchar(200) NOT NULL,
  `company_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_designations`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_designations` (
  `designation_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(11) NOT NULL,
  `designation_name` varchar(200) NOT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`designation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_document_type`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_document_type` (
  `document_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `document_type` varchar(255) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`document_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_email_template`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_email_template` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(2) NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_bankaccount`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_bankaccount` (
  `bankaccount_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `is_primary` int(11) NOT NULL,
  `account_title` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `bank_code` varchar(255) NOT NULL,
  `bank_branch` text NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`bankaccount_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_complaints`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_complaints` (
  `complaint_id` int(11) NOT NULL AUTO_INCREMENT,
  `complaint_from` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `complaint_date` varchar(255) NOT NULL,
  `complaint_against` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(2) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`complaint_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_contacts`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_contacts` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `relation` varchar(255) NOT NULL,
  `is_primary` int(11) NOT NULL,
  `is_dependent` int(11) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `work_phone` varchar(255) NOT NULL,
  `work_phone_extension` varchar(255) NOT NULL,
  `mobile_phone` varchar(255) NOT NULL,
  `home_phone` varchar(255) NOT NULL,
  `work_email` varchar(255) NOT NULL,
  `personal_email` varchar(255) NOT NULL,
  `address_1` text NOT NULL,
  `address_2` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_contract`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_contract` (
  `contract_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `contract_type_id` int(11) NOT NULL,
  `from_date` varchar(255) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `to_date` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`contract_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_documents`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_documents` (
  `document_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `document_type_id` int(11) NOT NULL,
  `date_of_expiry` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `notification_email` varchar(255) NOT NULL,
  `is_alert` tinyint(1) NOT NULL,
  `description` text NOT NULL,
  `document_file` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_exit`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_exit` (
  `exit_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `exit_date` varchar(255) NOT NULL,
  `exit_type_id` int(11) NOT NULL,
  `exit_interview` int(11) NOT NULL,
  `is_inactivate_account` int(11) NOT NULL,
  `reason` text NOT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`exit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_exit_type`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_exit_type` (
  `exit_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`exit_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_immigration`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_immigration` (
  `immigration_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `document_type_id` int(11) NOT NULL,
  `document_number` varchar(255) NOT NULL,
  `document_file` varchar(255) NOT NULL,
  `issue_date` varchar(255) NOT NULL,
  `expiry_date` varchar(255) NOT NULL,
  `country_id` varchar(255) NOT NULL,
  `eligible_review_date` varchar(255) NOT NULL,
  `comments` text NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`immigration_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_leave`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_leave` (
  `leave_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `contract_id` int(11) NOT NULL,
  `casual_leave` varchar(255) NOT NULL,
  `medical_leave` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`leave_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_location`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_location` (
  `office_location_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `from_date` varchar(255) NOT NULL,
  `to_date` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`office_location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_promotions`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_promotions` (
  `promotion_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `promotion_date` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`promotion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_qualification`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_qualification` (
  `qualification_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `education_level_id` int(11) NOT NULL,
  `from_year` varchar(255) NOT NULL,
  `language_id` int(11) NOT NULL,
  `to_year` varchar(255) NOT NULL,
  `skill_id` text NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`qualification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_resignations`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_resignations` (
  `resignation_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `notice_date` varchar(255) NOT NULL,
  `resignation_date` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`resignation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_shift`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_shift` (
  `emp_shift_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `shift_id` int(11) NOT NULL,
  `from_date` varchar(255) NOT NULL,
  `to_date` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`emp_shift_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_terminations`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_terminations` (
  `termination_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `terminated_by` int(11) NOT NULL,
  `termination_type_id` int(11) NOT NULL,
  `termination_date` varchar(255) NOT NULL,
  `notice_date` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(2) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`termination_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_transfer`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_transfer` (
  `transfer_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `transfer_date` varchar(255) NOT NULL,
  `transfer_department` int(11) NOT NULL,
  `transfer_location` int(11) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(2) NOT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`transfer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_travels`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_travels` (
  `travel_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `start_date` varchar(255) NOT NULL,
  `end_date` varchar(255) NOT NULL,
  `visit_purpose` varchar(255) NOT NULL,
  `visit_place` varchar(255) NOT NULL,
  `travel_mode` int(11) NOT NULL,
  `arrangement_type` int(11) NOT NULL,
  `expected_budget` varchar(255) NOT NULL,
  `actual_budget` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(2) NOT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`travel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_warnings`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_warnings` (
  `warning_id` int(11) NOT NULL AUTO_INCREMENT,
  `warning_to` int(11) NOT NULL,
  `warning_by` int(11) NOT NULL,
  `warning_date` varchar(255) NOT NULL,
  `warning_type_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(2) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`warning_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employee_work_experience`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employee_work_experience` (
  `work_experience_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `from_date` varchar(255) NOT NULL,
  `to_date` varchar(255) NOT NULL,
  `post` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`work_experience_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_employees`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_employees` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(200) NOT NULL,
  `office_shift_id` int(11) NOT NULL,
  `first_name` varchar(200) NOT NULL,
  `last_name` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `date_of_birth` varchar(200) NOT NULL,
  `gender` varchar(200) NOT NULL,
  `user_role_id` int(100) NOT NULL,
  `department_id` int(100) NOT NULL,
  `designation_id` int(100) NOT NULL,
  `company_id` int(11) NOT NULL,
  `salary_template` varchar(255) NOT NULL,
  `hourly_grade_id` int(11) NOT NULL,
  `monthly_grade_id` int(11) NOT NULL,
  `date_of_joining` varchar(200) NOT NULL,
  `date_of_leaving` varchar(255) NOT NULL,
  `marital_status` varchar(255) NOT NULL,
  `salary` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `profile_picture` text NOT NULL,
  `profile_background` text NOT NULL,
  `resume` text NOT NULL,
  `skype_id` varchar(200) NOT NULL,
  `contact_no` varchar(200) NOT NULL,
  `facebook_link` text NOT NULL,
  `twitter_link` text NOT NULL,
  `blogger_link` text NOT NULL,
  `linkdedin_link` text NOT NULL,
  `google_plus_link` text NOT NULL,
  `instagram_link` varchar(255) NOT NULL,
  `pinterest_link` varchar(255) NOT NULL,
  `youtube_link` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `last_login_date` varchar(255) NOT NULL,
  `last_logout_date` varchar(255) NOT NULL,
  `last_login_ip` varchar(255) NOT NULL,
  `is_logged_in` int(11) NOT NULL,
  `online` int(11) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_expense_type`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_expense_type` (
  `expense_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`expense_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_expenses`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_expenses` (
  `expense_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `expense_type_id` int(11) NOT NULL,
  `billcopy_file` text NOT NULL,
  `amount` varchar(200) NOT NULL,
  `purchase_date` varchar(200) NOT NULL,
  `remarks` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `status_remarks` text NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`expense_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_file_manager`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_file_manager` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` varchar(255) NOT NULL,
  `file_extension` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_file_manager_settings`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_file_manager_settings` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `allowed_extensions` text NOT NULL,
  `maximum_file_size` varchar(255) NOT NULL,
  `is_enable_all_files` varchar(255) NOT NULL,
  `updated_at` varchar(255) NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_finance_bankcash`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_finance_bankcash` (
  `bankcash_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `account_balance` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `branch_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bank_branch` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`bankcash_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_finance_deposit`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_finance_deposit` (
  `deposit_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_type_id` int(11) NOT NULL,
  `amount` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deposit_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `payer_id` int(11) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `deposit_reference` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deposit_file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`deposit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_finance_expense`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_finance_expense` (
  `expense_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_type_id` int(11) NOT NULL,
  `amount` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expense_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `payee_id` int(11) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `expense_reference` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expense_file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`expense_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_finance_payees`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_finance_payees` (
  `payee_id` int(11) NOT NULL AUTO_INCREMENT,
  `payee_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`payee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_finance_payers`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_finance_payers` (
  `payer_id` int(11) NOT NULL AUTO_INCREMENT,
  `payer_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`payer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_finance_transactions`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_finance_transactions` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_type_id` int(11) NOT NULL,
  `deposit_id` int(11) NOT NULL,
  `expense_id` int(11) NOT NULL,
  `transfer_id` int(11) NOT NULL,
  `transaction_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `total_amount` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_debit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_credit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_finance_transfer`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_finance_transfer` (
  `transfer_id` int(11) NOT NULL AUTO_INCREMENT,
  `from_account_id` int(11) NOT NULL,
  `to_account_id` int(11) NOT NULL,
  `transfer_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transfer_amount` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_method` varchar(111) COLLATE utf8_unicode_ci NOT NULL,
  `transfer_reference` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`transfer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_holidays`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_holidays` (
  `holiday_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `start_date` varchar(200) NOT NULL,
  `end_date` varchar(200) NOT NULL,
  `is_publish` tinyint(1) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`holiday_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_hourly_templates`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_hourly_templates` (
  `hourly_rate_id` int(11) NOT NULL AUTO_INCREMENT,
  `hourly_grade` varchar(255) NOT NULL,
  `hourly_rate` varchar(255) NOT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`hourly_rate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_income_categories`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_income_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_job_applications`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_job_applications` (
  `application_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `job_resume` text NOT NULL,
  `application_status` varchar(200) NOT NULL,
  `application_remarks` text NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`application_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_job_interviews`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_job_interviews` (
  `job_interview_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `interviewers_id` varchar(255) NOT NULL,
  `interview_place` varchar(255) NOT NULL,
  `interview_date` varchar(255) NOT NULL,
  `interview_time` varchar(255) NOT NULL,
  `interviewees_id` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`job_interview_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_job_type`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_job_type` (
  `job_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`job_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_jobs`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_jobs` (
  `job_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_title` varchar(255) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `job_type` int(225) NOT NULL,
  `job_vacancy` int(100) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `minimum_experience` varchar(255) NOT NULL,
  `date_of_closing` varchar(200) NOT NULL,
  `short_description` text NOT NULL,
  `long_description` text NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_leave_applications`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_leave_applications` (
  `leave_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(222) NOT NULL,
  `leave_type_id` int(222) NOT NULL,
  `from_date` varchar(200) NOT NULL,
  `to_date` varchar(200) NOT NULL,
  `applied_on` varchar(200) NOT NULL,
  `reason` text NOT NULL,
  `remarks` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`leave_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_leave_type`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_leave_type` (
  `leave_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) NOT NULL,
  `days_per_year` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`leave_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_make_payment`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_make_payment` (
  `make_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `payment_date` varchar(200) NOT NULL,
  `basic_salary` varchar(255) NOT NULL,
  `payment_amount` varchar(255) NOT NULL,
  `gross_salary` varchar(255) NOT NULL,
  `total_allowances` varchar(255) NOT NULL,
  `total_deductions` varchar(255) NOT NULL,
  `net_salary` varchar(255) NOT NULL,
  `house_rent_allowance` varchar(255) NOT NULL,
  `medical_allowance` varchar(255) NOT NULL,
  `travelling_allowance` varchar(255) NOT NULL,
  `dearness_allowance` varchar(255) NOT NULL,
  `provident_fund` varchar(255) NOT NULL,
  `tax_deduction` varchar(255) NOT NULL,
  `security_deposit` varchar(255) NOT NULL,
  `overtime_rate` varchar(255) NOT NULL,
  `is_advance_salary_deduct` int(11) NOT NULL,
  `advance_salary_amount` varchar(255) NOT NULL,
  `is_payment` tinyint(1) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `hourly_rate` varchar(255) NOT NULL,
  `total_hours_work` varchar(255) NOT NULL,
  `comments` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`make_payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_office_location`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_office_location` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `location_head` int(11) NOT NULL,
  `location_manager` int(11) NOT NULL,
  `location_name` varchar(200) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `address_1` text NOT NULL,
  `address_2` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `country` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_office_shift`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_office_shift` (
  `office_shift_id` int(11) NOT NULL AUTO_INCREMENT,
  `shift_name` varchar(255) NOT NULL,
  `default_shift` int(11) NOT NULL,
  `monday_in_time` varchar(222) NOT NULL,
  `monday_out_time` varchar(222) NOT NULL,
  `tuesday_in_time` varchar(222) NOT NULL,
  `tuesday_out_time` varchar(222) NOT NULL,
  `wednesday_in_time` varchar(222) NOT NULL,
  `wednesday_out_time` varchar(222) NOT NULL,
  `thursday_in_time` varchar(222) NOT NULL,
  `thursday_out_time` varchar(222) NOT NULL,
  `friday_in_time` varchar(222) NOT NULL,
  `friday_out_time` varchar(222) NOT NULL,
  `saturday_in_time` varchar(222) NOT NULL,
  `saturday_out_time` varchar(222) NOT NULL,
  `sunday_in_time` varchar(222) NOT NULL,
  `sunday_out_time` varchar(222) NOT NULL,
  `created_at` varchar(222) NOT NULL,
  PRIMARY KEY (`office_shift_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_payment_method`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_payment_method` (
  `payment_method_id` int(11) NOT NULL AUTO_INCREMENT,
  `method_name` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`payment_method_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_performance_appraisal`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_performance_appraisal` (
  `performance_appraisal_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `appraisal_year_month` varchar(255) NOT NULL,
  `customer_experience` int(11) NOT NULL,
  `marketing` int(11) NOT NULL,
  `management` int(11) NOT NULL,
  `administration` int(11) NOT NULL,
  `presentation_skill` int(11) NOT NULL,
  `quality_of_work` int(11) NOT NULL,
  `efficiency` int(11) NOT NULL,
  `integrity` int(11) NOT NULL,
  `professionalism` int(11) NOT NULL,
  `team_work` int(11) NOT NULL,
  `critical_thinking` int(11) NOT NULL,
  `conflict_management` int(11) NOT NULL,
  `attendance` int(11) NOT NULL,
  `ability_to_meet_deadline` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`performance_appraisal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_performance_indicator`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_performance_indicator` (
  `performance_indicator_id` int(11) NOT NULL AUTO_INCREMENT,
  `designation_id` int(11) NOT NULL,
  `customer_experience` int(11) NOT NULL,
  `marketing` int(11) NOT NULL,
  `management` int(11) NOT NULL,
  `administration` int(11) NOT NULL,
  `presentation_skill` int(11) NOT NULL,
  `quality_of_work` int(11) NOT NULL,
  `efficiency` int(11) NOT NULL,
  `integrity` int(11) NOT NULL,
  `professionalism` int(11) NOT NULL,
  `team_work` int(11) NOT NULL,
  `critical_thinking` int(11) NOT NULL,
  `conflict_management` int(11) NOT NULL,
  `attendance` int(11) NOT NULL,
  `ability_to_meet_deadline` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`performance_indicator_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_projects`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_projects` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `start_date` varchar(255) NOT NULL,
  `end_date` varchar(255) NOT NULL,
  `company_id` int(11) NOT NULL,
  `assigned_to` text NOT NULL,
  `priority` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `description` text NOT NULL,
  `added_by` int(11) NOT NULL,
  `project_progress` varchar(255) NOT NULL,
  `project_note` longtext NOT NULL,
  `status` tinyint(2) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_projects_attachment`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_projects_attachment` (
  `project_attachment_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `upload_by` int(11) NOT NULL,
  `file_title` varchar(255) NOT NULL,
  `file_description` text NOT NULL,
  `attachment_file` text NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`project_attachment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_projects_bugs`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_projects_bugs` (
  `bug_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `attachment_file` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`bug_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_projects_discussion`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_projects_discussion` (
  `discussion_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `attachment_file` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`discussion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_qualification_education_level`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_qualification_education_level` (
  `education_level_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`education_level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_qualification_language`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_qualification_language` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_qualification_skill`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_qualification_skill` (
  `skill_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`skill_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_salary_templates`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_salary_templates` (
  `salary_template_id` int(11) NOT NULL AUTO_INCREMENT,
  `salary_grades` varchar(255) NOT NULL,
  `basic_salary` varchar(255) NOT NULL,
  `overtime_rate` varchar(255) NOT NULL,
  `house_rent_allowance` varchar(255) NOT NULL,
  `medical_allowance` varchar(255) NOT NULL,
  `travelling_allowance` varchar(255) NOT NULL,
  `dearness_allowance` varchar(255) NOT NULL,
  `security_deposit` varchar(255) NOT NULL,
  `provident_fund` varchar(255) NOT NULL,
  `tax_deduction` varchar(255) NOT NULL,
  `gross_salary` varchar(255) NOT NULL,
  `total_allowance` varchar(255) NOT NULL,
  `total_deduction` varchar(255) NOT NULL,
  `net_salary` varchar(255) NOT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`salary_template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_support_ticket_files`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_support_ticket_files` (
  `ticket_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `ticket_files` varchar(255) NOT NULL,
  `file_size` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`ticket_file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_support_tickets`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_support_tickets` (
  `ticket_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_code` varchar(200) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `ticket_priority` varchar(255) NOT NULL,
  `department_id` int(11) NOT NULL,
  `assigned_to` text NOT NULL,
  `message` text NOT NULL,
  `description` text NOT NULL,
  `ticket_remarks` text NOT NULL,
  `ticket_status` varchar(200) NOT NULL,
  `ticket_note` text NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`ticket_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_system_setting`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_system_setting` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `application_name` TEXT NOT NULL,
  `default_currency` TEXT NOT NULL,
  `default_currency_symbol` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `show_currency` TEXT NOT NULL,
  `currency_position` TEXT NOT NULL,
  `notification_position` TEXT NOT NULL,
  `notification_close_btn` TEXT NOT NULL,
  `notification_bar` TEXT NOT NULL,
  `enable_registration` TEXT NOT NULL,
  `login_with` TEXT NOT NULL,
  `date_format_xi` TEXT NOT NULL,
  `employee_manage_own_contact` TEXT NOT NULL,
  `employee_manage_own_profile` TEXT NOT NULL,
  `employee_manage_own_qualification` TEXT NOT NULL,
  `employee_manage_own_work_experience` TEXT NOT NULL,
  `employee_manage_own_document` TEXT NOT NULL,
  `employee_manage_own_picture` TEXT NOT NULL,
  `employee_manage_own_social` TEXT NOT NULL,
  `employee_manage_own_bank_account` TEXT NOT NULL,
  `enable_attendance` TEXT NOT NULL,
  `enable_clock_in_btn` TEXT NOT NULL,
  `enable_email_notification` TEXT NOT NULL,
  `payroll_include_day_summary` TEXT NOT NULL,
  `payroll_include_hour_summary` TEXT NOT NULL,
  `payroll_include_leave_summary` TEXT NOT NULL,
  `enable_job_application_candidates` TEXT NOT NULL,
  `job_logo` TEXT NOT NULL,
  `payroll_logo` TEXT NOT NULL,
  `is_payslip_password_generate` int(11) NOT NULL,
  `payslip_password_format` TEXT NOT NULL,
  `enable_profile_background` TEXT NOT NULL,
  `enable_policy_link` TEXT NOT NULL,
  `enable_layout` TEXT NOT NULL,
  `job_application_format` text NOT NULL,
  `project_email` TEXT NOT NULL,
  `holiday_email` TEXT NOT NULL,
  `leave_email` TEXT NOT NULL,
  `payslip_email` TEXT NOT NULL,
  `award_email` TEXT NOT NULL,
  `recruitment_email` TEXT NOT NULL,
  `announcement_email` TEXT NOT NULL,
  `training_email` TEXT NOT NULL,
  `task_email` TEXT NOT NULL,
  `compact_sidebar` TEXT NOT NULL,
  `fixed_header` TEXT NOT NULL,
  `fixed_sidebar` TEXT NOT NULL,
  `boxed_wrapper` TEXT NOT NULL,
  `layout_static` TEXT NOT NULL,
  `system_skin` TEXT NOT NULL,
  `animation_effect` TEXT NOT NULL,
  `animation_effect_modal` TEXT NOT NULL,
  `animation_effect_topmenu` TEXT NOT NULL,
  `footer_text` TEXT NOT NULL,
  `enable_page_rendered` TEXT NOT NULL,
  `enable_current_year` TEXT NOT NULL,
  `updated_at` TEXT NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_tasks`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_tasks` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `assigned_to` varchar(255) NOT NULL,
  `start_date` varchar(200) NOT NULL,
  `end_date` varchar(200) NOT NULL,
  `task_hour` varchar(200) NOT NULL,
  `task_progress` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `task_status` int(5) NOT NULL,
  `task_note` text NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`task_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_tasks_attachment`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_tasks_attachment` (
  `task_attachment_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `upload_by` int(11) NOT NULL,
  `file_title` varchar(255) NOT NULL,
  `file_description` text NOT NULL,
  `attachment_file` text NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`task_attachment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_tasks_comments`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_tasks_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_comments` text NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_termination_type`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_termination_type` (
  `termination_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`termination_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_tickets_attachment`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_tickets_attachment` (
  `ticket_attachment_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `upload_by` int(11) NOT NULL,
  `file_title` varchar(255) NOT NULL,
  `file_description` text NOT NULL,
  `attachment_file` text NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`ticket_attachment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_tickets_comments`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_tickets_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ticket_comments` text NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_trainers`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_trainers` (
  `trainer_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `expertise` text NOT NULL,
  `address` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`trainer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_training`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_training` (
  `training_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(200) NOT NULL,
  `training_type_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `start_date` varchar(200) NOT NULL,
  `finish_date` varchar(200) NOT NULL,
  `training_cost` varchar(200) NOT NULL,
  `training_status` int(11) NOT NULL,
  `description` text NOT NULL,
  `performance` varchar(200) NOT NULL,
  `remarks` text NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`training_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_training_types`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_training_types` (
  `training_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`training_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_travel_arrangement_type`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_travel_arrangement_type` (
  `arrangement_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`arrangement_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_user_roles`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_user_roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(200) NOT NULL,
  `role_access` varchar(200) NOT NULL,
  `role_resources` text NOT NULL,
  `created_at` varchar(200) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

		
		



		-- ---------------------------------------------------------
		--
		-- Table structure for table : `xin_warning_type`
		--
		-- ---------------------------------------------------------
		CREATE TABLE `xin_warning_type` (
  `warning_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  PRIMARY KEY (`warning_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

		
		


		/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
		/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
		/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (id varchar(128) NOT NULL, ip_address varchar(45) NOT NULL, timestamp int(10) unsigned DEFAULT 0 NOT NULL, data blob NOT NULL, KEY ci_sessions_timestamp (timestamp));

INSERT INTO `xin_system_setting` (`setting_id`, `application_name`, `default_currency`, `default_currency_symbol`, `show_currency`, `currency_position`, `notification_position`, `notification_close_btn`, `notification_bar`, `enable_registration`, `login_with`, `date_format_xi`, `employee_manage_own_contact`, `employee_manage_own_profile`, `employee_manage_own_qualification`, `employee_manage_own_work_experience`, `employee_manage_own_document`, `employee_manage_own_picture`, `employee_manage_own_social`, `employee_manage_own_bank_account`, `enable_attendance`, `enable_clock_in_btn`, `enable_email_notification`, `payroll_include_day_summary`, `payroll_include_hour_summary`, `payroll_include_leave_summary`, `enable_job_application_candidates`, `job_logo`, `payroll_logo`, `is_payslip_password_generate`, `payslip_password_format`, `enable_profile_background`, `enable_policy_link`, `enable_layout`, `job_application_format`, `project_email`, `holiday_email`, `leave_email`, `payslip_email`, `award_email`, `recruitment_email`, `announcement_email`, `training_email`, `task_email`, `compact_sidebar`, `fixed_header`, `fixed_sidebar`, `boxed_wrapper`, `layout_static`, `system_skin`, `animation_effect`, `animation_effect_modal`, `animation_effect_topmenu`, `footer_text`, `enable_page_rendered`, `enable_current_year`, `updated_at`) VALUES
(1, 'HRM System', 'USD', '$', 'yes', 'prefix', 'toast-top-right', 'yes', 'yes', 'yes', 'username', 'Y-m-d', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', '', '', 0, '', 'yes', 'yes', 'fixed', '', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'skin-blue', 'fadeIn', 'fadeIn', 'fadeIn', 'All Rights Reserved', 'yes', 'yes', '2025-01-01 00:00:00');
INSERT INTO `xin_company_info` (`company_info_id`, `logo`, `logo_second`, `sign_in_logo`, `favicon`, `website_url`, `starting_year`, `company_name`, `company_email`, `company_contact`, `contact_person`, `email`, `phone`, `address_1`, `address_2`, `city`, `state`, `zipcode`, `country`, `updated_at`) VALUES
(1, 'logo.png', 'logo2.png', 'signin_logo.png', 'favicon.png', 'http://localhost/hrm', '2025', 'My Company', 'info@example.com', '1234567890', 'Admin', 'admin@example.com', '1234567890', '123 Main St', '', 'City', 'State', '12345', 1, '2025-01-01 00:00:00');