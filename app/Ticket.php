<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject', 'body', 'status', 'created_by', 'parent'
    ];

    /**
     * Get the user that send the ticket record.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    /**
     * Get the child ticket of current one.
     */
    public function child()
    {
        return $this->hasMany('App\Ticket', 'parent_id', 'id');
    }

}
