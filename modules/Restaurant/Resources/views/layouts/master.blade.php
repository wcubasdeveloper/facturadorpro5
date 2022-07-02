<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from portotheme.com/html/porto_ecommerce/demo-6/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 07 Sep 2019 03:39:38 GMT -->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Menu</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="ecommerce" />
    <meta name="description" content="eCommerce">
    <meta name="author" content="SW-THEMES">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('porto-ecommerce/assets/images/icons/favicon.ico') }}">

    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="{{ asset('porto-ecommerce/assets/css/bootstrap.min.css') }}">

    <!-- Main CSS File -->
    <link rel="stylesheet" href="{{ asset('porto-ecommerce/assets/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('porto-ecommerce/assets/css/custom.css') }}">

    <link rel="stylesheet" href="{{ asset('porto-ecommerce/assets/css/rating.css') }}">

    <!-- Fontawesome -->
    <link rel="stylesheet" href="{{ asset('porto-ecommerce/assets/font-awesome/css/fontawesome-all.min.css') }}">

    @if (file_exists(public_path('theme/custom_styles_ecommerce.css')))
        <link rel="stylesheet" href="{{ asset('theme/custom_styles_ecommerce.css') }}" />
    @endif

    <link rel="stylesheet" href="{{ asset('porto-light/css/styles_orders.css') }}" />
</head>
<style>


</style>
<body>

    <div class="page-wrapper">

        @include('restaurant::layouts.partials.header')
        <main class="main">

            @yield('content')
        </main><!-- End .main -->

        <footer class="footer">
            @include('restaurant::layouts.partials.footer')
        </footer><!-- End .footer -->
    </div><!-- End .page-wrapper -->

    <div class="mobile-menu-overlay"></div><!-- End .mobil-menu-overlay -->

    <div class="mobile-menu-container">

        @include('restaurant::layouts.partials.mobile_menu')

    </div><!-- End .mobile-menu-container -->

    <div class="newsletter-popup mfp-hide" id="newsletter-popup-form">
        <!-- style="background-image: url(assets/images/newsletter_popup_bg.jpg)" -->
        <div class="newsletter-popup-content">
            <img src="{{ asset('porto-ecommerce/assets/images/logo-black.png') }}" alt="Logo" class="logo-newsletter">
            <h2>BE THE FIRST TO KNOW</h2>
            <p>Subscribe to the Porto eCommerce newsletter to receive timely updates from your favorite products.</p>
            <form action="#">
                <div class="input-group">
                    <input type="email" class="form-control" id="newsletter-email" name="newsletter-email"
                        placeholder="Email address" required>
                    <input type="submit" class="btn" value="Go!">
                </div><!-- End .from-group -->
            </form>
            <div class="newsletter-subscribe">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="1">
                        Don't show this popup again
                    </label>
                </div>
            </div>
        </div><!-- End .newsletter-popup-content -->
    </div><!-- End .newsletter-popup -->

    <a id="scroll-top" href="#top" title="Top" role="button"><i class="icon-angle-up"></i></a>

    <!-- Plugins JS File -->
    <script src="{{ asset('porto-ecommerce/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('porto-ecommerce/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('porto-ecommerce/assets/js/plugins.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('porto-ecommerce/assets/js/main.min.js') }}"></script>

    <script src="{{ asset('porto-ecommerce/assets/js/vue.min.js') }}"></script>

    @stack('scripts')
</body>
</html>

