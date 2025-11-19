<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public function render()
    {
        return view('livewire.settings.index')->layout('layouts.app');
    }
}