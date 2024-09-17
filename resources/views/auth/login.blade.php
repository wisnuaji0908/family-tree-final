<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Family Tree - Login</title>
    {{-- bootsrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    {{-- font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Sofadi+One&display=swap" rel="stylesheet">
    <style>
        /* Full height to center the card vertically */
        body, html {
            height: 100%;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #ded4bb;
            font-family: "Poppins", sans-serif;
            font-weight: 400;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Section: Design Block -->
        <section class="text-center text-lg-start">
            <div class="card mb-3">
                <div class="row g-0 d-flex align-items-center">
                    <div class="col-lg-4 d-none d-lg-flex">
                        <img src="{{ asset('3.png') }}" alt="Tree Family" class="w-100 rounded-t-5 rounded-tr-lg-0 rounded-bl-lg-5" />
                    </div>
                    <div class="col-lg-8">
                        <div class="card-body py-5 px-md-5">
                            <form action="{{ route('login') }}" method="POST">
                                @csrf
                                <h3 class="text-center mb-5">Login</h3>
                                {{-- notification success --}}
                                <div class="form-floating mb-4">
                                    @if($message = session('success'))
                                        <div class="alert alert-success my-2 text-success" role="alert">{{ $message }}</div>
                                    @endif
                                </div>
                                <!-- Email input -->
                                <div class="form-floating mb-4">
                                    <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="floatingInput" placeholder="name@example.com" name="email" autocomplete="off">
                                    <label for="floatingInput">Email address</label>
                                </div>
                                <!-- Password input -->
                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" id="floatingPassword" placeholder="Password" name="password" autocomplete="off">
                                    <label for="floatingPassword">Password</label>
                                </div>
                                <!-- 2 column grid layout for inline styling -->
                                <div class="row mb-4 align-items-center">
                                    <!-- Checkbox -->
                                    <div class="col-6 d-flex">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="form2Example31" />
                                            <label class="form-check-label" for="form2Example31"> Remember me </label>
                                        </div>
                                    </div>
                                    <!-- Forgot password -->
                                    <div class="col-6 text-end">
                                        <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot password?</a>
                                    </div>
                                </div>

                                <!-- Submit button -->
                                <div class="d-grid gap-2">
                                    <input type="submit" value="Sign In" class="btn btn-primary mb-4">
                                </div>
                                <div class="text-center">
                                    <p>Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Register!</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Section: Design Block -->
    </div>    
</body>
</html>
