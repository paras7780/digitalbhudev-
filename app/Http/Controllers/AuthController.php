<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\BhudevProfile;
use App\Models\BhudevStatusHistory;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Auth\Events\Registered;
class AuthController extends Controller
{
 public function showLogin(){return view('pages.login');}
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'The supplied credentials do not match our records.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        $user = $request->user();

        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors(['email' => 'This account has been disabled.']);
        }

        Audit::record($request, 'auth.login', $user);

        // Smart role-based redirection avoiding 403 intended URL traps:
        if ($user->isAdmin()) {
            $target = session()->pull('url.intended', route('admin.dashboard'));
            if (str_contains($target, '/bhudev')) {
                $target = route('admin.dashboard');
            }
            return redirect()->to($target);
        } else {
            // User is a Bhudev
            $target = session()->pull('url.intended', route('bhudev.dashboard'));
            // If the user visited /admin prior to logging in,
            // send them to their Bhudev portal dashboard instead of hitting a 403 on /admin!
            if (str_contains($target, '/admin')) {
                $target = route('bhudev.dashboard');
            }
            return redirect()->to($target);
        }
    }
 public function register(Request $request){$data=$request->validate(['name'=>['required','string','max:120'],'phone'=>['required','string','max:25','unique:users,phone'],'email'=>['required','email','max:255','unique:users,email'],'password'=>['required','confirmed',Password::min(12)->mixedCase()->numbers()]]);$user=User::create(['name'=>$data['name'],'phone'=>$data['phone'],'email'=>$data['email'],'password'=>Hash::make($data['password']),'role'=>'bhudev','is_active'=>true]);$profile=BhudevProfile::create(['user_id'=>$user->id,'display_name'=>$data['name'],'slug'=>Str::slug($data['name']).'-'.Str::lower(Str::random(6)),'status'=>'pending','is_visible'=>false]);BhudevStatusHistory::create(['bhudev_profile_id'=>$profile->id,'changed_by'=>$user->id,'from_status'=>null,'to_status'=>'pending','reason'=>'Initial registration','is_visible'=>false]);event(new Registered($user));Auth::login($user);$request->session()->regenerate();return redirect()->route('verification.notice')->with('status','Account created. Verify your email, then complete your profile.'); }
 public function logout(Request $request){Audit::record($request,'auth.logout',$request->user());Auth::logout();$request->session()->invalidate();$request->session()->regenerateToken();return redirect()->route('home');}
 public function forgotForm(){return view('auth.forgot-password');}
 public function forgot(Request $request){$request->validate(['email'=>['required','email']]);$status=PasswordBroker::sendResetLink($request->only('email'));return $status===PasswordBroker::RESET_LINK_SENT?back()->with('status',__($status)):back()->withErrors(['email'=>__($status)]);}
 public function resetForm(Request $request,string $token){return view('auth.reset-password',['request'=>$request,'token'=>$token]);}
 public function reset(Request $request){$data=$request->validate(['token'=>'required','email'=>'required|email','password'=>['required','confirmed',Password::min(12)->mixedCase()->numbers()]]);$status=PasswordBroker::reset($data,function(User $user,string $password){$user->forceFill(['password'=>Hash::make($password)])->setRememberToken(Str::random(60));$user->save();});return $status===PasswordBroker::PASSWORD_RESET?redirect()->route('login')->with('status',__($status)):back()->withErrors(['email'=>__($status)]);}
}
