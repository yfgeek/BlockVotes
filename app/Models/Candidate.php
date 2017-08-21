<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Candidate Model
 * @author  Yifan Wu
 * @package Model
 */
class Candidate extends Model
{
    protected $table = 'candidate';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'des',
        'vote_id'
    ];

    public function addCandidate($name,$description,$vote_id){
        $this->create([
            'name' => $name,
            'des' => $description,
            'vote_id' => $vote_id
        ]);
    }

    public function updateCandidate($name,$description){
        $this->update([
            'name' => $name,
            'des' => $description
        ]);
    }

    public function delCandidate($id){
        $this->where('id',$id)->delete();
    }
}