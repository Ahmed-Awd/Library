<?php


namespace App\Http\Controllers;


use App\Course;
use App\CourseSubject;
use App\Skill;
use App\Subject;
use App\User;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class SkillsController extends Controller
{
    public function index()
    {
        $data['layout'] = getLayout();
        $data['active_class'] = 'academic';
        $data['title'] = getPhrase('skills');
        return view('Skills.all_skills', $data);
    }

    public function create()
    {
        $data['layout'] = getLayout();
        $data['active_class'] = 'academic';
        $data['title'] = getPhrase('add_skills');
        $data['manpulation_kind']='add';
        $data['sid'] = 0;
        return view('Skills.add-edit', $data);
    }

    public function getDatatable()
    {
        $records = Skill::select(['id','slug','skill_title', 'course_id', 'subject_id','created_by_user','updated_by_user','created_by_ip','updated_by_ip','created_at','updated_at']);
        // ->get();


        return Datatables::of($records)
            ->addColumn('academic_year', function ($record) {
                return getSubjectDetails($record->subject_id)['year']->academic_year_title;
            })
            ->addColumn('semester', function ($record) {
                return getPhrase(SemesterName(getSubjectDetails($record->subject_id)['sem']));
            })
            ->addColumn('branch', function ($record) {
                return getSubjectDetails($record->subject_id)['course']->course_title;
            })
            ->addColumn('subject', function ($record) {
                return getSubjectDetails($record->subject_id)['subject']->subject_title;
            })

            ->addColumn('action', function ($records) {
                $records->created_by_user_name = User::get_user_name($records->created_by_user);
                $records->updated_by_user_name = User::get_user_name($records->updated_by_user);
                $view = "<li><a onclick='pop_it($records)'><i class=\"fa fa-eye\"></i>".getPhrase('view_record_history')."</a></li>";

                return '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li><a href="skills/edit/'.$records->id.'"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>

                             <li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->slug . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>'.$view.'

                        </ul>
                    </div>';
            })
            ->removeColumn('created_by_user')
            ->removeColumn('updated_by_user')
            ->removeColumn('created_by_ip')
            ->removeColumn('updated_by_ip')
            ->removeColumn('created_at')
            ->removeColumn('updated_at')

            ->removeColumn('course_id')
            ->removeColumn('subject_id')
            ->removeColumn('slug')


            ->removeColumn('id')
            ->make();
    }
    public function edit($id)
    {
        $data['layout'] = getLayout();
        $data['active_class'] = 'academic';
        $data['title'] =getPhrase('edit_skills');
        $data['record']=Skill::find($id);
        $data['manpulation_kind']='edit';
        $data['sid'] = $data['record']->subject_id;
        return view('Skills.add-edit', $data);

    }
    public function getRelatedSubjects(Request $request)
    {
        $allSubjectsRelated = Subject::join('course_subject', 'subjects.id', '=', 'course_subject.subject_id')
            ->where('course_parent_id', $request->course_parent_id)
            ->select(['subjects.id', 'subject_title'])->groupBy('subjects.id')->get();
        $i = 0;
        foreach ($allSubjectsRelated as $subject) {
            $data[$i]['subjects_title'] = $subject->subject_title;
            $data[$i]['subjects_id'] = $subject->id;
            $i++;
        }
        return $data;
    }

    public function store(Request $request)
    {
        $skills_title = $request->skills;
        if ($request->subject_id == 0 or $request->course_id == 0) {
            flash(getPhrase('error'), getPhrase('select_all_fields_please'), 'error');
            return redirect()->back();
        }
        foreach ($skills_title as $skill) {
            $record = new Skill;
            if ($skill != '') {
                $record->skill_title = $skill;
                $record->course_id   = $request->course_id;
                $record->subject_id  = $request->subject_id;
                $record->slug        = $record->makeSlug($skill);
                $record->user_stamp($request);
                $record->save();
            }
        }
        flash(getPhrase('saved'), getPhrase('skills_saved_successfully'), 'success');
        return redirect()->back();
        //return redirect('mastersettings/skills/create');
    }
    public function update(Request $request,$id)
    {
        $skills_title = $request->skills;
        $skill_record=Skill::find($id);
        if ($request->course_id == 0 or $request->subject_id == 0) {
            flash(getPhrase('error'), getPhrase('select_all_fields_please'), 'error');
            return redirect()->back();
        }
        foreach ($skills_title as $skill) {
            if ($skill != '') {
                $skill_record->skill_title=$skill;
                $skill_record->course_id=$request->course_id;
                $skill_record->subject_id=$request->subject_id;
                $skill_record->update_stamp($request);
                $skill_record->update();
            }
        }
        flash(getPhrase('updated'), getPhrase('skills_updated_successfully'), 'success');
        return redirect()->back();//return redirect(URL_MASTERSETTINGS_SKILLS_EDIT.$id);
    }
    public function delete($slug)
    {
        try {
            if (!env('DEMO_MODE')) {
                Skill::where('slug', $slug)->delete();
            }
            $response['status'] = 1;
            $response['message'] = getPhrase('record_deleted_successfully');
        } catch (Exception $e) {
            $response['status'] = 0;
            if (getSetting('show_foreign_key_constraint', 'module')) {
                $response['message'] = $e->getMessage();
            } else {
                $response['message'] = getPhrase('this_record_is_in_use_in_other_modules');
            }
        }
        return json_encode($response);
    }
    public function getSkills(Request $request)
    {
        if ($request->course_id < 23) {
            $skills = Skill::where('subject_id', $request->subject_id)->where('course_id',
                $request->course_id)->groupBy('skill_title')->get(['id', 'skill_title']);
        }
        return $skills;
    }
}
