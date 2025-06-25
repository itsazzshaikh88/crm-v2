<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', TRUE);

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
defined('FILE_READ_MODE')  or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  or define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

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
defined('EXIT_SUCCESS')        or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code



// user definded constants
defined('APP_SECRET_KEY')       or define('APP_SECRET_KEY', 'secretkeyforanapplicationiskismatji7246');


// NAVIGATION LINK ACTIVE ITEMS

define('HOME_ACTIVE_LINK', 'home');
define('PRODUCT_ACTIVE_LINK', 'products');
define('ADMIN_ACTIVE_LINK', 'admin');
$activities = [
    [
        'name' => 'Call',
        'id' => 'custom-activity-modal-call',  // Unique ID for Call activity modal
    ],
    [
        'name' => 'Meeting',
        'id' => 'custom-activity-modal-meeting',  // Unique ID for Meeting activity modal
    ],
    [
        'name' => 'Note',
        'id' => 'custom-activity-modal-notes',  // Unique ID for Note activity modal
    ],
];
define('LEAD_ACTIVITY_OPTIONS', $activities);
$deal_activities = [
    [
        'name' => 'Call',
        'id' => 'custom-activity-modal-call',  // Unique ID for Call activity modal
    ],
    [
        'name' => 'Meeting',
        'id' => 'custom-activity-modal-meeting',  // Unique ID for Meeting activity modal
    ],
    [
        'name' => 'Note',
        'id' => 'custom-activity-modal-notes',  // Unique ID for Note activity modal
    ],
    [
        'name' => 'Task',
        'id' => 'custom-activity-modal-task',  // Unique ID for Note activity modal
    ],
];
define('DEAL_ACTIVITY_OPTIONS', $deal_activities);

/*
|--------------------------------------------------------------------------
| Time Intervals Reference Table
|--------------------------------------------------------------------------
| This table provides standard time intervals with all units fully converted:
| - Hours (decimal)
| - Total Minutes (all hours converted to minutes)
| - Total Seconds (all minutes converted to seconds)
|
| Format: [Label] => [Hours] | [Minutes] | [Seconds]
|
| Interval        => Hours   | Minutes | Seconds
|---------------------------|---------|---------
| 1 min           => 0.0167  | 1       | 60
| 2 min           => 0.0333  | 2       | 120
| 3 min           => 0.0500  | 3       | 180
| 4 min           => 0.0667  | 4       | 240
| 5 min           => 0.0833  | 5       | 300
| 10 min          => 0.1667  | 10      | 600
| 15 min          => 0.2500  | 15      | 900
| 30 min          => 0.5000  | 30      | 1800
| 45 min          => 0.7500  | 45      | 2700
| 1 hour          => 1.0000  | 60      | 3600
| 1.25 hours      => 1.2500  | 75      | 4500
| 1.5 hours       => 1.5000  | 90      | 5400
| 1.75 hours      => 1.7500  | 105     | 6300
| 2 hours         => 2.0000  | 120     | 7200
| 2.25 hours      => 2.2500  | 135     | 8100
| 2.5 hours       => 2.5000  | 150     | 9000
| 2.75 hours      => 2.7500  | 165     | 9900
| 3 hours         => 3.0000  | 180     | 10800
| 3.25 hours      => 3.2500  | 195     | 11700
| 3.5 hours       => 3.5000  | 210     | 12600
| 3.75 hours      => 3.7500  | 225     | 13500
| 4 hours         => 4.0000  | 240     | 14400
| 4.25 hours      => 4.2500  | 255     | 15300
| 4.5 hours       => 4.5000  | 270     | 16200
| 4.75 hours      => 4.7500  | 285     | 17100
| 5 hours         => 5.0000  | 300     | 18000
|
*/


// Notifications Intervals -> must be in seconds
define('LEADS_NOTIFICATION_INTERVAL', 120);
