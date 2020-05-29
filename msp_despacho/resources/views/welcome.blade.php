<!DOCTYPE html>
<html>
<head>
  <title>Pusher Test</title>
  <script src="{{asset('/externo/jquery.js')}}"></script>
          <script src="{{asset('/externo/ajax.js')}}"></script>
          <script src="{{asset('/externo/bootstrap.js')}}"></script>
  <script src="https://js.pusher.com/5.1/pusher.min.js"></script>
  <script>





    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('26efa85a4f3783833ec2', {
      cluster: 'mt1',
      forceTLS: true
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('UserAdded', function(data) {
      alert(JSON.stringify(data));
    });






  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
  </p>
</body>
</html>
