<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('admin/css/style.css')}}">
</head>

<body>

    <!-- Sidebar -->
    @include('investor.body.sidebar')

    <!-- Main Content -->
    <div class="main d-flex flex-column min-vh-100">
        <!-- Header -->
        @include('investor.body.navber')
        <!-- Main Content -->
        @yield('content')
        <!-- Footer -->
        @include('investor.body.footer')
    </div>

    <!-- Mobile Bottom Navigation -->
    <div class="mobile-bottom-nav">
        <a href="{{ route('investor.logout') }}" class="mobile-bottom-nav-item active">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="#" class="mobile-bottom-nav-item">
            <i class="fas fa-users"></i>
            <span>Investors</span>
        </a>
        <a href="#" class="mobile-bottom-nav-item">
            <i class="fas fa-briefcase"></i>
            <span>Projects</span>
        </a>
        <a href="profile.html" class="mobile-bottom-nav-item">
            <i class="fas fa-users"></i>
            <span>Profile</span>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{asset('admin/js/main.js')}}"></script>
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
