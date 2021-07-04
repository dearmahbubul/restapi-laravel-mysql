<?php
/**
 * Created by Mahbubul Alam
 * User: Happy App
 * Date: 7/10/21
 * Time: 12:00 AM
 */

// Core constant
// For status
const STATUS_INACTIVE = 0;
const STATUS_ACTIVE   = 1;

// For leave
const NOT_EMERGENCY_LEAVE = 0;
const EMERGENCY_LEAVE = 1;

const CASUAL_LEAVE = 1;
const SICK_LEAVE = 2;

const LEAVE_PENDING = 0;
const LEAVE_APPROVED   = 1;
const LEAVE_REJECT   = 2;

// For attendance
const ATTENDANCE_TYPE_PRESENT = 1;
const ATTENDANCE_TYPE_ABSENT = 2;
const ATTENDANCE_TYPE_WEEKLY_HOLIDAY = 3;
const ATTENDANCE_TYPE_GOVT_HOLIDAY = 4;
const ATTENDANCE_TYPE_CASUAL_LEAVE = 5;
const ATTENDANCE_TYPE_SICK_LEAVE = 6;

/**
 * @param $payload
 * @param string $message
 * @param bool $status
 * @param int $status_code
 * @return array
 */
function getFormattedResponseData($payload, string $message, bool $status, int $status_code = 200) : array
{
    return [
        'payload'       => $payload,
        'message'       => $message,
        'status'        => $status,
        'status_code'   => $status_code
    ];
}


