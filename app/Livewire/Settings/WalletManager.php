<?php

namespace App\Livewire\Settings;

use App\Models\Wallet;
use Livewire\Component;

class WalletManager extends Component
{
    public $wallet_id;
    public $name;
    public $account_number;
    public $balance; // Opsional: biasanya saldo diubah lewat transaksi, tapi kita tampilkan di sini
    public $isEditing = false;

    public function render()
    {
        return view('livewire.settings.wallet-manager', [
            'wallets' => Wallet::all()
        ]);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'nullable|string|max:50',
        ]);

        // Simpan atau Update
        Wallet::updateOrCreate(
            ['id' => $this->wallet_id],
            [
                'name' => $this->name,
                'account_number' => $this->account_number,
                // Kita tidak update saldo di sini agar tidak merusak pembukuan
                // Saldo hanya diset saat create awal (di seeder) atau lewat transaksi
            ]
        );

        session()->flash('message', 'Data Dompet berhasil disimpan.');
        $this->cancel(); // Reset form
    }

    public function edit($id)
    {
        $wallet = Wallet::find($id);
        $this->wallet_id = $wallet->id;
        $this->name = $wallet->name;
        $this->account_number = $wallet->account_number;
        $this->balance = $wallet->balance;
        $this->isEditing = true;
    }

    public function delete($id)
    {
        // Cek apakah dompet sudah dipakai transaksi
        $wallet = Wallet::find($id);
        if ($wallet->finance_records()->exists()) {
            session()->flash('error', 'Gagal hapus! Dompet ini sudah ada riwayat transaksinya.');
            return;
        }
        
        $wallet->delete();
        session()->flash('message', 'Dompet berhasil dihapus.');
    }

    public function cancel()
    {
        $this->reset(['name', 'account_number', 'balance', 'wallet_id', 'isEditing']);
    }
}