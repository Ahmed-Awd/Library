<?php


namespace App\Repositories;


use App\Models\AcademicSemester;
use App\Models\LevelSubject;
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Timetable;
use App\Models\TimingSet;
use App\Models\TimingsetDetail;
use App\Models\User;
use Facade\FlareClient\Time\Time;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Request;

class TimetableRepository implements TimetableRepositoryInterface
{
    public function getUserSchedule($username)
    {
        $user = User::where('username', $username)->first();
        $role = $user->getRoleNames()[0];
        $semester_id = new AcademicSemester();
        $semester_id = $semester_id->defaultSemester();
        $subjects = [];
        $class_id = false;
        $without_break = false;
        if($role == 'staff'){
            $staff = $user->id;
        } elseif ($role == 'student') {
            $staff = null;
            $without_break = true;
            $subjects = StudentSubject::where('student_id', $user->id)->where('semester_id', $semester_id)->pluck('subject_id');
        }
        return $this->getSchedule($class_id, $staff, $without_break, $user->branch_id, $subjects);

    }
    public function getClassSchedule($class)
    {
        $data['class'] = $class;
        $data['schedule'] = $this->getSchedule($class->id, null, false, auth()->user()->branch_id);
        return $data;
    }

    public function getSchedule($class = null, $staff = null, $without_break = false, $branch = null, $subjects = [])
    {
            $results = TimingSet::with(['timingsetdetails.timetables'=>function($query) use($class, $staff, $branch, $subjects) {
                        if($class) $query->where('class_id',$class);
                        if($staff) $query->where('user_id',$staff);
                        $query->where('timetable.branch_id',$branch);
                        $query->join('users','users.id','user_id');
                        $query->join('subjects','subjects.id','subject_id');
                        $query->leftJoin('classes','classes.id','class_id');
                if($subjects)$query->whereIn('subject_id', $subjects);
                        $query->select(['timingset_details_id','name',
                            'username','subjects.title as subject_title','classes.title as class_title','day']);
                        }])->get();
            $results->transform(function ($timingset){
                $count = 0 ;
                $timingset->timingsetdetails->map(function ($session) use(&$count){
                    $count +=  $session->timetables->count();
                    $session->timetables->transform(function ($timetable) {
                           $days = config('general.days');
                           foreach ($days as $key => $day) {
                               if($timetable->day == $key){
                                   $day_sessions[$day] = $timetable;
                               } else {
                                   $day_sessions[$day] = null;
                               }
                           }
                           return $day_sessions;
                       });
                       return $session;
                 });
                if($count > 0) {
                    return $timingset;
                }
            });
            $results = $results->filter(function($v){
                return !is_null($v) ;
            });
        return $results;
    }

    public function updateSchedule($data) {
        
        $semester_id = new AcademicSemester();
        $semester_id = $semester_id->defaultSemester();

        foreach($data->deletedSessions as $session) {
            $record = Timetable::where('day', $session['day'])
                                ->where('timingset_details_id', $session['timingsetdetails_id'])
                                ->where('user_id', $session['staff_id'])
                                ->where('subject_id', $session['subject_id'])
                                ->first();
            if($record) {
                $record->delete();
            }
        }
        foreach ($data->appendedSesions as $session) {
            $record = new Timetable ();
            $record->semester_id = $semester_id;
            $record->class_id = $session['class_id'];
            $record->day = $session['day'];
            $record->timingset_details_id = $session['timingsetdetails_id'];
            $record->user_id = $session['staff_id'];
            $record->subject_id = $session['subject_id'];
            $record->branch_id = auth()->user()->id;
            $record->save();
        }
    }

    public function isStaffAvailable($data)
    {
        $record = Timetable::where('day', $data->day)
        ->where('timingset_details_id', $data->timingsetdetails_id)
        ->where('user_id', $data->user_id)
        ->first();
        if($record){
            return false;
        } else {
            return true;
        }
    }

    public function getLevelSchedule($program, $track, $level)
    {
        $subjects = LevelSubject::where('program_id', $program->id)
                                ->where('track_id', $track->id)
                                ->where('level_id', $level->id)
                                ->pluck('subject_id');
        $class = null;
        $staff = null;
        $without_break = false;
        $branch_id = auth()->user()->branch_id;                       
       $schedule =  $this->getSchedule($class, $staff, $without_break, $branch_id, $subjects);
       $data['track'] = $track;
       $data['program'] = $program;
       $data['level'] = $level;
       $data['schedule'] = $schedule;
       return $data;
    }

    public function staffReport($data)
    {
        foreach ($data->staff as $staff) {
            $schedule[$staff['name']] =  $this->getSchedule(false , $staff['id'], true, auth()->user()->branch_id , []);
        }
        return $schedule;
    }
    public function classesReport($data)
    {
        foreach ($data->classes as $class) {
            $schedule[$class['title']] =  $this->getSchedule($class['id'] , false, true, auth()->user()->branch_id , []);
        }
        return $schedule;
    }

}
