<style>
    .vl {
        border-left: 2px solid black;
        height: 100%;
        margin-left: 30%;
    }

</style>

<div class="footer-middle">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="widget widget-info">
                    <h4 class="widget-title">Contáctanos</h4>
                    <ul class="contact-info">
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="40" height="40" viewBox="0 0 172 172" style=" fill:#000000;"><g transform=""><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,172v-172h172v172z" fill="none"></path><path d="" fill="none"></path><path d="" fill="none"></path><path d="" fill="none"></path><g fill="#60769a"><path d="M121.69,102.5636c-2.32773,-1.36453 -5.18867,-1.33587 -7.50493,0.04013l-11.7304,6.98893c-2.62587,1.5652 -5.90533,1.38173 -8.31333,-0.4988c-4.1624,-3.2508 -10.86467,-8.7204 -16.69547,-14.5512c-5.8308,-5.8308 -11.3004,-12.53307 -14.5512,-16.69547c-1.88053,-2.408 -2.064,-5.68747 -0.4988,-8.31333l6.98893,-11.7304c1.38173,-2.31627 1.3932,-5.20013 0.02867,-7.52787l-17.21147,-29.40053c-1.6684,-2.84373 -4.98227,-4.24267 -8.1872,-3.4572c-3.1132,0.7568 -7.1552,2.60293 -11.39213,6.8456c-13.26693,13.26693 -20.3132,35.64413 29.57827,85.5356c49.89147,49.89147 72.26293,42.85093 85.5356,29.57827c4.2484,-4.2484 6.0888,-8.29613 6.85133,-11.41507c0.774,-3.1992 -0.602,-6.49013 -3.44,-8.1528c-7.0864,-4.1452 -22.37147,-13.09493 -29.45787,-17.24587z"></path></g></g></g></svg>
                            <a href="tel:+51944999965" target="blank" style="font-size: 25px;">{{$information->information_contact_phone}}</a>
                        </li>
                        @if($information->information_contact_address)
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="60" height="60" viewBox="0 0 172 172" style=" fill:#000000;"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,172v-172h172v172z" fill="none"></path><g fill="#60769a"><path d="M86,11.46667c-25.32987,0 -45.86667,20.5368 -45.86667,45.86667c0,34.4 40.13333,45.86667 40.13333,68.8c0,3.1648 2.56853,5.73333 5.73333,5.73333c3.1648,0 5.73333,-2.56853 5.73333,-5.73333c0,-22.93333 40.13333,-34.4 40.13333,-68.8c0,-25.32987 -20.5368,-45.86667 -45.86667,-45.86667zM86,45.86667c6.33533,0 11.46667,5.13133 11.46667,11.46667c0,6.33533 -5.13133,11.46667 -11.46667,11.46667c-6.33533,0 -11.46667,-5.13133 -11.46667,-11.46667c0,-6.33533 5.13133,-11.46667 11.46667,-11.46667zM34.4,97.46667c-2.3908,0 -4.5322,1.48744 -5.375,3.71771l-17.2,45.86667c-0.65933,1.76586 -0.40572,3.72621 0.66068,5.27421c1.07787,1.548 2.83379,2.47474 4.71432,2.47474h137.6c1.88053,0 3.64219,-0.92674 4.71433,-2.47474c1.07213,-1.54227 1.32001,-3.50835 0.66067,-5.27421l-17.2,-45.86667c-0.8428,-2.23027 -2.9842,-3.71771 -5.375,-3.71771h-11.33229c-3.14187,3.5088 -6.38398,6.71526 -9.43985,9.73099c-0.602,0.5848 -1.17901,1.16234 -1.75808,1.73567h18.55495l12.9,34.4h-121.04948l12.9,-34.4h18.55495c-0.57907,-0.57333 -1.15061,-1.15087 -1.74687,-1.73567c-3.0616,-3.01573 -6.30918,-6.22219 -9.45104,-9.73099z"></path></g></g></svg>
                            <a href="#" target="blank" style="font-size: 14px;">
                                {{$information->information_contact_address}}
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="widget">
                    <h4 class="widget-title">Enlaces de interés</h4>
                    <div class="row">
                        <div class="col-sm-6 col-md-5">
                            <ul class="links">
                                <li><a href="{{ route("tenant.restaurant.menu") }}">Inicio</a></li>
                                <li><a href="{{ route('tenant_detail_cart') }}">Ver Carrito</a></li>
                                @guest
                                <li><a href="{{route('tenant_ecommerce_login')}}" class="login-link">Login</a></li>
                                @else
                                <li><a role="menuitem" href="{{ route('logout') }}" class="login-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Salir
                                </a></li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                @endguest
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="widget">
                    <h4 class="widget-title">Redes Sociales</h4>
                    <div class="social-icons">
                        @if($information->link_facebook)
                            <a href="{{$information->link_facebook}}" class="social-icon" target="_blank"><i class="icon-facebook"></i></a>
                        @endif
                        @if($information->link_twitter)
                            <a href="{{$information->link_twitter}}" class="social-icon" target="_blank"><i class="icon-twitter"></i></a>
                        @endif
                        @if($information->link_youtube)
                            <a href="{{$information->link_youtube}}" class="social-icon" target="_blank"><i class="fab fa-youtube"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="footer-bottom" style="padding-bottom: 2rem;">
        <p class="footer-copyright">Facturador Pro 4. &copy; {{ now()->year }}. Todos los Derechos Reservados</p>
        <img src="{{ asset('porto-ecommerce/assets/images/payments.png') }}" alt="payment methods"
            class="footer-payments">
    </div>
</div>

<div class="modal fade" id="moda-succes-add-product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="alert alert-success" role="alert">
                    <i class="icon-ok"></i> Tu producto se agregó al carrito
                </div>
                <div class="row">
                    <div id="product_added_image" class="col-md-4">
                    </div>
                    <div class="col-md-8">
                        <div id="product_added" class="product-single-details">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('restaurant.detail.cart') }}" class="btn btn-primary text-white">Ir a Carrito</a>
                <button type="button" class="btn btn-warning" data-dismiss="modal">Seguir Comprando</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-already-product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div style="font-size: 2em;" class="alert alert-warning" role="alert">
                <i class="fas fa-exclamation"></i> Tu Producto ya está agregado al carrito.
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('restaurant.detail.cart') }}" class="btn btn-primary text-white">Ir al Carrito</a>
                <button type="button" class="btn btn-warning" data-dismiss="modal">Seguir Comprando</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="login_register_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content"  style="height:600px">
            <div class="modal-body">
                <div class="container-fluid ">
                    <br> <br> <br> <br>
                    <div class="row">
                        <div class="col-md-5 ">
                            <h4 class="title mb-2">Login</h4>
                            <div id="msg_login" class="alert alert-danger" role="alert">
                                Usuario o Contraseña Incorrectos.
                            </div>
                            <form action="#" id="form_login">
                                <div class="form-group">
                                    <label for="email">Correo Electronico:</label>
                                    <input type="email" required class="form-control" id="email"
                                        placeholder="Enter email" name="email">
                                </div>
                                <div class="form-group">
                                    <label for="pwd">Contraseña:</label>
                                    <input type="password" required class="form-control" id="pwd"
                                        placeholder="Enter password" name="password">
                                </div>
                                <button type="submit" class="btn btn-primary">Ingresar</button>
                            </form>
                        </div>
                        <div class="col-md-1 text-center">
                            <div class="vl"></div>
                        </div>
                        <div class="col-md-5">
                            <h4 class="title mb-2">Nuevo Registro</h4>
                            <div id="msg_register" class="alert alert-danger" role="alert">
                                <p id="msg_register_p"></p>
                            </div>
                            <form autocomplete="off" action="#" id="form_register">
                                <div class="form-group">
                                    <label for="email">Nombres:</label>
                                    <input type="text" required autocomplete="off" class="form-control" id="name_reg"
                                        placeholder="Enter name" name="name">
                                </div>
                                <div class="form-group">
                                    <label for="email">Correo Electronico:</label>
                                    <input type="email" required autocomplete="off" class="form-control" id="email_reg"
                                        placeholder="Enter email" name="email">
                                </div>
                                <div class="form-group">
                                    <label for="pwd">Contraseña:</label>
                                    <input type="password" required autocomplete="off" class="form-control" id="pwd_reg"
                                        placeholder="Ingrese contraseña" name="pswd">
                                </div>
                                <div class="form-group">
                                    <label for="pwd">Repita la Contraseña:</label>
                                    <input type="password" required autocomplete="off" class="form-control"
                                        id="pwd_repeat_reg" placeholder="Repita contraseña" name="pswd_rpt">
                                </div>
                                <button type="submit" class="btn btn-primary">Registrarse</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript" src="{{ asset('porto-ecommerce/assets/js/cart.js') }}"></script>
<script type="text/javascript">
    matchPassword();
    submitLogin();
    submitRegister();


    function matchPassword() {
        var password = document.getElementById("pwd_reg"),
            confirm_password = document.getElementById("pwd_repeat_reg");

        function validatePassword() {
            if (password.value != confirm_password.value) {
                confirm_password.setCustomValidity("El Password no coincide.");
            } else {
                confirm_password.setCustomValidity('');
            }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    }

    function submitLogin() {
        $('#msg_login').hide();

        $('#form_login').submit(function (e) {
            e.preventDefault()
            $.ajax({
                type: "POST",
                dataType: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('tenant_ecommerce_login')}}",
                data: $(this).serialize(),
                success: function (data) {
                    if (data.success) {
                        location.reload();
                    } else {
                        $('#msg_login').show();
                    }
                },
                error: function (error_data) {
                    console.log(error_data)
                }
            });
        })

    }

    function submitRegister() {
        $('#msg_register').hide();

        $('#form_register').submit(function (e) {
            e.preventDefault()
            $.ajax({
                type: "POST",
                dataType: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('tenant_ecommerce_store_user')}}",
                data: $(this).serialize(),
                success: function (data) {
                    if (data.success) {
                        location.reload();
                    } else {
                        $('#msg_register').show();
                        $('#msg_register_p').text(data.message)
                    }
                },
                error: function (error_data) {
                    console.log(error_data)
                }
            });
        })
    }

</script>
@endpush
