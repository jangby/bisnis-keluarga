<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        // --- PERBAIKAN: REDIRECT TEGAS (PAKSA) ---
        // Kita tidak pakai redirectIntended agar user tidak 'nyasar' 
        // ke halaman sebelumnya yang tidak sesuai hak aksesnya.

        $user = Auth::user();

        if ($user->role === 'pelanggan') {
            // Pelanggan WAJIB ke Halaman Depan
            $this->redirect(route('front.index', absolute: false), navigate: true);
        } else {
            // Owner/Staf WAJIB ke Dashboard
            $this->redirect(route('dashboard', absolute: false), navigate: true);
        }
    }
}; ?>

<div>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Selamat Datang! 👋</h2>
        <p class="text-sm text-gray-400 mt-1">Masuk untuk mulai memesan makanan enak.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-gray-400">📧</span>
                </div>
                <input wire:model="form.email" id="email" type="email" name="email" required autofocus autocomplete="username" 
                    class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-gray-100 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl text-sm font-semibold transition" 
                    placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-xs text-red-500 font-bold ml-1" />
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-gray-400">🔒</span>
                </div>
                <input wire:model="form.password" id="password" type="password" name="password" required autocomplete="current-password"
                    class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-gray-100 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl text-sm font-semibold transition" 
                    placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-xs text-red-500 font-bold ml-1" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded-lg bg-gray-100 border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5">
                <span class="ms-2 text-sm text-gray-500 font-medium">{{ __('Ingat Saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-600 font-bold hover:text-indigo-800 transition" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Lupa?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg shadow-indigo-200 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition transform active:scale-[0.98]">
                {{ __('Masuk Sekarang') }}
            </button>
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-500">
                Belum punya akun? 
                <a href="{{ route('register') }}" wire:navigate class="font-bold text-indigo-600 hover:underline">
                    Daftar di sini
                </a>
            </p>
        </div>
    </form>
</div>