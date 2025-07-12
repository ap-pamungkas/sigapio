<div class="login-card">


    <div class="card-header">
        <div class="fire-icon">
            <i class="fas fa-fire"></i>
        </div>
        <h1 class="card-title">SIGAP IO Ketapang</h1>
        {{-- <p class="card-subtitle">Sistem Pemadam Kebakaran Hutan</p> --}}
    </div>

    <div class="card-body">
           @if (session()->has('error'))
    <div class="text-white" > {{ session('error') }}
    </div>
    @endif
        <form wire:submit.prevent="login">
            <div class="mb-4">
                <label for="username" class="form-label">
                    <i class="fas fa-envelope me-1"></i> Username
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="username" class="form-control" id="username" wire:model="username" placeholder="Masukkan username" required>
                </div>
                @error('username')
                <span class="text-danger small mt-1 d-block">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">
                    <i class="fas fa-lock me-1"></i> Password
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" class="form-control" id="password" wire:model="password" placeholder="Masukkan password" required>
                    <button class="btn btn-outline-warning" type="button" id="togglePassword">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
                @error('password')
                <span class="text-danger small mt-1 d-block">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </span>
                @enderror
            </div>

            {{-- <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" wire:model="remember">
                    <label class="form-check-label text-light" for="remember">
                        <i class="fas fa-shield-alt me-1"></i> Ingat saya
                    </label>
                </div>
            </div> --}}

            <div class="d-flex justify-content-between align-items-center mb-3">
                <button type="submit" class="btn btn-login btn-lg flex-grow-1 me-2">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Login
                </button>
                {{-- <a href="#" class="text-warning text-decoration-none small">
                    <i class="fas fa-key me-1"></i>Lupa Password?
                </a> --}}
            </div>
        </form>

        <div class="emergency-badge">
            <i class="fas fa-exclamation-triangle me-1"></i>
            AKSES DARURAT 24/7
        </div>
    </div>

    <div class="smoke-effect"></div>
</div>
