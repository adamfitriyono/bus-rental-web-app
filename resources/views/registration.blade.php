<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <title>Registrasi</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="mainNav">
            <div class="container px-4">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <i class="fas fa-bus me-2"></i>Sistem Penjualan Tiket Bus
                </a>
            </div>
        </nav>
        <div class="container mt-4 px-3">
            <div class="row justify-content-center">
                <div class="col col-md-5">
                    <!-- Back Button -->
                    <div class="mb-3">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                        </a>
                    </div>
                    
                    <h2 class="mb-4">Daftar Akun Baru</h2>
                    
                    <form action="{{ route('register.store') }}" method="POST">
                        @csrf
                        <div class="form-floating mb-2">
                            <input type="text" name="name" class="form-control" id="floatingName" placeholder="Nama" value="{{ old('name') }}" required>
                            <label for="floatingName">Nama Lengkap</label>
                        </div>
                        <div class="form-floating mb-2">
                            <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
                            <label for="floatingInput">Email</label>
                        </div>
                        @error('email')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="form-floating mb-2">
                            <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                            <label for="floatingPassword">Password</label>
                            <small class="form-text text-muted">Minimal 6 karakter</small>
                        </div>
                        @error('password')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="form-floating mb-2">
                            <input type="password" name="password_confirmation" class="form-control" id="floatingPasswordConfirm" placeholder="Konfirmasi Password" required>
                            <label for="floatingPasswordConfirm">Konfirmasi Password</label>
                        </div>
                        @error('password_confirmation')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mb-2">No Telepon disarankan menggunakan nomor yang terhubung dengan whatsapp.</small>
                        <div class="form-floating mb-2">
                            <input type="text" name="nomor_hp" class="form-control" id="floatingtelp" placeholder="Nomor Telepon" value="{{ old('nomor_hp') }}" required>
                            <label for="floatingtelp">No Telepon</label>
                        </div>
                        @error('nomor_hp')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="btn btn-success w-100 mt-4">Daftar</button>
                    </form>
                    <div class="d-flex w-100 mt-3">
                        Sudah punya akun? Silakan &nbsp;<a class="link-dark" href="{{ route('login') }}">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
