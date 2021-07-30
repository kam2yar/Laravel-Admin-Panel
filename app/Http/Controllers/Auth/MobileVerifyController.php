<?php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Services\SMS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MobileVerifyController extends Controller
{
    public $cache_name;

    public function __construct()
    {
        $this->cache_name = 'mobile_verification_' . auth()->user()->mobile;
    }

    public function form()
    {
        if (auth()->user()->mobile_verified_at)
            return redirect()->route('admin.index');

        if (!Cache::has($this->cache_name)) {
            $verification_code = rand(1111, 9999);

            Cache::remember($this->cache_name, 2, function () use ($verification_code) {
                return $verification_code;
            });

            SMS::notifyNow(auth()->user(), 'کد تایید موبایل شما ' . $verification_code . ' می باشد');
        }


        return view('pages.auth.mobile-verify');
    }

    public function verify(Request $request)
    {
        if (auth()->user()->mobile_verified_at)
            return redirect()->route('admin.index');

        $this->validate($request, [
            'code' => 'required|numeric'
        ]);

        if (!Cache::exists($this->cache_name))
            return back()->with('warning', 'کد تایید منقضی شده است، پیامک تایید مجددا برای شما ارسال شد');

        if (Cache::get($this->cache_name) != $request->code)
            return back()->withErrors(['کد وارد شده صحیح نیست لطفا دوباره امتحان کنید']);

        auth()->user()->update([
            'mobile_verified_at' => Carbon::now()->toDateTimeString()
        ]);

        return redirect()->route('admin.index');
    }
}
