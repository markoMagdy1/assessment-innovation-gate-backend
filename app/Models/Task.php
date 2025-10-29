<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['creator_id', 'assignee_id', 'title', 'description', 'due_date', 'priority', 'is_completed'];

     protected $hidden = ['created_at', 'updated_at'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function getStatusAttribute()
    {
        if ($this->is_completed) {
            return 'Done';
        }

        $today = Carbon::today();
        $due = Carbon::parse($this->due_date);

        if ($due->isToday()) {
            return 'Due Today';
        } elseif ($due->isPast()) {
            return 'Missed/Late';
        } else {
            return 'Upcoming';
        }
    }
}
