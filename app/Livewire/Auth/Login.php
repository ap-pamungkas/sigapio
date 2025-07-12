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
   protected $logActivityService;


   public function boot(){
    $this->logActivityService = new LogActivityService;
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

    protected $messages = [
        'username.required' => 'Username harus diisi.',
        'password.required' => 'Password harus diisi.',
    ];

    public function login()
    {
        $this->validate($this->rules, $this->messages);

        // Coba autentikasi pengguna
        if (! Auth::attempt(['username' => $this->username, 'password' => $this->password], $this->remember)) {
            // Log failed login attempt

            // Jika autentikasi gagal, lempar exception validasi
           session()->flash('error', 'login gagal! Periksa username dan password Anda.');
        }else{
             session()->flash('success', ' login berhasil, Selamat datang kembali!.');
        session()->regenerate();

        // Redirect pengguna berdasarkan role
        $user = Auth::user();

        // Log successful login activity
        $this->logActivityService->logActivity(
            $user,
            'login_success',
            [
                'username' => $user->username,
                'role' => $user->role,
                'login_time' => now()->toDateTimeString(),
            ],
            'error'
        );

        // Arahkan pengguna berdasarkan role
        if ($user->role === 1) {
            return redirect()->route('admin.beranda'); // Admin
        } else {
            return redirect()->route('komando'); // Komando (default)
        }
        }



    }

    public function logout()
    {
        // Initialize LogActivityService if not already initialized
        if (!$this->logActivityService) {
            $this->logActivityService = new LogActivityService();
        }

        $user = Auth::user();
        if ($user) {
            $this->logActivityService->logActivity(
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
