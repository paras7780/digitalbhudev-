<?php
namespace App\Support;
use App\Models\AuditLog;
use Illuminate\Http\Request;
class Audit { public static function record(Request $request,string $action,?object $subject=null,array $metadata=[]): void { AuditLog::create(['user_id'=>$request->user()?->id,'action'=>$action,'subject_type'=>$subject ? get_class($subject) : null,'subject_id'=>$subject?->getKey(),'ip_hash'=>$request->ip() ? hash('sha256',$request->ip().config('app.key')) : null,'user_agent'=>substr((string)$request->userAgent(),0,500),'metadata'=>$metadata,'created_at'=>now()]); } }
