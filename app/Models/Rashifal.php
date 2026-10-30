<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Rashifal extends Model { protected $fillable=['sign','date','title','content','status','published_at','created_by','updated_by']; protected function casts(): array { return ['date'=>'date','published_at'=>'datetime']; } }
