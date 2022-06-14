
<Script>



    function pop_it(data) {
        console.log(data);
        if(data.created_by_ip == null){data.created_by_ip = ''}
        if(data.updated_by_ip == null){data.updated_by_ip = ''}
        if(data.created_by_user_name == null){data.created_by_user_name = ''}
        if(data.updated_by_user_name == null){data.updated_by_user_name = ''}
        if(data.created_at == null){data.created_at = ''}
        if(data.updated_at == null){data.updated_at = ''}

        var newWindow = window.open("{{URL::to('/')}}/popup_data",'_blank','location=yes,height=450,width=450,scrollbars=yes,status=yes');
        newWindow.data = data;
    }

</Script>
