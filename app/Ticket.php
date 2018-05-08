<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    //
        //
      //
    /**
	* Field to be mass-assigned.
	*
	* @var array
	*/
	protected $fillable = ['report_id','responder_id','status'];

	public function responder()
    {
        return $this->belongsTo('App\User', 'responder_id');
    }
}
