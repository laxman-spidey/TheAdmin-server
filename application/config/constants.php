<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


/* 
*   application specific Success and Error codes
*/

// Top level constants


defined('TAG_HTTP_REQUEST_CODE') OR define('TAG_HTTP_REQUEST_CODE', "HTTP_REQUESTCODE");
defined('TAG_REQUEST_CODE')      OR define('TAG_REQUEST_CODE', "requestCode");
defined('TAG_RESULT_CODE')       OR define('TAG_RESULT_CODE', "resultCode");

defined('PRO_SUCCESS')          OR define('PRO_SUCCESS', TRUE); // Request successfully proccessed.
defined('PRO_FAILED')           OR define('PRO_FAILED', FALSE); // Request processing failed.


// Response Codes for check in

defined('CHECKIN_ALREADY_CHECKEDIN')    OR define('CHECKIN_ALREADY_CHECKEDIN', 101); //already checkin
defined('CHECKIN_SUCCESS')    OR define('CHECKIN_SUCCESS', 102); // checkin inserted succesfully
defined('CHECKIN_INSERT_DBERROR')    OR define('CHECKIN_INSERT_DBERROR', 103); // checkin deosnot inserted
defined('INFO_WEEKOFF')    OR define('INFO_WEEKOFF', 104); // weekoff of staffid.contact administrator to register attendance
defined('WARNING_ROASTER_DOES_NOT_EXIST')    OR define('WARNING_ROASTER_DOES_NOT_EXIST', 105);//staffid doesnot exister in roaster on that day

// Response Codes for check out

defined('CHECKOUT_SUCCESS')    OR define('CHECKOUT_SUCCESS', 111); // checkout inserted succesfully
defined('CHECKOUT_INSERT_DBERROR')    OR define('CHECKOUT_INSERT_DBERROR', 112); // checkout deosnot inserted
defined('CHECKOUT_NOT_CHECKEDIN')    OR define('CHECKOUT_NOT_CHECKEDIN', 113); // checkout deosnot inserted
defined('CHECKOUT_INSERT_DBERROR')    OR define('CHECKOUT_INSERT_DBERROR', 114); // checkout deosnot inserted

// Response Codes for roaster details

defined('ROASTER_DETAILS_NOT_EXIST')    OR define('ROASTER_DETAILS_NOT_EXIST', 121); // Roaster details doesnot found in given limit
defined('ROASTER_DETAILS_EXIST')    OR define('ROASTER_DETAILS_EXIST', 122); // Roaster details found in given limit

// Response Codes for Attendance History details

defined('ATTENDANCE_HISTORY_NOT_EXIST')    OR define('ATTENDANCE_HISTORY_NOT_EXIST', 131); // attendance details does not found in given limit
defined('ATTENDANCE_HISTORY_EXIST')    OR define('ATTENDANCE_HISTORY_EXIST', 132); // attendance details found in given limit


// Response Codes for checking authorization and expiring unused OTP

defined('CHECK_AUTHORIZATION_SUCCESS')    OR define('CHECK_AUTHORIZATION_SUCCESS', 141); // Found staff details with phone number
defined('CHECK_AUTHORIZATION_FAIL')    OR define('CHECK_AUTHORIZATION_FAIL', 142); // Doesnot found staff details with phone number
defined('CHECK_AUTHORIZATION_EXPIRATION_UPDATE_FAIL')    OR define('CHECK_AUTHORIZATION_EXPIRATION_UPDATE_FAIL', 143); // Doesnot found staff details with phone number

// Response Codes for getting user data

defined('LOGIN_SUCCESS')    OR define('LOGIN_SUCCESS', 151); // Found valid otp and login sucessful
defined('USER_DATA_SUCCESS')    OR define('USER_DATA_SUCCESS', 152); // Found user details
defined('USER_DATA_FAIL')    OR define('USER_DATA_FAIL', 153); // Doesnot found user details

// Response Codes for validation OTP and updating status

defined('VALIDATE_OTP_STATUS_UPDATE_SUCCESS')    OR define('VALIDATE_OTP_STATUS_UPDATE_SUCCESS', 161); // used OTP status update success
defined('VALIDATE_OTP_STATUS_UPDATE_FAIL')    OR define('VALIDATE_OTP_STATUS_UPDATE_FAIL', 162); // used OTP status update failed
defined('INVALID_OTP')    OR define('INVALID_OTP', 163); // Entered invalid OTP

// Response codes for applying leave

defined('APPLY_LEAVE_ALREADY_ON_LEAVE')    OR define('APPLY_LEAVE_ALREADY_ON_LEAVE', 171); // staff id already on leave on the date appllied for leave
defined('APPLY_LEAVE_SUCCESS')    OR define('APPLY_LEAVE_SUCCESS', 172); // leave applied succesfully for the day and staffid
defined('APPLY_LEAVE_FAIL')    OR define('APPLY_LEAVE_FAIL', 173); //leave not applied due to some db error 

// Response codes for checking number of leaves available

defined('CHECK_LEAVE_EXISTS')    OR define('CHECK_LEAVE_EXISTS', 181); //leaves available 
defined('CHECK_LEAVE_DOES_NOT_EXIST')    OR define('CHECK_LEAVE_DOES_NOT_EXIST', 182); //leaves completed not available

// Response Codes for getting leaves summary

defined('LEAVE_SUMMARY_EXISTS')    OR define('LEAVE_SUMMARY_EXISTS', 191); //leaves summary available
defined('LEAVE_SUMMARY_DOES_NOT_EXIST')    OR define('LEAVE_SUMMARY_DOES_NOT_EXIST', 192); //leaves summary not available no records found

// Response codes to check if swap is available

defined('SHOW_SWAP_AVAILABLE')    OR define('SHOW_SWAP_AVAILABLE', 201); // swap available
defined('SHOW_SWAP_UNAVAILABLE')    OR define('SHOW_SWAP_UNAVAILABLE', 202); // swap unavailable

// Response codes to apply swap by eligibility

defined('APPLY_SWAP_BY_ELIGIBILITY_SUCCESS')    OR define('APPLY_SWAP_BY_ELIGIBILITY_SUCCESS', 211); // swap request submitted succesfully and eligible
defined('APPLY_SWAP_BY_ELIGIBILITY_FAIL')    OR define('APPLY_SWAP_BY_ELIGIBILITY_FAIL', 212); // swap request ubmission failed but eligible
defined('APPLY_SWAP_BY_ELIGIBILITY_SWAPS_COMPLETED')    OR define('APPLY_SWAP_BY_ELIGIBILITY_SWAPS_COMPLETED', 213); // uneligible for swap

// Response codes to update swap status of swap request

defined('SWAP_STATUS_SUCCESS')    OR define('SWAP_STATUS_SUCCESS', 221); // swap request status updated succesfully 
defined('SWAP_STATUS_FAIL')    OR define('SWAP_STATUS_FAIL', 222); // swap request status updation failed











