<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        // SET ROLE PELANGGAN OTOMATIS
        $validated['role'] = 'pelanggan';

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        // REDIRECT KE MENU (KATALOG), BUKAN DASHBOARD ADMIN
        $this->redirect(route('front.index', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Buat Akun Baru 🚀</h2>
        <p class="text-sm text-gray-400 mt-1">Gabung dan nikmati masakan keluarga.</p>
    </div>

    <form wire:submit="register" class="space-y-5">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Nama Lengkap</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-gray-400">👤</span>
                </div>
                <input wire:model="name" id="name" type="text" name="name" required autofocus autocomplete="name"
                    class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-gray-100 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl text-sm font-semibold transition" 
                    placeholder="Nama Anda">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs text-red-500 font-bold ml-1" />
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-gray-400">📧</span>
                </div>
                <input wire:model="email" id="email" type="email" name="email" required autocomplete="username"
                    class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-gray-100 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl text-sm font-semibold transition" 
                    placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-500 font-bold ml-1" />
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-gray-400">🔒</span>
                </div>
                <input wire:model="password" id="password" type="password" name="password" required autocomplete="new-password"
                    class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-gray-100 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl text-sm font-semibold transition" 
                    placeholder="Minimal 8 karakter">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-500 font-bold ml-1" />
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Konfirmasi Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-gray-400">🔐</span>
                </div>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-gray-100 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl text-sm font-semibold transition" 
                    placeholder="Ulangi password">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs text-red-500 font-bold ml-1" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg shadow-indigo-200 text-sm font-bold text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition transform active:scale-[0.98]">
                {{ __('Daftar Sekarang') }}
            </button>
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-500">
                Sudah punya akun? 
                <a href="{{ route('login') }}" wire:navigate class="font-bold text-indigo-600 hover:underline">
                    Masuk di sini
                </a>
            </p>
        </div>
    </form>
</div>