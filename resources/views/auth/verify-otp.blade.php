<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Family Tree - Forgot Password</title>
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    {{-- Font --}}
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

        /* OTP Input Styles */
        .otp-container {
            display: flex;
            justify-content: center;
            gap: 10px; /* Spacing between inputs */
        }
        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 1.5rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            transition: border-color 0.3s;
        }
        .otp-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
            outline: none;
        }

        .otp-input:hover {
            border-color: #007bff;
        }

        .otp-input::-webkit-outer-spin-button,
        .otp-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .otp-input[type="number"] {
            -moz-appearance: textfield;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
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
                            <div class="text-center mb-4 d-flex justify-content-center align-items-center">
                                <img src="{{ asset('logo_ft.png') }}" alt="Logo" class="img-fluid me-3" style="max-width: 100px;"> 
                                <h3 class="mb-0">Verify OTP</h3>
                            </div>
                            <form action="{{ route('otp.verify.post') }}" method="POST" id="otp-form">
                                @csrf
                                {{-- notification success --}}
                                <div class="form-floating mb-4">
                                    @if($message = session('status'))
                                        <div class="alert alert-success my-2 text-success" role="alert">{{ $message }}</div>
                                    @endif
                                </div>
                                <div class="form-floating mb-4">
                                    <input type="text" name="identifier" class="form-control {{ $errors->has('identifier') ? 'is-invalid' : '' }}" id="identifier" value="{{ old('identifier', session('identifier')) }}" placeholder="Masukkan Nomor WhatsApp" readonly>
                                    <label for="identifier">Phone Number</label>
                                    @error('identifier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="otp-container mb-4">
                                    <input type="number" name="otp[]" maxlength="1" class="form-control otp-input" required>
                                    <input type="number" name="otp[]" maxlength="1" class="form-control otp-input" required>
                                    <input type="number" name="otp[]" maxlength="1" class="form-control otp-input" required>
                                    <input type="number" name="otp[]" maxlength="1" class="form-control otp-input" required>
                                    <input type="number" name="otp[]" maxlength="1" class="form-control otp-input" required>
                                    <input type="number" name="otp[]" maxlength="1" class="form-control otp-input" required>
                                </div>
                                @if ($errors->has('otp'))
                                    <div class="alert alert-danger">{{ $errors->first('otp') }}</div>
                                @endif
                                <!-- Submit button -->
                                <div class="d-grid gap-2">
                                    <input type="submit" value="Verify" class="btn btn-primary mb-4">
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
        // Script to handle auto-focus on OTP input fields
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('.otp-input');
  
            inputs.forEach((input, index) => {
                input.addEventListener('input', (event) => {
                    const value = event.target.value;
  
                    // Move to next input if current one is filled
                    if (value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
  
                    // Move back if input is cleared and not at the first input
                    if (value.length === 0 && index > 0) {
                        inputs[index - 1].focus();
                    }
                });
  
                input.addEventListener('keydown', (event) => {
                    // Allow moving back with backspace
                    if (event.key === 'Backspace' && input.value === '' && index > 0) {
                        inputs[index - 1].focus();
                    }
                });
            });
        });
    </script>  
</body>
</html>
