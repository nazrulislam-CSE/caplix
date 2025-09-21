<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | {{ config('app.name') }}</title>
    <!-- Favicon -->
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        body,
        html {
            height: 100%;
            background: linear-gradient(to right, #9096f8, #8f94fb);
        }

        .login-card {
            max-width: 400px;
            margin: auto;
            margin-top: auto;
            margin-bottom: auto;
            padding: 30px;
            border-radius: 12px;
            background: #ffffff;
            /* white card */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .form-control::placeholder {
            color: #6c757d;
        }

        .input-group-text {
            background: #f1f1f1;
            border: 1px solid #ced4da;
            color: #495057;
        }

        .toggle-password {
            cursor: pointer;
            background: #f1f1f1;
            border: 1px solid #ced4da;
        }

        a.text-dark:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="card login-card">
            <h3 class="text-center mb-3">Welcome Back</h3>
            <p class="text-center text-muted">Access your CapliX dashboard</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               placeholder="Enter email" value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" placeholder="Enter password" required>
                        <span class="input-group-text toggle-password">
                            <i class="fas fa-eye" id="togglePassword"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Login Buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Login as Investor</button>
                    <button type="submit" class="btn btn-success">Login asEntrepreneur</button>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('register') }}" class="text-dark">Don't have an account? Create Account</a>
                </div>
                @if (Route::has('password.request'))
                    <!-- Forget Password Link -->
                    <div class="text-center mt-2">
                        <a href="{{ route('password.request') }}" class="text-danger">Forgot your password?</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            togglePassword.classList.toggle('fa-eye-slash');
        });

        // Toastr messages (session)
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>
</body>

</html>
