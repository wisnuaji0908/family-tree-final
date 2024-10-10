<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Family Tree - Login</title>
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Sofadi+One&display=swap" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            background: linear-gradient(to right, #f0f4f8, #e2e7ef);
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: "Poppins", sans-serif;
        }
        .card {
            border-radius: 15px; 
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); 
        }
        .card-body {
            padding: 2rem; 
        }
        h3 {
            font-weight: 600; 
            color: #333; 
        }
        .form-control {
            border-radius: 10px; 
            background-color: #f8f9fa; 
            border: 1px solid #ced4da; 
        }
        .form-control:focus {
            border-color: #007bff; 
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); 
        }
        .btn-primary {
            background-color: #007bff; 
            border: none; 
            border-radius: 10px; 
            padding: 10px; 
        }
        .btn-primary:hover {
            background-color: #0056b3; 
        }
        .text-decoration-none {
            color: #007bff; 
        }
        .text-decoration-none:hover {
            text-decoration: underline;
        }
        .eye-icon {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #007bff;
            font-size: 1.2rem; 
            line-height: 1; 
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
                        <img src="{{ asset('3.png') }}" alt="Tree Family" class="w-100 rounded-start" />
                    </div>
                    <div class="col-lg-8">
                        <div class="card-body py-5 px-md-5">
                            <div class="text-center mb-4 d-flex justify-content-center align-items-center">
                                <img src="{{ asset('logo_ft.png') }}" alt="Logo" class="img-fluid me-3" style="max-width: 100px;"> 
                                <h3 class="mb-0">Login</h3>
                            </div>
                            <form action="{{ route('login') }}" method="POST">
                                @csrf
                                {{-- Notification success --}}
                                <div class="form-floating mb-4">
                                    @if($message = session('success'))
                                        <div class="alert alert-success my-2 text-success" role="alert">{{ $message }}</div>
                                    @endif
                                </div>
                                <!-- Email input -->
                                {{-- <div class="form-floating mb-4">
                                    <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="floatingInput" placeholder="name@example.com" name="email" autocomplete="off">
                                    <label for="floatingInput">Email address</label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div> --}}
                                <div class="input-group mb-3">
                                    <span class="input-group-text">+62</span>
                                    <div class="form-floating">
                                      <input type="number" name="phone_number" class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" id="floatingInput" placeholder="Phone Number">
                                      <label for="floatingInputGroup1">Phone Number</label>
                                      @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                      @enderror
                                    </div>
                                  </div>
                                <!-- Password input -->
                                <div class="form-floating mb-4 position-relative">
                                    <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" id="floatingPassword" placeholder="Password" name="password" autocomplete="off">
                                    <label for="floatingPassword">Password</label>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <span class="eye-icon" id="eyeIcon" onclick="togglePassword()">👁️</span> <!-- Tambahkan ikon mata -->
                                </div>
                                <!-- 2 column grid layout for inline styling -->
                                <div class="row mb-4 align-items-center">
                                    <!-- Checkbox -->
                                    <div class="col-6 d-flex">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="form2Example31" checked/>
                                            <label class="form-check-label" for="form2Example31"> Remember me </label>
                                        </div>
                                    </div>
                                    <div class="col-6 text-end">
                                        <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot password?</a>
                                    </div>
                                </div>
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
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("floatingPassword");
            const eyeIcon = document.getElementById("eyeIcon");
            if (passwordInput.type === "password") {
                passwordInput.type = "text"; 
                eyeIcon.textContent = "🙈"; 
            } else {
                passwordInput.type = "password";
                eyeIcon.textContent = "👁️";  
            }
        }
    </script>    
</body>
</html>
