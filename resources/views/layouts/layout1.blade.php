<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

        @if(isset($title))
        <title>{{ $title }}</title>
      @else
        <title>Fliteracy</title>
      @endif

    <!-- Styles -->
    <link href="{{ asset('css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body  id='app'>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div id="main-wrapper">
        @include('layouts.components.navbar')
        @yield('sidenav')
        <div class="page-wrapper">
            <div class="container-fluid">
                <div>
                    @yield('content')
                </div>
            </div>
        </div>
        </div>
        <footer class="footer text-center">
            All Rights Reserved by brand. Designed and Developed by <a href="http://wynkcommunications.com" target="_blank">WYNK Communications</a>.
        </footer>
    <script>
        window.User = {!! json_encode([
            'csrfToken' => csrf_token(),
            'user' => Auth::user()
        ]) !!};
    </script>
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../dist/js/jquery.ui.touch-punch-improved.js"></script>
    <script src="../dist/js/jquery-ui.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="../dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
    <!-- this page js -->
    <script src="../assets/libs/moment/min/moment.min.js"></script>
    <script src="../assets/libs/fullcalendar/dist/fullcalendar.min.js"></script>
    <script src="../dist/js/pages/calendar/cal-init.js"></script>
    <script src="../js/app.js"></script>
    <script type="text/javascript" src="https://api.bistri.com/bistri.conference.min.js"></script>
    <script type="text/javascript">
        $(document).ready(() => {
            $.ajax({url: "/api/notifications", data: {
                api_key: User.user.api_key
            }, success: function(result){
                ref = $('#notification');
                for (var i = 0; i < result.data.length; i++) {
                    ref.append('<a class="dropdown-item" href="javascript:void(0)">'+result.data[i].message+' <br> <small>' + moment(result.data[i].created_at, "YYYYMMDD").fromNow() + '</small></a>');
                }
                if (result.data.length > 0) {
                    ref.append('<a class="dropdown-item" href="javascript:void(0)">Delete all</a>')
                }
            }});
        });
    </script>
</body>
</html>
