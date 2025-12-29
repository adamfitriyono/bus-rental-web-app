<!DOCTYPE html>
<html>
    <head>
        <title>Lupa Password</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="col-md-6">
                    <!-- Back Button -->
                    <div class="mb-3">
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Login
                        </a>
                    </div>
                    
                    <h3 class="mb-4">Lupa Password</h3>
                    
                    @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('forgetpassword.sendlink') }}" method="POST">
                    @csrf
                    <input type="text" class="form-control mb-3" name="email" placeholder="Masukkan email anda" required>
                    <button class="btn btn-success" type="submit">Kirim link reset password</button>
                </form>
            </div>
        </div>
    </body>
</html>
