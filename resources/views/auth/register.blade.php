<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $setting?->app_name ?? config('app.name') }} - People</title>  
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Sofadi+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Full height to center the card vertically */
        body, html {
            height: 100%;
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
        @if($message = session('success'))
            <div class="alert alert-success my-2 text-success" role="alert">{{ $message }}</div>
        @endif
        <!-- Section: Design Block -->
        <section class="text-center text-lg-start">
            <div class="card mb-3">
                <div class="row g-0 d-flex align-items-center">
                    <div class="col-lg-4 d-none d-lg-flex">
                        <img src="{{ asset('3.png') }}" alt="Tree Family" class="w-100 rounded-t-5 rounded-tr-lg-0 rounded-bl-lg-5" />
                    </div>
                    <div class="col-lg-8">
                        <div class="card-body py-5 px-md-5">
                            <div class="text-center mb-4 d-flex justify-content-center align-items-center">
                                <img src="{{ asset('logo_ft.png') }}" alt="Logo" class="img-fluid me-3" style="max-width: 100px;"> 
                                <h3 class="mb-0">Register</h3>
                            </div>
                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                 <!-- Phone Number input -->
                                 <div class="form-floating mb-4">
                                    <input type="number" class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" id="phone_number" placeholder="Input" name="phone_number" autocomplete="off">
                                    <label for="floatingInput">Phone Number</label>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- button to send OTP --}}
                                <div class="mb-4 d-grid gap-2">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="sendOtp()">Send Otp</button>
                                </div>
                                <!-- Phone Number input -->
                                <div class="form-floating mb-4">
                                    <input type="number" class="form-control {{ $errors->has('otp_code') ? 'is-invalid' : '' }}" id="floatingInput" placeholder="Input" name="otp_code" autocomplete="off">
                                    <label for="floatingInput">OTP code</label>
                                    @error('otp_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Password input -->
                                <div class="form-floating mb-4 position-relative">
                                    <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" id="password" placeholder="Password" name="password" autocomplete="off">
                                    <label for="password">Password</label>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <span class="eye-icon" id="eyeIconPassword" onclick="togglePassword('password', 'eyeIconPassword')">👁️</span>
                                </div>
                                <!-- Password Confirmation input -->
                                <div class="form-floating mb-4 position-relative">
                                    <input type="password" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" id="password_confirmation" placeholder="Password Confirmation" name="password_confirmation" autocomplete="off">
                                    <label for="password_confirmation">Password Confirmation</label>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <span class="eye-icon" id="eyeIconPasswordConfirmation" onclick="togglePassword('password_confirmation', 'eyeIconPasswordConfirmation')">👁️</span>
                                </div>
                                <div class="d-grid gap-2">
                                    <input type="submit" value="Sign Up" class="btn btn-primary mb-4">
                                </div>
                                <div class="text-center">
                                    <p>Already have an account? <a href="{{ route('login') }}" class="text-decoration-none">Login!</a></p>
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
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.textContent = "🙈"; 
            } else {
                passwordInput.type = "password";
                eyeIcon.textContent = "👁️";
            }
        }
        
        function sendOtp() {
        const phoneNumber = document.getElementById('phone_number').value;

        if (!phoneNumber) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Nomor Telepon harus diisi!',
            });
            return;
        }

        fetch("{{ route('send.otp') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ phone_number: phoneNumber })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Kode OTP telah dikirim!',
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Terjadi kesalahan saat mengirim OTP!, Coba lagi',
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat mengirim OTP!',
            });
            console.error('Error:', error);
        });
    }

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
        });
    @endif

    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            html: `
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
        });
    @endif
    </script>    
</body>
</html>
