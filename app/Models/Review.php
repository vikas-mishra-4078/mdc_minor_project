<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\User;

class Review extends Authenticatable
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reviewer_id', 'receiver_id', 'rating', 'review', 'status'
    ];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

	public function reviewer() {
		return $this->belongsTo(User::class, 'reviewer_id');
    }

	public function receiver() {
		return $this->belongsTo(User::class, 'receiver_id');
    }

}