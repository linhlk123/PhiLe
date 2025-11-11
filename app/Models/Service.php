<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'SERVICES';
    protected $primaryKey = 'ServiceID';
    public $timestamps = false;

    protected $fillable = [
        'ServiceName',
        'Description',
        'Price'
    ];

    public function serviceUsages()
    {
        return $this->hasMany(ServiceUsage::class, 'ServiceID');
    }
}
