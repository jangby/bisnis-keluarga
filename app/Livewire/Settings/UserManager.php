<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserManager extends Component
{
    public $users;
    public $name, $email, $password, $role = 'finance'; // Default role
    public $isEditing = false;
    public $editId;

    public function render()
    {
        // Ambil semua user, urutkan yang terbaru
        $this->users = User::orderBy('created_at', 'desc')->get();
        return view('livewire.settings.user-manager');
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'role' => 'required|in:owner,finance,marketing,production',
        ];

        if (!$this->isEditing) {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|min:6';
        } else {
            $rules['email'] = 'required|email|unique:users,email,' . $this->editId;
            $rules['password'] = 'nullable|min:6'; // Password opsional saat edit
        }

        $this->validate($rules);

        if ($this->isEditing) {
            $user = User::find($this->editId);
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
            ];
            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }
            $user->update($data);
            session()->flash('message', 'Data user berhasil diperbarui.');
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
            ]);
            session()->flash('message', 'User baru berhasil ditambahkan.');
        }

        $this->resetInput();
    }

    public function edit($id)
    {
        $user = User::find($id);
        $this->editId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->isEditing = true;
    }

    public function delete($id)
    {
        if ($id == Auth::id()) {
            session()->flash('error', 'Tidak bisa menghapus akun sendiri!');
            return;
        }
        User::find($id)->delete();
        session()->flash('message', 'User berhasil dihapus.');
    }

    public function cancel()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'finance';
        $this->isEditing = false;
        $this->editId = null;
    }
}