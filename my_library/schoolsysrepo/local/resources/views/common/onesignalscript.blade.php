<head>

  <script src="{{JS}}OneSignalSDK.js" async='async'></script>

  <script>

    var OneSignal = window.OneSignal || [];
   
    OneSignal.push(["init", {

      appId: "{{getSetting('one_signal_app_id','push_notifications')}}",

      autoRegister: false, /* Set to true to automatically prompt visitors */

      subdomainName: '{{getSetting('one_signal_subdomain_name','push_notifications')}}',   

      notifyButton: {

          enable: true /* Set to false to hide */

      }

    }]);

  </script>

  