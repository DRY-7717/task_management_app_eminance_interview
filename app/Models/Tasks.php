<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tasks extends Model
{
    use HasFactory;
    protected $table = 'tasks';
    protected $guarded = ['id'];



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'developer_id', 'id');
    }
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }
    public function severity(): BelongsTo
    {
        return $this->belongsTo(Severity::class, 'severity_id', 'id');
    }
}
