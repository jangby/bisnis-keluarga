<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserManager extends Component
{
    public $users;
    // Tambahkan properti $phone
    public $name, $email, $password, $phone, $role = 'staff'; 
    public $daily_salary = 0;
    public $isEditing = false;
    public $editId;

    public function render()
    {
        $this->users = User::orderBy('created_at', 'desc')->get();
        return view('livewire.settings.user-manager');
    }

    public function save()
    {
        // [UPDATE] Tambahkan 'inventory' ke dalam daftar validasi role
        $rules = [
            'name' => 'required|string|max:255',
            'role' => 'required|in:owner,finance,marketing,production,inventory,staff',
        ];

        if (!$this->isEditing) {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|min:6';
        } else {
            $rules['email'] = 'required|email|unique:users,email,' . $this->editId;
            $rules['password'] = 'nullable|min:6';
        }

        $this->validate($rules);

        if ($this->isEditing) {
            $user = User::find($this->editId);
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
                'daily_salary' => $this->daily_salary,
            ];
            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }
            $user->update($data);
            
            $this->dispatch('notify', message: 'Data user berhasil diperbarui.', type: 'success');
        
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'daily_salary' => $this->daily_salary,
            ]);

            $this->dispatch('notify', message: 'User baru berhasil ditambahkan.', type: 'success');
        }

        $this->resetInput();
    }

    public function edit($id)
    {
        $user = User::find($id);
        $this->editId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone; // Load Phone
        $this->role = $user->role;
        $this->daily_salary = $user->daily_salary;
        $this->isEditing = true;
    }

    public function delete($id)
    {
        if ($id == Auth::id()) {
            $this->dispatch('notify', message: 'Tidak bisa menghapus akun sendiri!', type: 'error');
            return;
        }

        User::find($id)->delete();
        $this->dispatch('notify', message: 'User dihapus.', type: 'success');
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
        $this->phone = ''; // Reset Phone
        $this->role = 'staff';
        $this->daily_salary = 0;
        $this->isEditing = false;
        $this->editId = null;
    }
}