$scope.year_selected             = false;

$scope.selected_academic_id      = '';
$scope.selected_course_parent_id = '';
$scope.selected_course_id        = '';
$scope.selected_year             = '';
$scope.selected_semister         = 0;
$scope.courses_object            = [];
$scope.parent_courses_objects            = [];
$scope.total_semisters           = 0;
$scope.semisters                 = [];
$scope.have_semisters            = false;
$scope.parent_courses            = [];
$scope.courses                   = [];
$scope.parent_selected           = false;
$scope.graduation_page           = false;
$scope.years                     = [];
$scope.result_data               = [];

if($location.absUrl().split('/')[$location.absUrl().split('/').length-2] === "completed")
{
    $scope.graduation_page=true;
}

@if(isset($user_slug))
    $scope.pre_selected_academic_id = '';
    $scope.pre_selected_course_parent_id = '';
    $scope.pre_selected_course_id = '';
    $scope.pre_selected_year = 1;
    $scope.pre_selected_semister = 0;
@endif

$scope.resetYears = function(){

$scope.years           = [];
$scope.selected_year   = null;
$scope.current_year    = null;

$scope.result_data = [];
$scope.resetSemisters();
}

$scope.resetSemisters = function(){

$scope.total_semisters = 0;
$scope.semisters = [];
$scope.have_semisters = false;
$scope.current_semister = '';
$scope.result_data=[];
}

$scope.resetCourses = function(){
$scope.selected_course_id = '';
$scope.courses         = [];
$scope.resetYears();
}

$scope.resetParentCourses = function(){

$scope.parent_courses  = [];
$scope.selected_course_parent_id = '';
$scope.course_parent_id = '';
$scope.resetCourses();
}


$scope.resetFields = function(){

$scope.selected_academic_id = '';
$scope.resetParentCourses();

}

$scope.getAcadmicSemester = function(academic_id)
{
    
if($location.absUrl().split('/')[$location.absUrl().split('/').length-2] === "completed")
{
    
$scope.selected_academic_id = academic_id;
$scope.doCall();
//return;
}
if(academic_id=='')
return;
$scope.resetFields();

$scope.selected_academic_id = academic_id;
route = '{{URL_ACADEMICS_SEMESTER}}';
data= {  _method: 'post',
'_token':httpPreConfig.getToken(),
'academic_id': academic_id
};
$scope.semisters=[];
$scope.have_semisters = false;
$scope.semisters = { "current_semister": "<?php echo getPhrase('select'); ?>","values": ['<?php echo getPhrase('select'); ?>'] };
$scope.semisters.current_semister = {{default_sem(default_year())}}
$scope.semisters.current_semister =  $scope.semisters.current_semister.toString();
httpPreConfig.webServiceCallPost(route, data).then(function(result){
angular.forEach(result.data, function(value, key){
    $scope.semisters.values.push(value.sem_num);
    $scope.have_semisters = true;
console.log(value);
});
 
});
}

$scope.getParentCourses = function(academic_id)
{
    
if($location.absUrl().split('/')[$location.absUrl().split('/').length-2] === "completed")
{
    
$scope.selected_academic_id = academic_id;
$scope.doCall();
//return;
}
if(academic_id=='')
return;
$scope.resetFields();

$scope.selected_academic_id = academic_id;
route = '{{URL_ACADEMICS_COURSES_GET_PARENT_COURSES}}';
data= {  _method: 'post',
'_token':httpPreConfig.getToken(),
'academic_id': academic_id
};

httpPreConfig.webServiceCallPost(route, data).then(function(result){
angular.forEach(result.data, function(value, key){
$scope.parent_courses.push(value.course);
$scope.parent_courses_objects.push(value);
});
if($scope.pre_selected_course_parent_id)
{
index = httpPreConfig.findIndexInData(
$scope.parent_courses,'id',
$scope.pre_selected_course_parent_id
);
$scope.course_parent_id = $scope.parent_courses[index];
$scope.getChildCourses($scope.selected_academic_id, $scope.pre_selected_course_parent_id);
}
});
}

$scope.getChildCourses = function(academic_id, parent_course_id){
$scope.thirdYear=false;
var gr=$scope.parent_courses.filter(function(v){

    return v.id==parent_course_id;
});
 if(gr.length>0){
if(gr[0].graduated_course === 1)
{
$scope.thirdYear=true;
}
 }
if(academic_id=='')
return;

if(parent_course_id=='')
return ;

$scope.resetCourses();

$scope.selected_course_parent_id = parent_course_id;
route = '{{URL_ACADEMICS_COURSES_GET_CHILD_COURSES}}';

data= {   _method: 'post',
'_token':httpPreConfig.getToken(),
'academic_id': academic_id,
'parent_course_id': parent_course_id,
@if(isset($user_id))
'user_id':'{{$user_id}}'
@endif
};
httpPreConfig.webServiceCallPost(route, data).then(function(result){
angular.forEach(result.data, function(value, key){
$scope.courses.push(value.course);
$scope.courses_object.push(value);

});
$scope.parent_selected = true;

if($scope.pre_selected_course_id)
{
index = httpPreConfig.findIndexInData(
$scope.courses,'id',
$scope.pre_selected_course_id
);
$scope.course_id = $scope.courses[index];
$scope.prepareYears($scope.pre_selected_course_id)
}


});
}

$scope.prepareYears = function(course){
 
$scope.resetYears();
if(course==null)
{
return;
}

index = httpPreConfig.findIndexInData($scope.courses, 'id', course);

$scope.selected_course_id = $scope.courses[index].id;
 

if($location.absUrl().split('/')[$location.absUrl().split('/').length-1] === "transfers" || $location.absUrl().split('/')[$location.absUrl().split('/').length-2] === "completed"
|| $location.absUrl().split('/')[$location.absUrl().split('/').length-2]+$location.absUrl().split('/')[$location.absUrl().split('/').length-1] === "studentlist"
|| $location.absUrl().split('/')[$location.absUrl().split('/').length-2]+$location.absUrl().split('/')[$location.absUrl().split('/').length-1] === "detainedlist"
|| $location.absUrl().split('/')[$location.absUrl().split('/').length-3]+$location.absUrl().split('/')[$location.absUrl().split('/').length-2] === "studentresults"
|| $location.absUrl().split('/')[$location.absUrl().split('/').length-1] === "id-cards"
|| $location.absUrl().split('/')[$location.absUrl().split('/').length-2]+$location.absUrl().split('/')[$location.absUrl().split('/').length-1] === "usersimport"
|| $location.absUrl().split('/')[$location.absUrl().split('/').length-3]+$location.absUrl().split('/')[$location.absUrl().split('/').length-2] === "profileedit"
{{-- 
    hashed to show semesters on student attendance report
    || $location.absUrl().split('/')[$location.absUrl().split('/').length-4]+$location.absUrl().split('/')[$location.absUrl().split('/').length-3] === "studentattendance" 
    --}}
|| $location.absUrl().split('/')[$location.absUrl().split('/').length-1] === "record-student"
)
{
    
    $scope.selected_course_id=course;
    
    if( $location.absUrl().split('/')[$location.absUrl().split('/').length-2] === "completed")
    $scope.selected_academic_id =academic_id;
$scope.doCall();

$scope.have_semisters = false;
$scope.years.current_year    = null;
}
else{
$scope.yearChanged(1)
}

}

$scope.yearChanged     = function (year_number) {
$scope.resetSemisters();
$scope.year_selected   = true;

academic_id          = $scope.selected_academic_id;
parent_course_id     = $scope.selected_course_parent_id;
course_id            = $scope.selected_course_id;
$scope.selected_year = year_number;
year                 = year_number;


angular.forEach($scope.parent_courses_objects, function(course, key){
if(course.course.id == $scope.selected_course_parent_id){
angular.forEach(course.semister, function(semister, no){

if(semister.year== year){

if(semister.total_semisters>0)
{

semisters =[];

$scope.semisters = { "current_semister": "<?php echo getPhrase('select'); ?>","values": ['<?php echo getPhrase('select'); ?>'] };
for(i=1; i<=semister.total_semisters; i++)
{
$scope.semisters.values.push(i);
}
{{--$scope.current_semister = 'select';--}}
$scope.total_semisters = semister.total_semisters;
$scope.have_semisters = true;
}
else
{
$scope.total_semisters = 0;
$scope.semisters = [];
$scope.have_semisters = false;
}

}
});
}
});


}

$scope.semisterChanged = function(current_semister){
$scope.selected_semister = current_semister;
$scope.doCall();
}

$scope.setPreSelectedData = function(
academic_id,
parent_course_id,
course_id,
year,
semister) {
$scope.pre_selected_academic_id = academic_id;
$scope.pre_selected_course_parent_id = parent_course_id;
$scope.pre_selected_course_id = course_id;
$scope.pre_year = year;
$scope.pre_semister = semister;
$scope.academic_year = $scope.pre_selected_academic_id;
$scope.selected_academic_id = $scope.academic_year;

$scope.getParentCourses($scope.academic_year);
$scope.getAcadmicSemester($scope.academic_year)
}


