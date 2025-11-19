<?php

namespace App\Livewire\Settings;

use App\Models\Contact;
use Livewire\Component;

class ContactManager extends Component
{
    public $type; // 'supplier' atau 'customer'
    public $title;
    
    public $name, $phone, $address, $contact_id;
    public $isEditing = false;

    public function render()
    {
        return view('livewire.settings.contact-manager', [
            'contacts' => Contact::where('type', $this->type)->latest()->get()
        ]);
    }

    public function save()
    {
        $this->validate(['name' => 'required', 'phone' => 'required']);

        Contact::updateOrCreate(
            ['id' => $this->contact_id],
            [
                'name' => $this->name,
                'phone' => $this->phone,
                'address' => $this->address,
                'type' => $this->type
            ]
        );

        $this->reset(['name', 'phone', 'address', 'contact_id', 'isEditing']);
        session()->flash('message', 'Kontak disimpan.');
    }

    public function edit($id)
    {
        $c = Contact::find($id);
        $this->contact_id = $c->id;
        $this->name = $c->name;
        $this->phone = $c->phone;
        $this->address = $c->address;
        $this->isEditing = true;
    }
    
    public function delete($id) { Contact::find($id)->delete(); }
    public function cancel() { $this->reset(['name', 'phone', 'address', 'contact_id', 'isEditing']); }
}