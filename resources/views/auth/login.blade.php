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
            max-width: 450px;
            margin: auto;
            margin-top: auto;
            margin-bottom: auto;
            padding: 30px;
            border-radius: 12px;
            background: #ffffff;
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
        
        .login-method-toggle {
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        
        .login-method-toggle.active {
            background-color: #198754;
            color: white;
        }
    </style>
</head>

<body>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="card login-card">
            <h3 class="text-center mb-3">Welcome Back</h3>
            <p class="text-center text-muted">Access your CapliX dashboard</p>
            
            <!-- Login Method Toggle -->
            <div class="d-flex justify-content-center mb-3">
                <div class="btn-group" role="group" aria-label="Login method">
                    <button type="button" class="btn login-method-toggle active" id="emailLoginBtn" data-method="email">
                        <i class="fas fa-envelope me-2"></i>Email
                    </button>
                    <button type="button" class="btn login-method-toggle" id="phoneLoginBtn" data-method="phone">
                        <i class="fas fa-phone me-2"></i>Phone
                    </button>
                </div>
            </div>
            
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <!-- Email Field -->
                <div class="mb-3 email-field">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               placeholder="Enter email" value="{{ old('email') }}" id="emailInput">
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Phone Field (Hidden by default) -->
                <div class="mb-3 phone-field" style="display: none;">
                    <label class="form-label">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               placeholder="Enter phone number" value="{{ old('phone') }}" id="phoneInput">
                    </div>
                    @error('phone')
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
                    <button type="submit" class="btn btn-success">Login</button>
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
        // Password toggle
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            togglePassword.classList.toggle('fa-eye-slash');
        });

        // Login method toggle
        const emailLoginBtn = document.getElementById('emailLoginBtn');
        const phoneLoginBtn = document.getElementById('phoneLoginBtn');
        const emailField = document.querySelector('.email-field');
        const phoneField = document.querySelector('.phone-field');
        const emailInput = document.getElementById('emailInput');
        const phoneInput = document.getElementById('phoneInput');
        const loginForm = document.getElementById('loginForm');

        emailLoginBtn.addEventListener('click', () => {
            emailLoginBtn.classList.add('active');
            phoneLoginBtn.classList.remove('active');
            emailField.style.display = 'block';
            phoneField.style.display = 'none';
            emailInput.setAttribute('required', 'required');
            phoneInput.removeAttribute('required');
            // Clear phone value when switching to email
            phoneInput.value = '';
        });

        phoneLoginBtn.addEventListener('click', () => {
            phoneLoginBtn.classList.add('active');
            emailLoginBtn.classList.remove('active');
            phoneField.style.display = 'block';
            emailField.style.display = 'none';
            phoneInput.setAttribute('required', 'required');
            emailInput.removeAttribute('required');
            // Clear email value when switching to phone
            emailInput.value = '';
        });

        // Form submission handling
        loginForm.addEventListener('submit', function(e) {
            if (emailField.style.display !== 'none') {
                // If email field is visible, remove phone field from form data
                phoneInput.removeAttribute('name');
            } else {
                // If phone field is visible, remove email field from form data
                emailInput.removeAttribute('name');
            }
        });

        // Set initial state based on previous errors
        @if(old('phone'))
            emailLoginBtn.classList.remove('active');
            phoneLoginBtn.classList.add('active');
            emailField.style.display = 'none';
            phoneField.style.display = 'block';
        @endif

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