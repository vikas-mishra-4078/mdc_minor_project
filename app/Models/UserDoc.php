<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\User;

class UserDoc extends Authenticatable
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'doc_file', 'doc_type'
    ];
    
    public static function getDocTypes() {
        return [
            'Adhar Card' => 'Adhar Card', 
            'Voter ID' => 'Voter ID', 
            'Pan Card' => 'Pan Card', 
            'Driving Licence' => 'Driving Licence', 
            '10th Marksheet' => '10th Marksheet', 
            '12th Marksheet' => '12th Marksheet', 
            'Bachelor Degree' => 'Bachelor Degree', 
            'Master Degree' => 'Master Degree', 
            'Cancel Check' => 'Cancel Check', 
            'Other' => 'Other', 
        ];
    }

	public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

}