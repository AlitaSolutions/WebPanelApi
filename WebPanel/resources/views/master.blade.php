
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Web Panel</title>

    <link rel="stylesheet" type="text/css" href="{{asset('semantic/semantic.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semantic/components/reset.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semantic/components/site.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semantic/components/container.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semantic/components/grid.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semantic/components/header.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semantic/components/image.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semantic/components/menu.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semantic/components/divider.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semantic/components/dropdown.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semantic/components/segment.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semantic/components/button.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semantic/components/list.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semantic/components/icon.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semantic/components/sidebar.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semantic/components/transition.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semantic/components/input.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semantic/components/dropdown.min.css')}}">
    <style type="text/css">

        .hidden.menu {
            display: none;
        }

        .masthead.segment {
            min-height: 700px;
            padding: 1em 0em;
        }
        .masthead .logo.item img {
            margin-right: 1em;
        }
        .masthead .ui.menu .ui.button {
            margin-left: 0.5em;
        }
        .masthead h1.ui.header {
            margin-top: 3em;
            margin-bottom: 0em;
            font-size: 4em;
            font-weight: normal;
        }
        .masthead h2 {
            font-size: 1.7em;
            font-weight: normal;
        }

        .ui.vertical.stripe {
            padding: 8em 0em;
        }
        .ui.vertical.stripe h3 {
            font-size: 2em;
        }
        .ui.vertical.stripe .button + h3,
        .ui.vertical.stripe p + h3 {
            margin-top: 3em;
        }
        .ui.vertical.stripe .floated.image {
            clear: both;
        }
        .ui.vertical.stripe p {
            font-size: 1.33em;
        }
        .ui.vertical.stripe .horizontal.divider {
            margin: 3em 0em;
        }

        .quote.stripe.segment {
            padding: 0em;
        }
        .quote.stripe.segment .grid .column {
            padding-top: 5em;
            padding-bottom: 5em;
        }

        .footer.segment {
            padding: 5em 0em;
        }

        .secondary.pointing.menu .toc.item {
            display: none;
        }

        @media only screen and (max-width: 700px) {
            .ui.fixed.menu {
                display: none !important;
            }
            .secondary.pointing.menu .item,
            .secondary.pointing.menu .menu {
                display: none;
            }
            .secondary.pointing.menu .toc.item {
                display: block;
            }
            .masthead.segment {
                min-height: 350px;
            }
            .masthead h1.ui.header {
                font-size: 2em;
                margin-top: 1.5em;
            }
            .masthead h2 {
                margin-top: 0.5em;
                font-size: 1.5em;
            }
        }


    </style>

</head>
<body>

<!-- Following Menu -->
<div class="ui large top fixed menu">
    <div class="ui container">
        <a class="item" href="{{action("MainController@index")}}">Main</a>
        <a class="item" href="{{action("PlatformController@index")}}">Platforms</a>
        <a class="item" href="{{action("ServiceController@index")}}">Services</a>
        <a class="item" href="{{action("PropertyController@index")}}">Properties</a>
        <a class="item" href="{{action("ServerController@index")}}">Servers</a>
        <a class="item" href="{{action("GroupController@index")}}">Groups</a>
        <a class="item" href="{{action("TagsController@index")}}">Tags</a>
        <a class="item" href="{{action("DeviceController@index")}}">Devices</a>
        <a class="item" href="{{action("SettingsController@index")}}">Settings</a>
        <div class="right menu">
            <div class="item">
                <a class="ui primary button" href="{{ route('logout') }}"  onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Sign Out</a>
            </div>
        </div>
    </div>
</div>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>


<!-- Page Contents -->
<div class="pusher">
    @yield('content')
</div>
<script src="{{asset("js/jquery.min.js")}}"></script>
<script src="{{asset("semantic/semantic.min.js")}}"></script>
<script src="{{asset("semantic/components/visibility.min.js")}}"></script>
<script src="{{asset("semantic/components/sidebar.min.js")}}"></script>
<script src="{{asset("semantic/components/transition.min.js")}}"></script>
<script>
    $(document)
        .ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // fix menu when passed
            $('.masthead')
                .visibility({
                    once: false,
                    onBottomPassed: function() {
                        $('.fixed.menu').transition('fade in');
                    },
                    onBottomPassedReverse: function() {
                        $('.fixed.menu').transition('fade out');
                    }
                })
            ;

            // create sidebar and attach to menu open
            $('.ui.sidebar')
                .sidebar('attach events', '.toc.item')
            ;

        })
    ;
</script>
@yield('scripts')
</body>

</html>
