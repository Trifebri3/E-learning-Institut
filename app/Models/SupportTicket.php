<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    // HAPUS $guarded jika menggunakan $fillable, atau sebaliknya
    // protected $guarded = [];

    protected $fillable = [
        'user_id',
        'program_id',
        'category',
        'subject',
        'description', // TAMBAHKAN INI
        'priority',
        'attachment_path',
        'status',
        'admin_reply'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    // Helper untuk warna status
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'open' => 'blue',
            'in_progress' => 'yellow',
            'resolved' => 'green',
            'closed' => 'gray',
            default => 'gray',
        };
    }

    // Helper untuk Label Kategori (Bahasa Indonesia)
    public function getCategoryLabelAttribute()
    {
        return match($this->category) {
            'general' => 'Laporan Umum',
            'academic' => 'Kendala Kelas/Program',
            'permission' => 'Pengajuan Izin',
            'system' => 'Gangguan Sistem (IT)',
            default => 'Lainnya',
        };
    }
    
}
