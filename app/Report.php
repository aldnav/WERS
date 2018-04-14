<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    /*protected $fillable=['id','owner_id','incident_id','lat','lng','body','status'];*/
    //protected $table = 'reports';
//    protected $primaryKey = 'id';
    protected $owner_id = 'owner_id';
    protected $incident_id = 'incident_id';
    protected $lat = 'lat';
    protected $lng = 'lng';
    protected $body = 'body';
    protected $status = 'status';
}
