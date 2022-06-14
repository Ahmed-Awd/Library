<script src="{{JS}}angular.js"></script>
<script src="{{JS}}angular-messages.js"></script>
<script src="{{JS}}ng-file-upload.js"></script>
<script src="{{JS}}angular-toastr.min.js"></script>
<script src="{{JS}}angular-toastr.tpls.min.js"></script>

<script>
    var app = angular.module('academia', ['ngMessages', 'ngFileUpload', 'toastr']);


    app.controller('homeworkCtrl', function ($scope, $http, Upload, toastr) {

        $scope.bupload = true;
        $scope.me      = {{Auth::user()->id}};
        console.log($scope.me);
        $scope.file_name = [];

        $scope.upload_file = function ($files) {
            $scope.bupload = false;
            var file = $files;
            extn = [];
            for(i=0;i<file.length;i++){
                extn[i] =  "."+file[i].name.split(".").pop();
            }
            $http({
                method:"GET",
                url:'{{PREFIX}}'+'available_Massages_extn',
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    $scope.av_extn = response.data.split(',');
                    flag = true;
                    for(i=0;i<extn.length;i++){
                        if(!$scope.av_extn.includes(extn[i])){
                            flag = false;
                        }
                    }
                    if(flag == true){
                        $('#progressbar').show();
                        $scope.valid = "ok";
                        Upload.upload({
                            url: '{{PREFIX}}messages/upload',
                            dataType: "json",
                            file: file,
                            method: "POST",
                            headers: {'Content-Type': undefined}
                        }).progress(function (e) {
                            var progress = (e.loaded / e.total) * 100;
                            $("#progressbar_2").css('width', progress + '%');
                        }).then(function (response, status, headers, config) {
                            console.log(response.data);
                            if (response.data.state == "failed") {
                                toastr.error(response.data.desc, 'Error');
                            }
                            if (response.data.state == "success") {

                                toastr.success(response.data.desc);
                                $scope.bupload = true;
                                $scope.uploaded_file = response.data.file;
                            }
                            //$scope.file_show = "{{ EXAM_UPLOADS}}" + response.data.file;
                            angular.forEach(response.data.files, function(value, key) {
                                $scope.file_name.push(value)
                            });
                            $('#progressbar').hide();
                            $('#upload1').val('');
                        });
                    }else{
                        $scope.valid = "no";
                        $scope.upload1 = "";
                        $scope.massage = "invalid file type";
                    }
                })
        }

        $scope.deleteFile = function () {

            angular.forEach($scope.file_name, function(value, key) {
                $http({
                    method:"delete",
                    url:'{{MSG_FILE_DELETE}}'+value,
                    dataType:"json",
                    headers:{'Content-Type': 'application/x-www-form-urlencoded'}
                })
                    .then(function (response) {
                        $scope.file_name = false;
                    })
            });
        }


        $scope.keyChanged = function () {
            if($scope.key == "" || $scope.key == undefined){
                key = "all";
            }else { key = $scope.key }
            $http({
                method:"get",
                url:'{{URL_MESSAGES_SEARCH}}'+key,
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    $scope.heads = response.data;
                    console.log($scope.heads);
                })
        }
        $scope.keyChanged();

        $scope.destroy = function(id) {
            swal({
                    title: "{{getPhrase('are_you_sure')}}?",
                    text: "{{getPhrase('you_will_not_be_able_to_recover_this_message')}}!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "{{getPhrase('yes').', '.getPhrase('delete_it')}}!",
                    cancelButtonText: "{{getPhrase('no').', '.getPhrase('cancel_please')}}!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function (isConfirm) {
                    if (isConfirm) {
                        var token = '{{ csrf_token()}}';
                        route = '{{PREFIX}}messages/destroy/' + id;
                        $.ajax({
                            url: route,
                            type: 'post',
                            data: {_method: 'get', _token: token},
                            success: function (msg) {
                                result = $.parseJSON(msg);
                                if (typeof result == 'object') {
                                    status_message = '{{getPhrase('deleted')}}';
                                    status_symbox = 'success';
                                    status_prefix_message = '';
                                    if (!result.status) {
                                        status_message = '{{getPhrase('sorry')}}';
                                        status_prefix_message = '{{getPhrase("cannot_delete_this_record_as")}}\n';
                                        status_symbox = 'info';
                                    }
                                    swal(status_message + "!", status_prefix_message + result.message, status_symbox);
                                }
                                else {
                                    swal("{{getPhrase('deleted')}}!", "{{getPhrase('your_message_has_been_deleted')}}", "success");
                                }
                                $scope.keyChanged();
                            }
                        });
                    } else {
                        swal("{{getPhrase('cancelled')}}", "{{getPhrase('your_message_is_safe')}} :)", "error");
                    }
                });
        }
        
        $scope.archive = function (id) {
            swal({
                    title: "{{getPhrase('are_you_sure')}}?",
                    text: "{{getPhrase('this_message_will_be_archived')}}!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "{{getPhrase('yes').', '.getPhrase('archive')}}!",
                    cancelButtonText: "{{getPhrase('no').', '.getPhrase('cancel_please')}}!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function (isConfirm) {
                    if (isConfirm) {
                        var token = '{{ csrf_token()}}';
                        route = '{{PREFIX}}messages/archive/' + id;
                        $.ajax({
                            url: route,
                            type: 'post',
                            data: {_method: 'get', _token: token},
                            success: function (msg) {
                                result = $.parseJSON(msg);
                                if (typeof result == 'object') {
                                    status_message = '{{getPhrase('deleted')}}';
                                    status_symbox = 'success';
                                    status_prefix_message = '';
                                    if (!result.status) {
                                        status_message = '{{getPhrase('sorry')}}';
                                        status_prefix_message = '{{getPhrase("cannot_delete_this_record_as")}}\n';
                                        status_symbox = 'info';
                                    }
                                    swal(status_message + "!", status_prefix_message + result.message, status_symbox);
                                }
                                else {
                                    swal("{{getPhrase('archived')}}!", "{{getPhrase('your_message_has_been_archived')}}", "success");
                                }
                                $scope.keyChanged();
                            }
                        });
                    } else {
                        swal("{{getPhrase('cancelled')}}", "{{getPhrase('your_message_is_safe')}} :)", "error");
                    }
                });
        }

    });


function destroy(id) {
    swal({
            title: "{{getPhrase('are_you_sure')}}?",
            text: "{{getPhrase('you_will_not_be_able_to_recover_this_message')}}!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "{{getPhrase('yes').', '.getPhrase('delete_it')}}!",
            cancelButtonText: "{{getPhrase('no').', '.getPhrase('cancel_please')}}!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function (isConfirm) {
            if (isConfirm) {
                var token = '{{ csrf_token()}}';
                route = '{{PREFIX}}messages/destroy/' + id;
                $.ajax({
                    url: route,
                    type: 'post',
                    data: {_method: 'get', _token: token},
                    success: function (msg) {
                        result = $.parseJSON(msg);
                        if (typeof result == 'object') {
                            status_message = '{{getPhrase('deleted')}}';
                            status_symbox = 'success';
                            status_prefix_message = '';
                            if (!result.status) {
                                status_message = '{{getPhrase('sorry')}}';
                                status_prefix_message = '{{getPhrase("cannot_delete_this_record_as")}}\n';
                                status_symbox = 'info';
                            }
                            swal(status_message + "!", status_prefix_message + result.message, status_symbox);
                        }
                        else {
                            swal("{{getPhrase('deleted')}}!", "{{getPhrase('your_message_has_been_deleted')}}", "success");
                        }
                        location.reload();
                    }
                });
            } else {
                swal("{{getPhrase('cancelled')}}", "{{getPhrase('your_message_is_safe')}} :)", "error");
            }
        });
}

</script>