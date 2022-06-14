<script src="{{JS}}WebAudioRecorder.min.js"></script>
<script>
app.controller('recorderController', function($scope,$http) {
//webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL;
var gumStream; 						//stream from getUserMedia()
var recorder; 						//WebAudioRecorder object
var input; 							//MediaStreamAudioSourceNode  we'll be recording
var encodeAfterRecord = true;
var audioElement;
var saveElement;
var audioContext; //new audio context to help us record
// shim for AudioContext when it's not avb.
var AudioContext = window.AudioContext || window.webkitAudioContext;

$scope.finish = true;
var audio = '';
$scope.student_audio = null;
$scope.init = function(audio) {
  $scope.student_audio = audio;
  if ($scope.student_audio == null) {
    $scope.hasRecord = true;
  }
}

$scope.getSubjects = function () {
    return false;
}

    $scope.startRecording = function(id) {
  var recordButton='recordButton'+id;
  var stopButton = 'stopButton'+id;

  audioElement = document.getElementById("record"+id);
  saveElement = document.getElementById("save"+id);
  audioElement.innerHTML = '';
  saveElement.innerHTML = '';

  var constraints = { audio: true, video:false }

    /*
    	We're using the standard promise based getUserMedia()
    	https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia
	*/
	navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
		/*
			create an audio context after getUserMedia is called
			sampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods
			the sampleRate defaults to the one set in your OS for your playback device

		*/
		audioContext = new AudioContext();
		//assign to gumStream for later use
		gumStream = stream;
		/* use the stream */
		input = audioContext.createMediaStreamSource(stream);
		recorder = new WebAudioRecorder(input, {
		  workerDir: "{{JS}}", // must end with slash
		  encoding: 'mp3',
		  numChannels:2, //2 is the default, mp3 encoding supports only 2
		});

		recorder.onComplete = function(recorder, blob) {
			createDownloadLink(blob,id);
		}
		recorder.setOptions({
		  timeLimit:120,
		  encodeAfterRecord:encodeAfterRecord,
	      ogg: {quality: 0.5},
	      mp3: {bitRate: 160}
	    });

		//start the recording process
		recorder.startRecording();
	}).catch(function(err) {
	  	//enable the record button if getUSerMedia() fails
    	recordButton.disabled = false;
    	stopButton.disabled = true;
	});

	//disable the record button
  $scope.record = true;
}

$scope.stopRecording = function(id) {
	//stop microphone access
	gumStream.getAudioTracks()[0].stop();
  $scope.record = false;
  $scope.finish = false;
	recorder.finishRecording();
}

$scope.deleteRecord = function (id) {
       swal({

           title: "{{getPhrase('are_you_sure')}}?",

           text: "{{getPhrase('you_will_not_be_able_to_recover_this_record')}}!",

           type: "warning",

           showCancelButton: true,

           confirmButtonClass: "btn-danger",

           confirmButtonText: "{{getPhrase('yes').', '.getPhrase('delete_it')}}!",

           cancelButtonText: "{{getPhrase('no').', '.getPhrase('cancel_please')}}!",

           closeOnConfirm: false,

           closeOnCancel: false

         },

         function(isConfirm) {

           if (isConfirm) {

               var token = '{{ csrf_token()}}';

             route = 'recorder/delete';

             $.ajax({

                 url:route,

                 type: 'POST',

                 data: { _token :token, 'id': id},

                 success:function(msg){
                   swal("{{getPhrase('deleted')}}!", "{{getPhrase('your_record_has_been_deleted')}}", "success");
                   $scope.doCall();
                 }
             });
           } else {
             swal("{{getPhrase('cancelled')}}", "{{getPhrase('your_record_is_safe')}} :)", "error");
           }

       });
}

$scope.play = function(id) {
  audio = new Audio('{{UPLOADS}}/student_names/'+id+'.mp3');
  audio.play();
  $scope.isPlaying = true;
};
$scope.pause = function() {
  audio.pause();
  $scope.isPlaying = false;
};

function createDownloadLink(blob,id) {
	var url = URL.createObjectURL(blob);
  var filename = id;
	var au = document.createElement('audio');
	var link = document.createElement('a');
  var upload = document.createElement('a');

	//add controls to the <audio> element
	au.controls = true;
	au.src = url;
  upload.href="#";
  upload.innerHTML = '{{getPhrase('save')}} <span class="glyphicon glyphicon-upload"></span>';
  upload.addEventListener("click", function(event){
      var fd=new FormData();
      fd.append("audio_data",blob, filename);
      var request = {
            method: 'POST',
            url: "{{url('student/recorder')}}",
            data: fd,
            headers: {
                'Content-Type': undefined
            }
        };
        $http(request)
            .then(function success(e) {
              swal("{{getPhrase('record_added_successfully')}}","", "success");
                $scope.doCall();
            }, function error(e) {
            });
})
audioElement.appendChild(au);
saveElement.appendChild(upload);
}
})

</script>
