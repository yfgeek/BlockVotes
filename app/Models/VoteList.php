<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * VoteList Model
 * @author  Yifan Wu
 * @package Model
 */
class VoteList extends Model
{

	protected $table = 'vote';


    protected $fillable = [
        'id',
        'title',
        'number',
        'description',
        'is_started'

    ];


    public function setProfile($id,$title,$number,$description)
    {
        $this->where('id',$id)->update([
            'title' => $title,
            'number' => $number,
            'description' => $description
        ]);
    }

}