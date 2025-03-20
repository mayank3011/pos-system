<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Error Page | 403</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}">

    <!-- Bootstrap css -->
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- App css -->
    <link href="{{ asset('backend/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style"/>
    
    <!-- Icons -->
    <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- Head js -->
    <script src="{{ asset('backend/assets/js/head.js') }}"></script>
</head>

<body class="authentication-bg authentication-bg-pattern">
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="error-ghost text-center">
                                <svg class="ghost" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="127.433px" height="132.743px"
                                    viewBox="0 0 127.433 132.743" enable-background="new 0 0 127.433 132.743" xml:space="preserve">
                                    <path fill="#f79fac" d="M116.223,125.064c1.032-1.183,1.323-2.73,1.391-3.747V54.76c0,0-4.625-34.875-36.125-44.375
                                        s-66,6.625-72.125,44l-0.781,63.219c0.062,4.197,1.105,6.177,1.808,7.006c1.94,1.811,5.408,3.465,10.099-0.6
                                        c7.5-6.5,8.375-10,12.75-6.875s5.875,9.75,13.625,9.25s12.75-9,13.75-9.625s4.375-1.875,7,1.25s5.375,8.25,12.875,7.875
                                        s12.625-8.375,12.625-8.375s2.25-3.875,7.25,0.375s7.625,9.75,14.375,8.125C114.739,126.01,115.412,125.902,116.223,125.064z" />
                                    <circle fill="#013E51" cx="86.238" cy="57.885" r="6.667" />
                                    <circle fill="#013E51" cx="40.072" cy="57.885" r="6.667" />
                                </svg>
                            </div>

                            <div class="text-center">
                                <h1 class="mt-4">403</h1>
                                <p class="text-muted mb-0">USER DOES NOT HAVE THE RIGHT PERMISSIONS</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-white-50">Return to <a href="{{ route('dashboard') }}" class="text-white ms-1"><b>Home</b></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer footer-alt">
        2023 - <script>document.write(new Date().getFullYear())</script> &copy; EeasyLearning <a href="" class="text-white-50"></a> 
    </footer>

    <!-- Vendor js -->
    <script src="{{ asset('backend/assets/js/vendor.min.js') }}"></script>
    
    <!-- App js -->
    <script src="{{ asset('backend/assets/js/app.min.js') }}"></script>
</body>

</html>
