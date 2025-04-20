<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Pengguna</title>

    <!-- Fonts & Styles -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Source Sans Pro', sans-serif;
        }

        body,
        html {
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100%;
        }

        .login-container {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        .login-form {
            flex: 1;
            background-color: #f7f7f7;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form h2 {
            margin-bottom: 20px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .login-form button {
            padding: 12px;
            border: none;
            background-color: #213448;
            color: #fff;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        .login-form .remember-me {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .login-form .remember-me input {
            margin-right: 8px;
        }

        .right-image {
            flex: 1;
            background: url('gudang.jpeg') no-repeat center center;
            background-size: cover;
        }

        @media (max-width: 768px) {
            .right-image {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-form">
            <h2>Login to PWL POS</h2>
            <p>Selamat datang di Sistem Manajemen PWL POS</p>
            <form action="{{ url('login') }}" method="POST" id="form-login">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="username" id="username" name="username" placeholder="Username" required />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required />
                </div>
                <button type="submit">Log In</button>
            </form>
            <p class="mt-3 mb-1 text-left">
                <a href="{{ url('register') }}" class="text-left">Create Account</a>
            </p>
        </div>
        <div class="right-image"></div>
    </div>

    <!-- JS -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $("#form-login").validate({
                rules: {
                    username: {
                        required: true,
                    },
                    password: {
                        required: true,
                        minlength: 6
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                }).then(function() {
                                    window.location = response.redirect;
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                }
            });
        });
    </script>
</body>

</html>
