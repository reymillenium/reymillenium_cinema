<!--Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="keywords"
          content="Sleet Responsive, Login Form Web Template, Flat Pricing Tables, Flat Drop-Downs, Sign-Up Web Templates, Flat Web Templates, Login Sign-up Responsive Web Template, Smartphone Compatible Web Template, Free Web Designs for Nokia, Samsung, LG, Sony Ericsson, Motorola Web Design"
    />

    <title>Página no encontrada - BlasterSoft</title>

    <script>
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <!-- font files -->
    <link href="//fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Rancho" rel="stylesheet">
    <!-- /font files -->

    <!-- css files -->
    <link href="{{asset('css/404_error_layout_style.css')}}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{asset('css/font-awesome.css')}}" rel="stylesheet" type="text/css" media="all"/>
    <!-- /css files -->

<body>

<div class="container-w3layouts  text-center">
    <div class="agileits-logo">
        <h1>
            <a href="{{url()->previous()}}">
                <span class="fa fa-spinner fa-spin" aria-hidden="true"></span>OOPS!</a>
        </h1>
    </div>
    <h2 class="txt-wthree">error 404</h2>
    <p>Parece que la página que usted está intentando visitar no existe.
        <br> Por favor chequee la URL e inténtelo nuevamente.</p>
    <div class="home">
        <a href="{{route('users.users_page')}}">regresar</a>
    </div>
</div>

<div class="w3_agile-footer">
    <p>Copyright &copy; 2018. All Rights Reserved | Design by
        <a href="http://reiniergarcia.com" target="=_blank">BlasterSoft</a>
    </p>
</div>

</body>

</html>