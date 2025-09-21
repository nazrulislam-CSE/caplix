<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | {{ config('app.name') }}</title>
    <!-- Favicon -->
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" type="image/png">
    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        body,
        html {
            height: 100%;
            background: linear-gradient(to right, #9096f8, #8f94fb);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
            max-width: 400px;
            width: 100%;
            background: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
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
    <div class="register-card">
        <h3 class="text-center mb-3">Join CapliX</h3>
        <p class="text-center text-muted mb-4">Start your investment journey today</p>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <!-- Full Name -->
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                        placeholder="Enter your full name" required>
                </div>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- User Name -->
            <div class="mb-3">
                <label class="form-label">User Name</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="username" value="{{ old('username') }}" class="form-control"
                        placeholder="Enter your username" required>
                </div>
                @error('username')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Referral Code -->
            <div class="mb-3">
                <label class="form-label">Referral Code</label>
                <small id="referMessage" class="mb-2"></small>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                    <input type="text" id="refer_by" name="refer_by" value="{{ $_GET['refer_id'] ?? 'caplix' }}"
                        class="form-control" placeholder="Enter referral code">
                </div>
                @error('refer_by')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                        placeholder="Enter your email" required>
                </div>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="form-control @error('phone') is-invalid @enderror"
                        placeholder="Enter your 11 digit phone number" minlength="11" maxlength="11" pattern="[0-9]{11}"
                        required>
                </div>
                <small class="text-muted">Phone number must be exactly 11 digits.</small>
                @error('phone')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>


            <!-- Password -->
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Create a strong password" required>
                    <span class="input-group-text toggle-password">
                        <i class="fas fa-eye" id="togglePassword"></i>
                    </span>
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                    <input type="password" name="password_confirmation" id="confirmPassword" class="form-control"
                        placeholder="Confirm your password" required>
                    <span class="input-group-text toggle-password">
                        <i class="fas fa-eye" id="toggleConfirmPassword"></i>
                    </span>
                </div>
            </div>

            <!-- Account Type -->
            <div class="mb-3">
                <label class="form-label">Account Type</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                    <select name="role" class="form-select" required>
                        <option value="">-- Select Account Type --</option>
                        <option value="investor" {{ old('role') == 'investor' ? 'selected' : '' }}>
                            Investor - I want to invest money
                        </option>
                        <option value="entrepreneur" {{ old('role') == 'entrepreneur' ? 'selected' : '' }}>
                            Entrepreneur - I need capital
                        </option>
                    </select>

                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus me-1"></i> Create Account
                </button>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-dark">Already have an account? Login</a>
            </div>
        </form>
    </div>

    <script>
        // Password show/hide
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        togglePassword.addEventListener('click', () => {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            togglePassword.classList.toggle('fa-eye-slash');
        });

        // Confirm Password show/hide
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPassword = document.getElementById('confirmPassword');
        toggleConfirmPassword.addEventListener('click', () => {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            toggleConfirmPassword.classList.toggle('fa-eye-slash');
        });
    </script>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#refer_by').on('keyup', function() {
                let username = $(this).val();
                const registerBtn = $('#registerBtn');

                if (username.length > 2) {
                    $.ajax({
                        url: "/check-refer/" + username,
                        type: "GET",
                        data: {
                            username: username
                        },
                        success: function(res) {
                            let messageEl = $('#referMessage');

                            if (res.status) {
                                messageEl.text(res.message).css('color', 'green');
                                registerBtn.prop('disabled', false);
                            } else {
                                messageEl.text(res.message).css('color', 'red');
                                registerBtn.prop('disabled', true);
                            }
                        },
                        error: function() {
                            messageEl.text('Something went wrong').css('color', 'red');
                            registerBtn.prop('disabled', true);
                        }
                    });
                } else {
                    messageEl.text('');
                    registerBtn.prop('disabled', true);
                }
            });

        });
    </script>
    <script>
        // Toastr messages (session)
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>
</body>

</html>
