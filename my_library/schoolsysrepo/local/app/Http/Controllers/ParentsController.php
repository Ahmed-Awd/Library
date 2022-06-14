<?php

namespace App\Http\Controllers;

use App;
use App\User;
use DB;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Yajra\Datatables\Datatables;

class ParentsController extends Controller
{
    public function __construct()
    {
        $currentUser = \Auth::user();


        $this->middleware('auth');

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $user = getUserWithSlug();

        if (!checkRole(getUserGrade(7))) {
            prepareBlockUserMessage();
            return back();
        }

        if (!isEligible($user->slug)) {
            return back();
        }

        $data['records'] = false;
        $data['user'] = $user;
        $data['title'] = getPhrase('children');
        $data['active_class'] = 'children';
        $data['layout'] = getLayout();
        return view('parent.list-users', $data);
    }
	public function children()
    {

        $user = getUserWithSlug();

        if (!checkRole(getUserGrade(7))) {
            prepareBlockUserMessage();
            return back();
        }

        if (!isEligible($user->slug)) {
            return back();
        }

        $data['records'] = false;
        $data['user'] = $user;
        $data['title'] = getPhrase('children');
        $data['active_class'] = 'children';
        $data['layout'] = getLayout();
        return view('parent.lession-children', $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */

    public function getDatatable($slug)
    {
        $records = array();
        $user = getUserWithSlug($slug);

        $records = User::select(['name', 'image', 'email', 'slug', 'id'])->where('parent_id', '=', $user->id);
        // ->get();


        return Datatables::of($records)
            ->addColumn('action', function ($records) {
                $buy_package = '';

                if (!isSubscribed('main', $records->slug) == 1) {
                    return '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                           <li><a href="' . URL_USERS_EDIT . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>
						   <li><a href="' . URL_STUDENT_LEASON_PLAN . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("lesson_plans") . '</a></li>
						   <li><a href="' . URL_HOMEWORK_STUDENT.'/' . $records->slug .'"><i class="fa fa-briefcase"></i>'. getPhrase('Homeworks') .'</a></li>
						   <li><a href="' . URL_STUDENT_EXAM_CATEGORIES.'"><i class="fa fa-briefcase"></i>'. getPhrase('exams') .'</a></li>
						   <li><a href="' . URL_STUDENT_LMS_CATEGORIES .'"><i class="fa fa-briefcase"></i>'. getPhrase('lms') .'</a></li>
                        </ul>
                    </div>';
                }
            })
            ->editColumn('name', function ($records) {
                return '<a href="' . URL_USER_DETAILS . $records->slug . '" title="' . $records->name . '">' . ucfirst($records->name) . '</a>';
            })
            ->editColumn('image', function ($records) {
                return '<img src="' . getProfilePath($records->image) . '"  />';
            })
            ->removeColumn('slug')
            ->removeColumn('id')
            ->make();
    }
public function getLessionChildDatatable($slug)
    {
        $records = array();
        $user = getUserWithSlug($slug);

        $records = User::select(['name', 'image', 'email', 'slug', 'id'])->where('parent_id', '=', $user->id);
        // ->get();


        return Datatables::of($records)
            ->addColumn('action', function ($records) {
                $buy_package = '';

                if (!isSubscribed('main', $records->slug) == 1) {
                    return '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                           <li><a href="' . URL_USERS_EDIT . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>
						   <li><a href="' . URL_STUDENT_LEASON_PLAN . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("lesson_plans") . '</a></li>
						   <li><a href="' . URL_HOMEWORK_STUDENT.'/' . $records->slug .'"><i class="fa fa-briefcase"></i>'. getPhrase('Homeworks') .'</a></li>
						   <li><a href="' . URL_STUDENT_EXAM_CATEGORIES.'"><i class="fa fa-briefcase"></i>'. getPhrase('exams') .'</a></li>
						   <li><a href="' . URL_STUDENT_LMS_CATEGORIES .'"><i class="fa fa-briefcase"></i>'. getPhrase('lms') .'</a></li>
                        </ul>
                    </div>';
                }
            })
            ->editColumn('name', function ($records) {
                return '<a href="' . URL_STUDENT_LEASON_PLAN . $records->slug . '" title="' . $records->name . '">' . ucfirst($records->name) . '</a>';
            })
            ->editColumn('image', function ($records) {
                return '<img src="' . getProfilePath($records->image) . '"  />';
            })
            ->removeColumn('slug')
            ->removeColumn('id')
            ->make();
    }

    public function childrenAnalysis()
    {

        $user = getUserWithSlug();

        if (!checkRole(getUserGrade(7))) {
            prepareBlockUserMessage();
            return back();
        }

        if (!isEligible($user->slug)) {
            return back();
        }

        $data['records'] = false;
        $data['user'] = $user;
        $data['title'] = getPhrase('children_analysis');
        $data['active_class'] = 'analysis';
        $data['layout'] = getLayout();
        return view('parent.list-users', $data);
    }

    public function getUserExpenses()
    {

        if (!checkRole(getUserGrade(7))) {
            prepareBlockUserMessage();
            return back();
        }

        $data['records'] = false;
        $data['title'] = getPhrase('academic_expenses');
        $data['active_class'] = 'academic_expenses';
        $data['layout'] = getLayout();
        return view('Dues.student_expenses', $data);
    }

    public function getDatatableExpenses()
    {
        $currentAcademic=new App\Academic();
        $currentAcademic=$currentAcademic->getCurrentAcademic()->id;
        $records = App\DuesPurchase::rightJoin('users', 'users.id', '=', 'dues_purchase.student_id', 'right join')
            ->where('dues_purchase.academic_id', $currentAcademic)
            ->orWhere('dues_purchase.academic_id',null)
            ->select(['users.name', 'users.slug','dues_purchase.specifications'])
            ->where('users.parent_id', Auth::user()->id)
            ->get();
        return Datatables::of($records)
            ->editColumn('name', function ($records) {
                return $records->name;
            })
            ->addColumn('total', function ($records) {
                if(isset($records->specifications))
                {
                    return json_decode($records->specifications, true)['total'];
                }
                $data['record'] = User::where('slug', $records->slug)->first();
                $course_id = App\Student::where('user_id', $data['record']->id)->first(['course_parent_id'])->course_parent_id;
                $data['dues_purchase'] = App\DuesPurchase::where('student_id', $data['record']->id)->where('parent_id',
                    Auth::user()->id)->first();
                $currentAcademicYear = new App\Academic();
                $currentAcademicYear = $currentAcademicYear->getCurrentAcademic()->id;
                $schoolExp = App\AcademicDuesPivot::join('academics_dues', 'academics_dues.id', '=', 'academics_dues_pivot.due_id')
                    ->where('academics_dues_pivot.academic_id', $currentAcademicYear)
                    ->where('academics_dues_pivot.course_parent', $course_id)
                    ->select(['academics_dues_pivot.id', 'academics_dues_pivot.due_type', 'academics_dues_pivot.due_value', 'academics_dues.title'])
                    ->get();
                $data['total'] = 0;
                $data['schoolExpenses'] = $schoolExp;
                if ($data['schoolExpenses'] != null) {
                    foreach ($data['schoolExpenses'] as $value) {
                        if ($value->due_type == 'mandatory') {
                            $data['total'] += $value->due_value;
                        }
                    }
                }
                return $data['total'];
            })
            ->addColumn('payed', function ($records) {
                return isset($records->specifications) ? json_decode($records->specifications, true)['your_money']: '-';
            // })->addColumn('coupon', function ($records) {
            //     $couponValue=isset($records->specifications) ? json_decode($records->specifications, true)['coupon'] : '-';
            //     return '<div style="color: red;">'.$couponValue.'</div>';
            })->addColumn('remained', function ($records) {
                return isset($records->specifications) ? json_decode($records->specifications, true)['remain_purchase'] : '-';
            })->addColumn('action', function ($records) {
                return '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li><a href="' . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("pay") . '</a></li>
                        </ul>
                    </div>';
            })
            ->removeColumn('specifications')
            ->removeColumn('slug')
            ->make();
    }

}
