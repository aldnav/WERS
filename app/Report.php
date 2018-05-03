<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
      //
    /**
	* Field to be mass-assigned.
	*
	* @var array
	*/
	protected $fillable = ['owner_id', 'incident_id', 'lat', 'lng','body','status','address','contact_number'];
}
