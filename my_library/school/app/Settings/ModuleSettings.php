<?php


namespace App\Settings;
use DateTime;
use phpDocumentor\Reflection\Types\Boolean;


class ModuleSettings extends settings
{
    public DateTime $start_date;
    public DateTime $end_time;
    public string $messsage_of_system_suspend;
    public int $number_of_students;
    public int $maximum_size_of_upload_file;
    public Boolean  $facebook_login;
    public Boolean  $google_plus_login;
    public Boolean  $messaging;
    public Boolean  $branches;
    public Boolean  $parent;
    public Boolean  $transfer_students;
    public Boolean  $daily_school_schedule;
    public Boolean  $management_of_educational_content;
    public Boolean  $language_settings;
    public Boolean  $library_Management;
    public Boolean  $attendance_and_departure;
    public Boolean  $homework;
    public Boolean  $topics;
    public Boolean  $year_grades;
    public Boolean  $feedback;
    public Boolean  $student_notes;
    public Boolean  $admin_statistics;
    public Boolean  $school_years_management;
    public Boolean  $Classroom_management;
    public Boolean  $Religions_management;
    public Boolean  $branches_management;
    public Boolean  $school_sections_management;
    public Boolean  $site_settings;
    public Boolean  $clear_database_logs;
    public Boolean  $transfer_data_to_next_year;
    public Boolean  $virtual_classrooms;
    public Boolean  $paypal;
    public Boolean  $payu;
    public Boolean  $email_verification;
    public Boolean  $space_ocean;
    public Boolean  $teacher_access_student_profile;
    public Boolean  $automatic_student_attendance;
    public Boolean  $virtual_room_for_every_class;
    public Boolean  $Points_and_cups_system;
    public Boolean  $end_virtual_room_automatic;
    public Boolean  $academic_university_system;
    public Boolean  $alphabetical_numbering;


    public static function group(): string
    {
        return 'module';
    }
    public static function casts(): array
    {
        return [
            'start_date' => DateTimeInterfaceCast::class,
            'end_date' => DateTimeInterfaceCast::class
        ];
    }

}
