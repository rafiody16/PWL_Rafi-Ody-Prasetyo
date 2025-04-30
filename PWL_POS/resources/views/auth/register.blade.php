<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Registration Page</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="adminlte/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="adminlte/dist/css/adminlte.min.css?v=3.2.0">

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

<body class="hold-transition register-page">
    <div class="login-container">
        <div class="login-form">
            <h2>Registrasi Account</h2>
            <p>Selamat datang di Sistem Manajemen PWL POS</p>
            <form action="{{ url('/register') }}" method="POST" id="form-login">
                @csrf
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="nama" id="nama" name="nama" placeholder="Nama" required />
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="username" id="username" name="username" placeholder="Username" required />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required />
                </div>
                {{-- <div class="form-group">
                    <label for="password">Level</label>
                    <select class="form-control" id="level_id" name="level_id" required>
                        <option value="">- Pilih Level -</option>
                        @foreach ($level as $item)
                            <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                        @endforeach
                    </select>
                    @error('level_id')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div> --}}
                <button type="submit">Register</button>
            </form>
        </div>
        <div class="right-image"></div>
    </div>
    <!-- /.register-box -->

    <!-- jQuery -->
    <script src="adminlte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="adminlte/dist/js/adminlte.min.js?v=3.2.0"></script>
    <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
        integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
        data-cf-beacon='{"rayId":"92f985eaa895d7ad","serverTiming":{"name":{"cfExtPri":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"version":"2025.3.0","token":"2437d112162f4ec4b63c3ca0eb38fb20"}'
        crossorigin="anonymous"></script>
</body>

</html>
