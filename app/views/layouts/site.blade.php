<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" itemscope itemtype="http://schema.org/Product"> <!--<![endif]-->
<head>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    @yield('header_meta')

    <link rel="shortcut icon" href="http://fideloper.com/favicon.png" type="image/x-icon" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png" />

    <link type="text/plain" rel="author" href="http://fideloper.com/humans.txt" />

    <link rel="stylesheet" href="/static/css/styles.css">
    <!--[if lte IE 8]>
    <script src="/static/jsmodernizr-2.6.2.min.js"></script>
    <![endif]-->
</head>

<body>

    <div class="container">
        @yield('adminhead')

        @section('header')
        <header id="brand">
            <article class="wide">
                <h1><a href="/">Fideloper</a></h1>
            </article>
        </header>
        @show

        <section>
            @yield('content')
            <aside>
                <a href="https://twitter.com/fideloper" class="twitter-follow-button" data-show-count="true">Follow @fideloper</a>
                @section('sidebar')
                @show
            </aside>
        </section><!-- end  #content -->

    @section('footer')
    <footer id="foot">
        <div class="container">
            <section>
                <ul class="wide">
                    <li><a href="/feed"><i class="entypo rss"></i>RSS</a></li>
                    <li><a href="https://twitter.com/fideloper"><i class="entypo-social twitter"></i>@fideloper</a></li>
                    <li><a href="https://github.com/fideloper"><i class="entypo-social github"></i>fideloper</a></li>
                </ul>
            </section>
        </div>
    </footer>
    @show

    </div><!-- end container -->

    @yield('scripts')

</body>
</html>
