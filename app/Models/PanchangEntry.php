<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PanchangEntry extends Model { protected $fillable=['date','location','timezone','vaar','tithi','nakshatra','yoga','karana','sunrise','sunset','moonrise','moonset','notes','status','published_at','created_by','updated_by']; protected function casts(): array { return ['date'=>'date','published_at'=>'datetime']; } }
