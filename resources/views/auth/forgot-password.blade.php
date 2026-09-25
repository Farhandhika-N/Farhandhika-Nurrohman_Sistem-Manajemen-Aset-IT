<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Sistem Manajemen Aset</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f1f5f9;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 24px 24px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            background: #ffffff;
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4f46e5, #818cf8);
        }

        .form-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 600;
        }

        .input-group-custom {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            transition: 0.2s;
            background: #fff;
        }

        .input-group-custom:focus-within {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        .form-control-custom {
            border: none;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            color: #334155;
            background: transparent;
        }

        .form-control-custom:focus {
            box-shadow: none;
            background: transparent;
        }

        .btn-primary-custom {
            padding: 0.75rem;
            border-radius: 10px;
            background-color: #4f46e5;
            border: none;
            font-weight: 600;
            font-size: 0.95rem;
            color: #ffffff;
            transition: 0.3s;
        }

        .btn-primary-custom:hover {
            background-color: #4338ca;
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">

                <!-- Logo Header di atas Card -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 shadow-sm mb-2" style="width: 48px; height: 48px; background-color: #e0e7ff; color: #4f46e5;">
                        <i class="bi bi-shield-lock fs-4"></i>
                    </div>
                    <h4 class="fw-bold mb-1" style="color: #0f172a; letter-spacing: -0.3px;">Lupa Password?</h4>
                    <p class="text-muted small mb-0">Masukkan email akun Anda, kami kirimkan tautan reset-nya.</p>
                </div>

                <div class="card login-card p-4 p-md-4">

                    @if (session('status'))
                        <div class="alert alert-success d-flex align-items-start gap-2 mb-3" style="font-size: 0.85rem; border-radius: 10px;">
                            <i class="bi bi-check-circle-fill mt-1"></i>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf

                        <!-- Input Email -->
                        <div class="mb-4">
                            <label class="form-label">Email Address</label>
                            <div class="input-group-custom @error('email') border-danger @enderror">
                                <input type="email" name="email" class="form-control form-control-custom @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nama@perusahaan.com" required autofocus>
                            </div>
                            @error('email')
                                <div class="text-danger mt-1" style="font-size: 0.75rem;">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100 shadow-sm d-flex align-items-center justify-content-center gap-2">
                            <span>Kirim Tautan Reset</span>
                            <i class="bi bi-send"></i>
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <a href="{{ route('login') }}" class="text-decoration-none d-inline-flex align-items-center gap-1" style="color: #4f46e5; font-size: 0.85rem; font-weight: 600;">
                            <i class="bi bi-arrow-left"></i> Kembali ke halaman login
                        </a>
                    </div>
                </div>

                <!-- Footer Copyright -->
                <div class="text-center mt-4 text-muted" style="font-size: 0.75rem;">
                    &copy; {{ date('Y') }} IT Division.
                </div>

            </div>
        </div>
    </div>

</body>
</html>
