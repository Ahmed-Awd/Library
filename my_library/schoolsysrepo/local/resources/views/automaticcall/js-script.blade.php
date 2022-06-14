<script>
  function autocallRequest (parentID,studentID){
    var requestDate = getDate();
    var token = '{{ csrf_token()}}';
    $.ajax({

        url:"{{url('parent/autocall')}}",

        type: 'post',

        data: {_token :token,
                student_id : studentID,
                parent_id : parentID,
                request_date : requestDate
        },

        success:function(msg){
                swal("","{{getPhrase('The_automatic_call_request_was_successfully_sent')}}", "success");
        },
        error : function (error){
          var msg = error.responseText;
          if(msg == "already called") {
            swal("","{{getPhrase('There_is_an_automatic_call_request_and_the_student_is_being_called')}}!", "error");
          } else if(msg == "student is out") {
            swal("","{{getPhrase('The_student_is_out_of_school_and_can_not_call_again')}}!", "error");
          } else if(msg == "absent") {
            swal("","{{getPhrase('The_student_is_absent_today')}}!", "error");
          } else if(msg == "left") {
            swal("","{{getPhrase('The_student_is_left_today')}}!", "error");
          } else if(msg == "notAttendance") {
            swal("","{{getPhrase('Student_attendance_record_is_not_updated')}}!", "error");
          } else {
            swal("","{{getPhrase('error')}}!", "error");
          }

        }

    });
  }
  function getDate () {
    var today = new Date();
    var day = today.getDate();
    var month = today.getMonth()+1;
    var year = today.getFullYear();

    if(day<10) {
        day = '0'+ day
    }

    if(month<10) {
        month = '0'+ month
    }

    return today = year + '-' + month + '-' + day;
  }
</script>
