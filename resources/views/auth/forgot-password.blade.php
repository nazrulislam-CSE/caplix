<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password | {{ config('app.name') }}</title>
  <!-- Favicon -->
  <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" type="image/png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #9096f8, #8f94fb);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }
    .card {
      padding: 2rem;
      border-radius: 1rem;
      max-width: 400px;
      width: 100%;
    }
  </style>
</head>
<body>
  <div class="card shadow">
    <h3 class="text-center mb-3">Forgot Password</h3>
    <p class="text-center text-muted mb-4">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
      <!-- Email -->
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" id="email"  name="email" :value="old('email')" class="form-control" placeholder="Enter your email" required>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-paper-plane me-1"></i> Email Password Reset Link
        </button>
      </div>

      <!-- Back to Login -->
      <div class="text-center mt-3">
        <a href="{{ route('login') }}" class="text-dark"><i class="fas fa-arrow-left me-1"></i> Back to Login</a>
      </div>
    </form>
  </div>
  <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
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
