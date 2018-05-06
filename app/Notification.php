<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    protected $fillable = [
        'owner_id', 'object_id', 'object_type', 'action',
        'verbose_string', 'is_read'];
    protected $dates = ['created_at'];
    public $timestamps = true;

    // Create notification instance using this static notify
    public static function notify(
        $userId,
        $action,
        $object_id,
        $object_type) {
        
        $notif = new Notification;
        $notif->owner_id = $userId;
        $notif->action = $action;
        $notif->object_id = $object_id;
        $notif->object_type = $object_type;
        $notif->verbose_string = '<User:'.$user->id.'> '.$action.' <'.$object_type.':'.$object_id.'>';
        $notif->save();
    }
}
