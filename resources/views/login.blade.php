<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quick Blog - Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
          crossorigin="anonymous">
</head>

<body class="bg-light">

    <!-- Center Container -->
    <div class="container min-vh-100 d-flex justify-content-center align-items-center">

        <!-- Login Card -->
        <div class="card shadow-lg border-0 rounded-4 p-4"
             style="width: 100%; max-width: 420px;">

            <!-- Heading -->
            <div class="text-center mb-4">
                <h1 class="fw-bold text-primary">Quick Blog</h1>
                <p class="text-muted">Welcome back! Please login to your account.</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>

                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                    >
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>

                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >
                </div>

                <!-- Remember Me -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            id="remember"
                            name="remember"
                        >

                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>

                    <a href="#" class="text-decoration-none">
                        Forgot password?
                    </a>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn btn-primary w-100 py-2">
                    Login
                </button>

                <!-- Signup Link -->
                <p class="text-center mt-4 mb-0">
                    Don't have an account?
                    <a href="{{ route('signup') }}" class="text-decoration-none fw-semibold">
                        Sign Up
                    </a>
                </p>

                {{-- back to home --}}

                <p class="text-center mt-4 mb-0">
                    <a href="{{ route('home') }}" class="text-decoration-none fw-semibold">
                        Back to Home
                    </a>
                </p>
            </form>

        </div>

    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9hJ09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>

</body>
</html>