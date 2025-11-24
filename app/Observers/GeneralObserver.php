<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class GeneralObserver
{
    public function created(Model $model)
    {
        $this->log($model, 'Membuat', 'bg-green-100 text-green-700', '✨');
    }

    public function updated(Model $model)
    {
        // [PERBAIKAN] Abaikan jika yang berubah HANYA kolom 'current_stock'
        // Kita tidak perlu log aktivitas setiap kali stok berkurang karena jualan
        $changes = $model->getDirty();
        if (count($changes) === 1 && array_key_exists('current_stock', $changes)) {
            return; 
        }

        // [PERBAIKAN] Abaikan jika yang berubah hanya timestamp (updated_at)
        if ($model->wasChanged()) {
            $this->log($model, 'Mengubah', 'bg-blue-100 text-blue-700', '✏️');
        }
    }

    public function deleted(Model $model)
    {
        $this->log($model, 'Menghapus', 'bg-red-100 text-red-700', '🗑️');
    }

    protected function log($model, $action, $colorClass, $icon)
    {
        if (!Auth::check()) return;

        $className = class_basename($model);
        
        // Nama item yang user friendly
        $itemName = $model->name ?? $model->code ?? $model->category ?? '#' . $model->id;

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => $className,
            'subject_id' => $model->id,
            'description' => "$action data $className: $itemName",
            'properties' => [
                'attributes' => $model->getAttributes(),
                'color' => $colorClass,
                'icon' => $icon
            ],
            'ip_address' => request()->ip()
        ]);
    }
}