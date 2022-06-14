<div class="panel-heading">

    <h2>{{getPhrase('saved_questions')}}</h2>

    <div class="crearfix selected-questions-details">

        <center><span>{{ $record->details['course']->course_title  }} - {{ $record->details['subject']->subject_title }} - {{ getPhrase(SemesterName($record->details['sem'])) }}</span></center>
        <span class="pull-left">{{getPhrase('saved_questions')}} (@{{savedQuestions.length}})</span>

        <span class="pull-right">{{getPhrase('total_marks')}}: @{{ totalMarks }}</span>

    </div>


</div>

{!! Form::open(array('url' => URL_QUIZ_UPDATE_QUESTIONS.$record->slug, 'method' => 'POST')) !!}

<input type="hidden" name="saved_questions" value="@{{savedQuestions}}">

<div class="panel-body">

    <div class="row">


        <div class="col-md-12 clearfix">

            <div ng-if="savedQuestions.length > 0" class="vertical-scroll">

                <a class="remove-all-questions text-red" style="cursor: pointer;"
                   ng-click="removeAll()">{{getPhrase('remove_all')}}</a>

                <table

                        class="table table-hover">

                    <thead>

                    <tr>

                        <th>{{getPhrase('subject')}}</th>

                        <th>{{getPhrase('question')}}</th>

                        <th>{{getPhrase('topic')}}</th>

                        <th>{{getPhrase('marks')}}</th>

                        <th></th>

                    </tr>

                    </thead>

                    <tbody>

                    <tr ng-repeat="i in savedQuestions track by $index">

                        <td>@{{ savedQuestions[$index].subject_title}}</td>

                        <td title="@{{ savedQuestions[$index].question | removeHTMLTags}}">@{{ savedQuestions[$index].question | removeHTMLTags }}</td>

                        <td> @{{ savedQuestions[$index].topic_name }}</td>

                        <td>@{{ savedQuestions[$index].marks}}</td>

                        <td><a ng-click="removeQuestion(i)" style="cursor: pointer;"
                               class="btn-outline btn-close text-red"><i class="fa fa-close"></i></a></td>

                    </tr>

                    </tbody>

                </table>

            </div>

            <div class="buttons text-center">

                <button class="btn btn-lg btn-success button">{{getPhrase('update')}}</button>

            </div>

        </div>

    </div>

</div>


{!! Form::close() !!}

