<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Quick Blog - Sign Up</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
          crossorigin="anonymous">
</head>

<body class="bg-light">

    <!-- Center Container -->
    <div class="container min-vh-100 d-flex justify-content-center align-items-center">

        <!-- Signup Card -->
        <div class="card shadow-lg border-0 rounded-4 p-4"
             style="width: 100%; max-width: 450px;">

            <!-- Heading -->
            <div class="text-center mb-4">
                <h1 class="fw-bold text-primary">Quick Blog</h1>
                <p class="text-muted mb-0">Create your account</p>
            </div>

            <!-- Signup Form -->
            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        name="name"
                        placeholder="Enter your name"
                    >
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                    >
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                    >
                </div>

                <div class="mb-4">
                    <label for="password_confirmation`" class="form-label">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        class="form-control"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Confirm your password"
                    >
                </div>


                <button type="submit" class="btn btn-primary w-100 py-2">
                    Sign Up
                </button>
            </form>
            <!-- Login Link -->
                <p class="text-center mt-4 mb-0">
                    Already have an account?
                    <a href="{{ route('loginView') }}" class="text-decoration-none fw-semibold">
                        Login
                    </a>
                </p>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9hJ09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>

</body>
</html>