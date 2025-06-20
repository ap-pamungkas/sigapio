<?php

namespace App\Livewire\Auth;

use App\Services\LogActivityService;
use App\Traits\DispatchMessage;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;

class Login extends Component
{
   protected $locActivityService;


   public function boot(){
    $this->locActivityService = new LogActivityService;
   }
    public $username = '';
    public $password = '';
    public $remember = false;


    #[Layout('components.layouts.auth')]
    // Aturan validasi
    protected $rules = [
        'username' => 'required',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate($this->rules);

        // Coba autentikasi pengguna
        if (! Auth::attempt(['username' => $this->username, 'password' => $this->password], $this->remember)) {
            // Log failed login attempt

            // Jika autentikasi gagal, lempar exception validasi
            throw ValidationException::withMessages([
                'username' => __('auth.failed'), // Menggunakan pesan error default Laravel
            ]);
        }

        // Regenerasi session ID untuk keamanan
        session()->flash('success', 'Welcome back! You have successfully logged in.');
        session()->regenerate();

        // Redirect pengguna berdasarkan role
        $user = Auth::user();

        // Log successful login activity
        $this->locActivityService->logActivity(
            $user,
            'login_success',
            [
                'username' => $user->username,
                'role' => $user->role,
                'login_time' => now()->toDateTimeString(),
            ],
            'username'
        );

        // Arahkan pengguna berdasarkan role
        if ($user->role === 1) {
            return redirect()->route('admin.beranda'); // Admin
        } else {
            return redirect()->route('komando'); // Komando (default)
        }
    }

    public function logout()
    {
        // Initialize LogActivityService if not already initialized
        if (!$this->locActivityService) {
            $this->locActivityService = new LogActivityService();
        }

        $user = Auth::user();
        if ($user) {
            $this->locActivityService->logActivity(
                $user,
                'logout',
                [
                    'username' => $user->username,
                ],
                'username'
            );
        }

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        // Emit event untuk redirect dari JS
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.auth.login');

    }


}
