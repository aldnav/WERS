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


}
