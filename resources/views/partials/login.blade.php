<form action="{{ route('login.attempt') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="d-flex">
        <button type="submit" class="btn btn-primary">Login</button>
        <a class="btn btn-link link-dark" href="{{ route('forgetpassword.index') }}">Lupa Password</a>
    </div>
</form>
