<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - MyApp</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <style>
        body {
        background: #0055e8;
        background: linear-gradient(180deg, rgba(0, 85, 232, 1) 0%, rgba(67, 224, 133, 1) 50%, rgba(210, 255, 105, 1) 100%);
        background-attachment: fixed;
      }

        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #007bff;
        }

        .btn-login {
            background-color: #007bff; 
            color: white;
        }

        .btn-login:hover {
            background-color: #0056b3;
        }

        .error-text {
            color: red;
            margin-top: 10px;
        }

        h2.page-title {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-container">
        <h2 class="page-title">Armazen De Stocks</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-login">Login</button>
            </div>

            @if($errors->any())
                <div class="error-text">
                    {{ $errors->first() }}
                </div>
            @endif
        </form>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
