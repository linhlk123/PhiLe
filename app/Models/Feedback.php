<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'FEEDBACK';
    protected $primaryKey = 'FeedbackID';

    public $timestamps = true;

    protected $fillable = [
        'FullName',
        'Email',
        'Phone',
        'Message',
    ];
}
