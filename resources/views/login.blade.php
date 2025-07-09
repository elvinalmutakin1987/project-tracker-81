<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ env('APP_NAME') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            /* background-color: #f8f9fa; */
            background-color: black;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="min-vh-100">
    <div class="login-container">
        <h2 class="text-center mb-3 font-weight-bold">
            <p class="fw-bold">{{ env('APP_NAME') }}</p>
        </h2>
        <form action="{{ route('login.store') }}" method="post">
            @csrf
            @method('POST')
            <div class="mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                    required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                    required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        @if (session('message'))
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Login failed!",
                    text: "Invalid Username or Password",
                    icon: "warning"
                });
            });
        @endif
    </script>
</body>

</html>
