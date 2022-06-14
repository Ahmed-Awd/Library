<script src="{{JS}}angular.js"></script>
<script src="{{JS}}ngStorage.js"></script>
<script src="{{JS}}angular-messages.js"></script>
<?php $settingsQuestions = json_decode($settings, true, JSON_UNESCAPED_UNICODE)['questions'];?>

<script>
    var app = angular.module('academia', ['ngMessages']);
</script>
@include('common.angular-factory',array('load_module'=> false))
<script>
    app.filter('removeHTMLTags', function() {
        return function(text) {
            return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
        };
    });

    app.controller('prepareQuestions', function ($scope, $http, httpPreConfig) {
        $scope.main_topic = [];
        $scope.main_topic_count = [];
        @if ($settingsQuestions != null)
            var oneEl = '[';
            <?php $i=0;?>

            @foreach($settingsQuestions as $question)
                oneEl += '{"id":'+'{{trim($question['id'])}}';
                oneEl += ',"subject_id":'+'{{trim($question['subject_id'])}}';
                oneEl += ',"question_id":'+'{{trim($question['question_id'])}}';
                oneEl += ',"marks":'+'{{trim($question['marks'])}}';
                oneEl += ',"topic_id":'+'{{trim($question['topic_id'])}}';
                oneEl += ',"topic_name":'+'"{{trim($question['topic_name'])}}"';
                oneEl += ',"question":'+'<?php echo json_encode(trim(preg_replace('/\s\s+/', ' ', $question['question']))); ?>';
                oneEl += ',"question_type":'+'"{{trim($question['question_type'])}}"';
                oneEl += ',"difficulty_level":'+'"{{trim($question['difficulty_level'])}}"';
                @if($i == count($settingsQuestions)-1)
                    oneEl += ',"subject_title":'+'"{{trim($question['subject_title'])}}"'+'}';
                @else
                    oneEl += ',"subject_title":'+'"{{trim($question['subject_title'])}}"'+'},';
                @endif
                <?php $i++;?>
            @endforeach
                oneEl += ']';
        @endif
        $scope.savedQuestions = [];

        $scope.savedQuestions = [];
        $scope.totalMarks = 0;
        $scope.current_course_sc    = null;
        $scope.current_subject_sc   = null;
        $scope.academic_courses_sc  = [];
        $scope.academic_subjects_sc = [];
        $scope.first_time = true;

        $scope.lastPart = window.location.href.split("/").pop();

        $scope.ifEdit = function () {
            
            if ($scope.lastPart != 'add') {

                $http({
                    method: "GET",
                    url: '{{PREFIX}}' + '/get_quiz_data/' + $scope.lastPart,
                    dataType: "json",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                })
                    .then(function (response) {
                        console.log(response.data);
                        $scope.current_year_sc = response.data.academic_id.toString();
                        $scope.current_sem_sc = response.data.semister.toString();
                        $scope.current_course_sc = response.data.course_parent_id.toString();
                       
                        $scope.current_subject_sc = response.data.subject_id.toString();
                        $scope.get_topics();
                    })
            }
        }

        $scope.parent_course_id="{{$record->details['course_parent_id']}}";
        @include('common.year_sems_js');
        @include('common.course_js');



        $scope.getSubjects = function () {

            if($scope.current_course_sc == null || $scope.current_year_sc == null || $scope.current_sem_sc == null){
                return false;
            }
            $http({
                method:"GET",
                url:'{{PREFIX}}'+'get_subjects/'+$scope.current_year_sc+'/'+$scope.current_sem_sc+'/'+$scope.current_course_sc,
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {

                    $scope.academic_subjects_sc = response.data;
                    if(response.data.length != 0) {
                        
                        $scope.current_subject_sc = response.data[0].subject_id.toString();
						$scope.current_subject_sc = "{{$record->details['subject_realid']}}";
                        $scope.get_topics();
                    }
                    else {$scope.ifEdit();$scope.getSubjects();}
                })

                
        }

        $scope.initAngData = function (data) {

            if (data === undefined)
                return;
            $scope.removeAll();
            $scope.totalMarks = 0;
            if (data == '') {
                $scope.subjectQuestions = [];
                $scope.subject = null;
                return;
            }

            dta = data;
            $scope.savedQuestions = JSON.parse(oneEl);
            $scope.totalMarks = "{{json_decode($settings,true)['total_marks']}}";
            $scope.setItem('saved_questions', $scope.savedQuestions);
            $scope.setItem('total_marks', $scope.total_marks);

        }
        $scope.get_topics = function()
        {

            if($scope.current_course_sc == null || $scope.current_year_sc == null || $scope.current_sem_sc == null || $scope.current_subject_sc == null){
                return false;
            }

            $http({
                method:"GET",
                url:'{{PREFIX}}'+'get_all_topicscount/'+$scope.current_subject_sc+'/'+$scope.current_course_sc+'/'+$scope.current_year_sc+'/'+$scope.current_sem_sc+'/'+$scope.lastPart,
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    $scope.main_topic_count = response.data;
                    
                    console.log($scope.main_topic_count );
                })

            $http({
                method:"GET",
                url:'{{PREFIX}}'+'get_all_topics/'+$scope.current_subject_sc+'/'+$scope.current_course_sc+'/'+$scope.current_year_sc+'/'+$scope.current_sem_sc,
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    $scope.academic_topics_sc=[];
                    $scope.academic_topics_sc = response.data;
                    
                    angular.forEach($scope.academic_topics_sc,function(item){
                    //    console.log(item.parent_id);
                        if(item.parent_id == 0){
                            item.topic_name = "- "+item.topic_name;
                            
                            $scope.main_topic.push(item);
                        }
                    });
                    angular.forEach($scope.main_topic,function (item) {
                        item.sub_topics = [];
                        angular.forEach($scope.academic_topics_sc,function(item2){
                            
                           if(item.id == item2.parent_id){
                            
                            var gr=$scope.main_topic_count.filter(function(v){
                            return v.topic_id==item2.id;
                            });  
                            if(gr.length >0)
                            {
                                item2.total = gr[0].total.toString();
                            }
                            else    {
                                item2.total ="0";}
                               item.sub_topics.push(item2);
                               
                           }

                        });
                    });
                    if(response.data.length != 0) {
                        $scope.current_topic_sc ="";// response.data[0].id.toString();
                        $scope.subjectChanged();
                    }
                    if($scope.first_time) {
                        $scope.ifEdit();
                        $scope.first_time = false;
                        return;
                    }
                })
        }



        $scope.subjectChanged = function () { 
            route = '{{URL_QUIZ_GET_QUESTIONS}}';
            data = {
                _method: 'post',
                '_token': httpPreConfig.getToken(),
                'subject_id': $scope.current_subject_sc,
                'course_id':$scope.current_course_sc,
                'academic_id':$scope.current_year_sc,
                'sem_id':$scope.current_sem_sc,
                'topic_id' : $scope.current_topic_sc
            };

            $scope.topics = [];
            httpPreConfig.webServiceCallPost(route, data).then(function (result) {
                result = result.data;

                $scope.subjectQuestions = [];
                $scope.subject = result.subject;
                $scope.topics = result.topics;
                $scope.topic = $scope.topics[0];
                $scope.subjectQuestions = result.questions;
                $scope.contentAvailable = true;

                $scope.removeDuplicates();

            });
        }

        $scope.removeDuplicates = function () {

            if ($scope.savedQuestions.length <= 0 || $scope.subjectQuestions.length <= 0)
                return;

            angular.forEach($scope.savedQuestions, function (value, key) {
                if (value.subject_id != $scope.subjectQuestions[0].subject_id)
                    return;

                res = httpPreConfig.findIndexInData($scope.subjectQuestions, 'id', value.question_id);
                if (res >= 0) {
                    $scope.subjectQuestions.splice(res, 1);
                }

            });
        }

        $scope.addQuestion = function (question, subject) {

            var record = {};
            record.id = Date.now();
            record.subject_id = subject.id;
            record.question_id = question.id;
            record.marks = question.marks;
            record.topic_id = question.topic_id;
            record.topic_name = question.topic_name;
            record.question = question.question;
            record.subject_title = subject.subject_title;
            record.question_type = question.question_type;
            record.difficulty_level = question.difficulty_level;


            res = httpPreConfig.findIndexInData($scope.savedQuestions, 'question_id', question.id);
            if (res == -1) {
                $scope.savedQuestions.push(record);
                $scope.removeFromSubjectQuestions(question);
            }
            else
                return;

            $scope.totalMarks = parseInt($scope.totalMarks) + parseInt(question.marks);
            //Push record to storage
            $scope.setItem('saved_questions', $scope.savedQuestions);
            $scope.setItem('total_marks', $scope.totalMarks);

        }

        $scope.removeFromSubjectQuestions = function (item) {
            var index = $scope.subjectQuestions.indexOf(item);
            $scope.subjectQuestions.splice(index, 1);
        }

        $scope.addToSubjectQuestions = function (item) {

            if ($scope.subjectQuestions.length) {
                if ($scope.subjectQuestions[0].subject_id == item.subject_id) {
                    res = httpPreConfig.findIndexInData($scope.subjectQuestions, 'id', item.question_id)
                    if (res == -1)
                        $scope.subjectQuestions.push(item);
                }
            }
        }


        /**
         * Set item to local storage with the sent key and value
         * @param {[type]} $key   [localstorage key]
         * @param {[type]} $value [value]
         */
        $scope.setItem = function ($key, $value) {
            localStorage.setItem($key, JSON.stringify($value));
        }

        /**
         * Get item from local storage with the specified key
         * @param  {[type]} $key [localstorage key]
         * @return {[type]}      [description]
         */
        $scope.getItem = function ($key) {
            return JSON.parse(localStorage.getItem($key));
        }


        $scope.get_topic_name = function (topic_id) {
            console.log("things");
            $http({
                method:"GET",
                url:'{{PREFIX}}'+'get_topic_name/'+topic_id,
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    return "test";
                })
            return "test";
        }

        /**
         * Remove question with the sent id
         * @param  {[type]} id [description]
         * @return {[type]}    [description]
         */


        $scope.removeQuestion = function (record) {

            $scope.savedQuestions = $scope.savedQuestions.filter(function (element) {
                if (element.id != record.id)
                    return element;
            });
            $scope.totalMarks = $scope.totalMarks - record.marks;
            $scope.setItem('saved_questions', $scope.savedQuestions);
            $scope.addToSubjectQuestions(record);
        }

        $scope.removeAll = function () {
            $scope.savedQuestions = [];
            $scope.totalMarks = 0;
            $scope.setItem('saved_questions', $scope.savedQuestions);
            $scope.setItem('total_marks', $scope.total_marks);
            $scope.subjectChanged($scope.subject_id);

        }

    });

    app.filter('cut', function () {
        return function (value, wordwise, max, tail) {
            if (!value) return '';

            max = parseInt(max, 10);
            if (!max) return value;
            if (value.length <= max) return value;

            value = value.substr(0, max);
            if (wordwise) {
                var lastspace = value.lastIndexOf(' ');
                if (lastspace != -1) {
                    if (value.charAt(lastspace - 1) == '.' || value.charAt(lastspace - 1) == ',') {
                        lastspace = lastspace - 1;
                    }
                    value = value.substr(0, lastspace);
                }
            }

            return value + (tail || ' …');
        };
    });

    app.filter('searchTopic', function () {
        return function (items, topic) {
            var result = [];
            if (topic && items.length > 0) {
                angular.forEach(items, function (value, key) {
                    if (topic.id == value.topic_id) {
                        result.push(value);
                    }
                });
            }
            return result;
        }

    });

    app.filter('searchQuestion', function () {

        return function (item) {
            questions = JSON.parse(localStorage.getItem('saved_questions'));
            ;

            var result = [];
            if (questions == 'undefined' || questions == null)
                return item;

            angular.forEach(questions, function (value, key) {
                if (item.id != value.question_id) {
                    result.push(item);
                }
            });

            return result;
        }

    });

</script>
