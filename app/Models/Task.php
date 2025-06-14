<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model {
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'assigned_to',
        'status',
        'due_date',
        'created_by'
    ];

    public function assignee() {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
