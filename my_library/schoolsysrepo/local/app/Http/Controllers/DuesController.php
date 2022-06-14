<?php

namespace App\Http\Controllers;

use App\Academic;
use App\AcademicDues;
use App\AcademicDuesPivot;
use App\DuesPurchase;
use App\Payment;
use App\Paypal;
use App\Settings;
use App\Student;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Softon\Indipay\Facades\Indipay;
use Yajra\Datatables\Datatables;

class DuesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getSelection()
    {
        $data['layout'] = getLayout();
        $data['active_class'] = 'academic';
        $data['title'] = getPhrase('academic_expenses');
        return view('Dues.dashboard', $data);
    }

    public function index()
    {
        $data['layout'] = getLayout();
        $data['active_class'] = 'academic';
        $data['title'] = getPhrase('academic_expenses');
        return view('Dues.all_dues', $data);
    }

    public function create()
    {
        $data['layout'] = getLayout();
        $data['active_class'] = 'academic';
        $data['title'] = getPhrase('add_academic_dues');
        $data['academics_years'] = Academic::where('show_in_list', '=', '1')->select([
            'id',
            'academic_year_title'
        ])->get();
        return view('Dues.add-edit', $data);
    }

    public function store(Request $request)
    {
        $toBeDeleted = DB::delete('Delete from academics_dues_pivot where academic_id = ?', [$request->academic_year]);
        if ($request->academic_year == "select" or $request->academic_dues == "select" or $request->due_value == "select" or $request->due_type == "select") {
            flash(getPhrase('error'), getPhrase("you_should_fill_all_fields"), 'error');
            return redirect()->back();
        }
        if ($request->has('due_title')) {
            $due_titles = array_unique($request->due_title);
            for ($i = 0; $i < count($due_titles); $i++) {
                $relation = new AcademicDuesPivot();
                $relation->academic_id = $request->academic_year;
                $relation->course_parent = $request->course_parent;
                $relation->due_id = $due_titles[$i];
                $relation->due_value = $request->due_value[$i];
                $relation->due_type = $request->due_type[$i];
                $relation->user_stamp($request);
                $relation->save();
            }
        }

        flash(getPhrase('success'), getPhrase("saved_successfully"), 'success');
        return redirect()->back();
    }

    public function getDatatable()
    {
        $records = AcademicDuesPivot::select(['id', 'academic_id', 'due_id', 'due_value', 'due_type']);
        //->get();
        return Datatables::of($records)
            ->editColumn('academic_id', function ($record) {
                return Academic::find($record->academic_id)->academic_year_title;
            })
            ->editColumn('due_id', function ($record) {
                return AcademicDues::find($record->due_id)->title;
            })
            ->editColumn('due_type', function ($record) {
                return getPhrase($record->due_type);
            })->addColumn('action', function ($records) {
                return '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li><a href="dues/edit/' . $records->id . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>

                            <li><a href="dues/delete/' . $records->id . '"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>

                        </ul>
                    </div>';
            })
            ->removeColumn('id')
            ->make();
    }

    public function update(Request $request, $id)
    {
        if ($request->academic_year == "select" or $request->academic_dues == "select" or $request->due_value == "select" or $request->due_type == "select") {
            flash(getPhrase('error'), getPhrase("all_fields_should_be_filled"), 'error');
            return redirect()->back();
        }
        $relation = AcademicDuesPivot::find($id);
        $relation->academic_id = $request->academic_year;
        $relation->due_id = $request->academic_dues;
        $relation->due_value = $request->due_value;
        $relation->due_type = $request->due_type;
        $relation->user_stamp($request);
        $relation->update();
        flash(getPhrase('success'), getPhrase("updated_successfully"), 'success');
        return redirect()->back();
    }

    /*public function delete($id)
    {
        if (AcademicDuesPivot::find($id)->delete()) {
            flash(getPhrase('deleted'), getPhrase('deleted_successfully'), 'success');
        } else {
            flash(getPhrase('error'), getPhrase('error_happened'), 'error');
        }
        return redirect()->back();
    }*/

    public function viewParentPurchase($slug)
    {
        $data['layout'] = getLayout();
        $data['active_class'] = 'academic_expenses';
        $data['title'] = getPhrase('parent_purchase');
        $data['record'] = User::where('slug', $slug)->first();
        $course_id = Student::where('user_id', $data['record']->id)->first(['course_parent_id'])->course_parent_id;
        $data['dues_purchase'] = DuesPurchase::where('student_id', $data['record']->id)->where('parent_id',
            Auth::user()->id)->first();

        $schoolExp = AcademicDuesPivot::join('academics_dues', 'academics_dues.id', '=', 'academics_dues_pivot.due_id')
            ->where('academics_dues_pivot.academic_id', default_year())
            ->where('academics_dues_pivot.course_parent', $course_id)
            ->select([
                'academics_dues_pivot.id',
                'academics_dues_pivot.due_type',
                'academics_dues_pivot.due_value',
                'academics_dues.title'
            ])
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
        $data['slug'] = $slug;
        $data['settingsModule'] = Settings::where('key', 'module')->first(['settings_data']);
        $data['settingsModule'] = json_decode($data['settingsModule']->settings_data);
        return view('Dues.parent_purchase', $data);
    }

    public function payGateway(Request $request, $slug)
    {

        $expenses = $request->expenses != null ? $request->expenses : array();
        $expenses_merged = array_merge($expenses, $request->mand);
        $userRecord = User::where('slug', $slug)->first();
        $gateway = trim($request->gateway);
        $items = ' ';
        
        if ($expenses_merged == null) {
            flash(getPhrase('Ooops'), getPhrase('you_must_select_one_item_at_least'), 'error');
            return back();
        }
        foreach ($expenses_merged as $expense) {
            $items .= '-' . explode('/', $expense)[1] . '<br>';
        }
        if ($gateway == 'payu') {
            if (!getSetting('payu', 'module')) {
                flash(getPhrase('Ooops'), getPhrase('this_payment_gateway_is_not_available'), 'error');
                return back();
            }

            $storeInstance = $this->storePurchase($request, $slug);
            $specifications = json_decode($storeInstance->specifications, true);
            $payment = new Payment();
            $payment->slug = $payment->makeSlug(getHashCode());
            $payment->item_name = $items;
            $payment->user_id = $userRecord->id;
            $payment->plan_type = 'academic_expenses';
            $payment->payment_gateway = 'payu';
            $payment->paid_by_parent = 1;
            $payment->cost = $request->your_money;
            if ($specifications['coupon'] != 0) {
                $payment->coupon_applied = 1;
                $payment->discount_amount = $specifications['coupon'];
                $payment->after_discount = $request->your_money - $specifications['coupon'];
            }
            $payment->payment_status = PAYMENT_STATUS_PENDING;
            $token = $payment->slug;
            $payment->save();
            $config = config();
            $payumoney = $config['indipay']['payumoney'];

            $payumoney['successUrl'] = URL_PAYU_PAYMENT_SUCCESS . '?token=' . $token;
            $payumoney['failureUrl'] = URL_PAYU_PAYMENT_CANCEL . '?token=' . $token;

            $user = Auth::user();
            $parameters = [
                'tid' => $token,
                'order_id' => '',
                'firstname' => $user->name,
                'email' => $user->email,
                'phone' => ($user->phone) ? $user->phone : '45612345678',
                'productinfo' => 'academic_expenses',
                'amount' => $request->your_money,
                'surl' => URL_PAYU_PAYMENT_SUCCESS . '?token=' . $token,
                'furl' => URL_PAYU_PAYMENT_CANCEL . '?token=' . $token,
            ];

            return Indipay::purchase($parameters);

            // URL_PAYU_PAYMENT_SUCCESS
            // URL_PAYU_PAYMENT_CANCEL
        } elseif ($gateway == 'paypal') {
            if (!getSetting('paypal', 'module')) {
                flash(getPhrase('Ooops'), getPhrase('this_payment_gateway_is_not_available'), 'error');
                return back();
            }
            $storeInstance = $this->storePurchase($request, $slug);
            $specifications = json_decode($storeInstance->specifications, true);
            $payment = new Payment();
            $payment->slug = $payment->makeSlug(getHashCode());
            $payment->item_name = $items;
            $payment->user_id = $userRecord->id;
            $payment->plan_type = 'academic_expenses';
            $payment->payment_gateway = 'paypal';
            $payment->paid_by_parent = 1;
            $payment->payment_status = PAYMENT_STATUS_PENDING;
            $token = $payment->slug;
            $payment->cost = $request->your_money;
            if ($specifications['coupon'] != 0) {
                $payment->coupon_applied = 1;
                $payment->discount_amount = $specifications['coupon'];
                $payment->after_discount = $request->your_money - $specifications['coupon'];
            }
            $payment->save();

            $paypal = new Paypal();
            $paypal->config['return'] = URL_PAYPAL_PAYMENT_SUCCESS . '?token=' . $token;
            $paypal->config['cancel_return'] = URL_PAYPAL_PAYMENT_CANCEL . '?token=' . $token;
            $paypal->invoice = $token;
            $paypal->add('academic_expenses', $request->your_money); //ADD  item
            $paypal->pay(); //Proccess the payment
        }
        if ($gateway == 'offline') {
            if (!getSetting('offline_payment', 'module')) {
                flash(getPhrase('Ooops'), getPhrase('this_payment_gateway_is_not_available'), 'error');
                return back();
            }
            $data['active_class'] = 'academic_expenses';
            $data['payment_data'] = json_encode(Input::all());
            $data['layout'] = getLayout();
            $data['title'] = getPhrase('offline_payment');
            $data['parent'] = 'done';
            $data['slug'] = $slug;
            return view('payments.offline-payment', $data);

        }
    }

    public function storePurchase($request, $slug, $off = null)
    {
        if ($off != null and isset($request->expenses)) {
            $expenses_merged = array_merge($request->expenses, $request->mand);
        } else {
            if ($off != null) {
                $expenses_merged = $request->mand;
            } else {
                $expenses = $request->expenses != null ? $request->expenses : array();

                $expenses_merged = array_merge($expenses, $request->mand);
            }
        }
        $current_academic_id = new Academic();
        $current_academic_id = $current_academic_id->getCurrentAcademic()->id;
        $parent_id = Auth::user()->id;
        $student_id = User::where('slug', $slug)->first(['id'])->id;
        $checkExistence = DuesPurchase::where('student_id', $student_id)->where('parent_id', $parent_id)->first();
        if ($checkExistence != null) {
            $specifications = json_decode($checkExistence->specifications, true);
            $total = 0;
            $remain = $specifications['remain_purchase'];
            if (isset($request->expenses)) {
                foreach ($expenses_merged as $expense) {
                    if (in_array(explode('/', $expense)[1], ($specifications['dues_title']))) {
                        continue;
                    }
                    $specifications['total'] += explode('/', $expense)[0];
                    $total = $specifications['total'];
                    $remain += explode('/', $expense)[0];
                    $specifications['dues_title'][] = explode('/', $expense)[1];
                }
            }
            $your_money = $request->your_money;
            
            $coupon = $request->coupon;
            /*$specifications['coupon'] += $coupon;*/

            //testing
            if (!isset($specifications['under_review'])) {
                $specifications['under_review'] = 0;
            }
            $specifications['under_review'] += $your_money + $coupon;
            // $specifications['your_money'] += $your_money;
            $specifications['remain_purchase'] = $remain;
            
            
            
            $db_object = array(
                'total' => $specifications['total'],
                'coupon' => $coupon,
                'your_money' => $specifications['your_money'],
                'remain_purchase' => $specifications['remain_purchase'],
                'under_review' => $specifications['under_review'],
                'dues_title' => $specifications['dues_title']
            );

            $db_object = json_encode($db_object);
            $checkExistence->specifications = $db_object;
            if ($checkExistence->update()) {
                flash(getPhrase('success'), getPhrase("payed_successfully"), 'success');
                return $checkExistence;
            }
        }
        $total = 0;
        $dues_titles = array();
        foreach ($expenses_merged as $expense) {
            $total += explode('/', $expense)[0];
            $dues_titles[] = explode('/', $expense)[1];
        }
        $your_money = $request->your_money;
        $coupon = $request->coupon;

        //testing
        $under_review = $your_money + $coupon;
        $remain_purchase = $total ;

        $your_money = 0; // not to be calculated untill confirmation

        if ($remain_purchase < 0) {
            flash(getPhrase('error'), getPhrase("be_sure_of_input"), 'error');
            return redirect()->back();
        }

        $db_object = array(
            'total' => $total,
            'coupon' => $coupon,
            'your_money' => $your_money,
            'under_review' => $under_review,
            'remain_purchase' => $remain_purchase,
            'dues_title' => $dues_titles
        );

        $db_object = json_encode($db_object);
        $dues_purchase_instance = new DuesPurchase();
        $dues_purchase_instance->student_id = $student_id;
        $dues_purchase_instance->parent_id = $parent_id;
        $dues_purchase_instance->academic_id = $current_academic_id;
        $dues_purchase_instance->slug = $dues_purchase_instance->makeSlug(getHashCode());
        $dues_purchase_instance->specifications = $db_object;
        if ($dues_purchase_instance->save()) {
            flash(getPhrase('success'), getPhrase("payed_successfully"), 'success');
            return $dues_purchase_instance;
        }
    }

    public function createRapidExpenses()
    {
        $data['layout'] = getLayout();
        $data['active_class'] = 'academic';
        $data['title'] = getPhrase('add_rapid_expenses');
        return view('Dues.rapid_add', $data);
    }

    public function storeRapidExpenses(Request $request)
    {
        $instance = new AcademicDues();
        $instance->title = $request->title;
        $instance->slug = $instance->makeSlug(getHashCode());
        $instance->user_stamp($request);
        $instance->save();
        flash(getPhrase('success'), getPhrase("saved_successfully"), 'success');
        return redirect()->back();
    }

    public function getAllRapidExpenses()
    {
        $data['layout'] = getLayout();
        $data['active_class'] = 'academic';
        $data['title'] = getPhrase('expenses_items');
        return view('Dues.all-rapid-expenses', $data);
    }

    public function getAllRapidExpensesDatatable()
    {
        $records = AcademicDues::select([
            'id',
            'title',
            'slug',
            'created_by_user',
            'updated_by_user',
            'created_by_ip',
            'updated_by_ip',
            'created_at',
            'updated_at'
        ]);
        // ->get();
        return Datatables::of($records)
            ->editColumn('title', function ($records) {
                return $records->title;
            })->addColumn('action', function ($records) {
                $records->created_by_user_name = User::get_user_name($records->created_by_user);
                $records->updated_by_user_name = User::get_user_name($records->updated_by_user);
                $view = "<li><a onclick='pop_it($records)'><i class=\"fa fa-eye\"></i>" . getPhrase('view_record_history') . "</a></li>";
                return '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li><a href="rapid_edit/' . $records->id . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>

                            <li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->slug . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>' . $view . '

                        </ul>
                    </div>';
            })
            ->removeColumn('id')
            ->removeColumn('slug')
            ->removeColumn('created_by_user')
            ->removeColumn('updated_by_user')
            ->removeColumn('created_by_ip')
            ->removeColumn('updated_by_ip')
            ->removeColumn('created_at')
            ->removeColumn('updated_at')
            ->make();
    }

    public function deleteRapidExpenses($slug)
    {
        try {
            if (!env('DEMO_MODE')) {
                AcademicDues::where('slug', $slug)->delete();
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

    public function editRapidExpenses($id)
    {
        $data['record'] = AcademicDues::find($id);
        $data['layout'] = getLayout();
        $data['active_class'] = 'academic';
        $data['title'] = getPhrase('edit_rapid_expenses');
        return view('Dues.rapid_add', $data);
    }

    public function UpdateRapidExpenses(Request $request, $id)
    {
        $instance = AcademicDues::find($id);
        $instance->title = $request->title;
        $instance->update_stamp($request);
        $instance->update();
        flash(getPhrase('success'), getPhrase("updated_successfully"), 'success');
        return redirect()->back();
    }

    public function getAllexpensesRelated(Request $request)
    {
        $results = AcademicDuesPivot::join('academics_dues', 'academics_dues.id', '=', 'academics_dues_pivot.due_id')
            ->where('academics_dues_pivot.academic_id', $request->academic_id)
            ->where('academics_dues_pivot.course_parent', $request->course_parent)
            ->get(['academics_dues_pivot.due_type', 'academics_dues_pivot.due_value', 'academics_dues.title']);
        return $this->getElementExpenses(null, $results);

    }

    public function getElementExpenses(Request $request = null, $fillables = null)
    {
        $due_types = array('mandatory', 'optional');
        $data['due_types'] = $due_types;
        $data['allDues'] = AcademicDues::select(['id', 'title'])->get();
        if ($request != null and $request->kind == 'emptyFields') {
            return view('Dues.expenses_element', $data);
        }
        if ($fillables != null) {
            $data['fillables'] = $fillables;
            return view('Dues.expenses_element', $data);
        }
    }

    public function updateOfflinePayment(Request $request, $slug)
    {
        $userRecord = User::where('slug', $slug)->first();
        $payment_data = json_decode($request->payment_data);
        $items = '';
        if (count($payment_data) == 6) {
            $expenses_merged = array_merge($payment_data->expenses, $payment_data->mand);
        } else {
            $expenses_merged = $payment_data->mand;
        }
        foreach ($expenses_merged as $expense) {
            $items .= '-' . explode('/', $expense)[1] . '<br>';
        }
        $storeInstance=$this->storePurchase(json_decode($request->payment_data), $slug, 'off');
        $specifications = json_decode($storeInstance->specifications, true);
        $payment = new Payment();
        $payment->slug = $payment->makeSlug(getHashCode());
        $payment->item_name = $items;
        $payment->item_id = $storeInstance->id;
        $payment->user_id = $userRecord->id;
        $payment->plan_type = 'academic_expenses';
        $payment->payment_gateway = 'offline';
        $payment->paid_by_parent = 1;
        $payment->notes = $request->payment_details;
        $payment->payment_status = PAYMENT_STATUS_PENDING;
        $payment->cost = json_decode($request->payment_data)->your_money + $specifications['coupon'];
        if ($specifications['coupon'] != 0) {
            $payment->coupon_applied = 1;
            $payment->discount_amount = $specifications['coupon'];
            $payment->after_discount = json_decode($request->payment_data)->your_money;
        }
        $payment->save();
        flash(getPhrase('success'), getPhrase('your_request_was_submitted_to_admin'), 'overlay');
        return redirect(URL_PAYMENTS_LIST . Auth::user()->slug);
    }
}
