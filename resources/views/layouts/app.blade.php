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
    <link href="{{ asset('assets/extra-libs/calendar/calendar.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.css') }}" rel="stylesheet">
    <style type="text/css">
        .fc-event{
            cursor: pointer !important;
        }
    </style>
</head>

<body id='app'>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div id="main-wrapper">
        @include('layouts.components.navbar')
        @include('layouts.components.user.sidenav')
        <div class="page-wrapper">
            @yield('breadcrumb')
            <div class="container-fluid">
                <div>
                    @yield('content')
                </div>
            </div>
            <footer class="footer text-center">
                All Rights Reserved by brand. Designed and Developed by <a href="http://wynkcommunications.com" target="_blank">WYNK Communications</a>.
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        window.User = {!! json_encode([
            'csrfToken' => csrf_token(),
            'user' => Auth::user()
        ]) !!};
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/af.js"></script>
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('dist/js/jquery.ui.touch-punch-improved.js') }}"></script>
    <script src="{{ asset('dist/js/jquery-ui.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('dist/js/waves.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('dist/js/custom.min.js') }}"></script>
    <!-- this page js -->
    <script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/calendar/cal-init.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- <script type="text/javascript" src="https://api.bistri.com/bistri.conference.min.js"></script> -->
    @yield('script')
    <script type="text/javascript">
        $(document).ready(() => {
            $('.time').each(function(i, e) {
                var date = $(e).attr('datetime');
                $(e).html('<span>' + moment(date).format('llll') + '</span>');
            });
        });
    </script>
</body>

</html>
