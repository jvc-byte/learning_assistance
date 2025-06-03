<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'subject',
        'grade_level',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that created the lesson.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')
            ->select('id', 'name');
    }

    /**
     * Get the user that last updated the lesson.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')
            ->select('id', 'name');
    }

    /**
     * Scope a query to only include active lessons.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include draft lessons.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
