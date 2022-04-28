<?php


namespace App\Repositories;


use App\Http\Controllers\ExcelSettings;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Student;
use App\Scopes\BranchScope;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use stdClass;

class ImportRepository implements ImportRepositoryInterface
{
    public function storeStudents(Request $request) {

        $excel = new ExcelSettings();
        $excel->Upload($request);
        $excel->set_sheet(0);

        $data = $this->setStudents($excel);

        $school = $excel->CellValue(0, 0);
        $branch = Branch::where('name', 'LIKE', "%$school%")->first();
        $type = $excel->CellValue(1, 0);
        $category = Category::where('name', 'LIKE', "%$type%")->first();

        if ($branch && $category) {
            $this->insertStudents($data['students'], $branch->id, $category->id, $request);
            $data['response_message'] = "student_added_successfully";
            return $data;
            $excel->deleteFile();
        } else {
            $data['response_message'] = "unable_to_find_matching_data";
            return $data;
            $excel->deleteFile();
        }
    }

    public function setStudents($excel) {
        $rows = $excel->current_sheet->rows;
        $students = [];
        $already_in = [];
        $x = 0;
        for ($i = 3; $i < $rows; $i++) {
            if ($excel->CellValue($i, 5) == ""
                || $excel->CellValue($i, 4) == ""
                || $this->isExist($excel->CellValue($i, 5), $excel->CellValue($i, 0))
                || !$this->checkCourse($excel->CellValue($i, 3))
                || !$this->checkClass($excel->CellValue($i, 2))
                || $excel->CellValue($i, 2) == ""
                || $excel->CellValue($i, 3) == ""
            ) {
                if ($excel->CellValue($i, 5) != "") {
                    $temp = new \stdClass();
                    $temp->name = $excel->CellValue($i, 4);
                    $temp->id_number = $excel->CellValue($i, 5);
                    if ($this->isExist($excel->CellValue($i, 5), $excel->CellValue($i, 0))) {
                        $temp->message = "id_number_or_email_already_exist";
                    }
                    if (!$this->checkCourse($excel->CellValue($i, 3))) {
                        $temp->message = "invalid_course_name";
                    }
                    if (!$this->checkClass($excel->CellValue($i, 2))) {
                        $temp->message = "invalid_class_name";
                    }
                    if ($excel->CellValue($i, 4) == "") {
                        $temp->message = "no_name_for_this_student";
                    }
                    if ($excel->CellValue($i, 3) == "") {
                        $temp->message = "empty_course_entry";
                    }
                    if ($excel->CellValue($i, 2) == "") {
                        $temp->message = "empty_class_entry";
                    }

                    array_push($already_in, $temp);
                }
                continue;
            }
            $students[$x] = new stdClass();
            $students[$x]->name = $excel->CellValue($i, 4);
            $students[$x]->id_number = $excel->CellValue($i, 5);
            $students[$x]->roll_no = $excel->CellValue($i, 6);
            $students[$x]->email = $excel->CellValue($i, 0);
            $students[$x]->course = $excel->CellValue($i, 3);
            $students[$x]->class = $excel->CellValue($i, 2);
            $students[$x]->phone = $excel->CellValue($i, 1);
            $x++;
        }
        $data['students'] = $students;
        $data['already_in'] = $already_in;
        return $data;
    }

    public function insertStudents($students, $branch, $category, Request $request)
    {
        foreach ($students as $student) {
            $user = new User();
            $user->name = $student->name;
            $user->username = $student->id_number;
            $user->assignRole('student');
            if ($student->email == "") {
                $user->email = $student->id_number . "@" . $_SERVER['HTTP_HOST'];
            } else {
                $user->email = $student->email;
            }
            $user->phone = $student->phone;
            $user->category_id = $category;
            $user->id_number = $student->id_number;
            $user->password = Hash::make($student->id_number);
//            $user->language_id = Language::getDefaultLanguage();
            $user->branch_id = $branch;
            $user->save();

            $one = new Student();
            if ($student->roll_no == "") {
                $one->roll_no = "P" . $user->id;
            } else {
                $one->roll_no = $student->roll_no;
            }
            $one->user_id = $user->id;
            $one->class_id = $student->class_id;
            $one->save();
        }
        return true;
    }
    public function checkCourse($name) {
        //check name of course from course table
    }

    public function checkClass($name) {
        //check name of class from class table
    }

    public function storeStaff(Request $request)
    {

        $excel = new ExcelSettings();
        $excel->Upload($request);
        $excel->set_sheet(0);
        $data = $this->setStaff($excel);
        $this->insertStaff($data['teachers'], $request);

    }

    public function setStaff($excel)
    {
        $rows = $excel->current_sheet->rows;
        $teachers = [];
        $already_in = [];
        $x = 0;
        for ($i = 15; $i < $rows; $i++) {
            if ($excel->CellValue($i, 23) == "" || $this->isExist($excel->CellValue($i, 23), $excel->CellValue($i, 8))) {
                if ($excel->CellValue($i, 23) != "") {
                    $temp = new stdClass();
                    $temp->name = $excel->CellValue($i, 21);
                    $temp->id_number = $excel->CellValue($i, 23);
                    array_push($already_in, $temp);
                }
                continue;
            }
            $teachers[$x] = new stdClass();
            $teachers[$x]->address = $excel->CellValue($i, 2);
            $teachers[$x]->phone = $excel->CellValue($i, 4);
            $teachers[$x]->mobile = $excel->CellValue($i, 5);
            $teachers[$x]->zipcode = $excel->CellValue($i, 6);
            $teachers[$x]->mail = $excel->CellValue($i, 7);
            $teachers[$x]->email = $excel->CellValue($i, 8);
            $teachers[$x]->marital_status = $excel->CellValue($i, 17);
            $teachers[$x]->birthdate = $excel->CellValue($i, 18);
            $teachers[$x]->nationality = $excel->CellValue($i, 19);
            $teachers[$x]->name = $excel->CellValue($i, 21);
            $teachers[$x]->id_number = $excel->CellValue($i, 23);
            $x++;
        }
        $data['teachers'] = $teachers;
        $data['already_in'] = $already_in;
        return $data;
    }

    public function insertStaff($teachers, Request $request)
    {
        foreach ($teachers as $teacher) {
            $user = new User();
            $user->name = $teacher->name;
            $user->username = $teacher->id_number;
            $user->assignRole('staff');

            if ($teacher->email == "") {
                $user->email = $teacher->id_number . "@" . $_SERVER['HTTP_HOST'];
            } else {
                $user->email = $teacher->email;
            }

            $user->phone = $teacher->phone;
            $user->address = $teacher->address;
            $user->category_id = auth()->user()->category_id;
            $user->id_number = $teacher->id_number;
            $user->password = Hash::make($teacher->id_number);
            $user->nationality = $teacher->nationality;
//            $user->default_lang = Language::getDefaultLanguage();
            $user->save();

            $profile['date_of_birth'] = $teacher->birthdate;
            $profile['nationality'] = $teacher->nationality;
            $profile['zipcode'] = $teacher->zipcode;
            $profile['country'] = $teacher->nationality;
            $profile['mobile'] = $teacher->mobile;
            $profile['home_phone'] = $teacher->phone;
            $user->profile()->save($profile);

        }
        return true;
    }

    public function isExist($id, $email)
    {
        $user = User::withoutGlobalScope(BranchScope::class)->where('id_number', $id)->orWhere('email', $email)->first();
        if ($user) {
            return true;
        }
        return false;
    }
}
