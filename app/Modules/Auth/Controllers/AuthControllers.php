<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LoginHistory;
use App\Models\BlockedUser;
use App\Models\Variable;
use Illuminate\Support\Facades\Hash;

class AuthControllers extends Controller {

    use \TraitsFunc;

    public function __construct(){
        $this->middleware('withAuth',['except' => ['login','doLogin']]);
    }

    public function login() {
        if(\Session::has('user_id')){
            return redirect('/backend/dashboard');
        }
        return view('Auth.Views.login');
    }

	public function doLogin() {

        $input = \Request::all();
        $attempts = session()->get('login.attempts', 0);
        $rules = array(
            'username' => 'required',
            'password' => 'required',
        );
        session()->get('defUsername',$input['username']);

        $message = array(
            'username.required' => "يرجي ادخال اسم المستخدم",
            'password.required' => "يرجي ادخال كلمة المرور",
        );

        $validate = \Validator::make($input, $rules,$message);

        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            $this->checkFailAttempts($attempts,$input['username']);
            return redirect('/backend/login');
        }

        $username = $input['username'];
        $userObj = User::getLoginUser($username);
        
        if ($userObj == null) {
            \Session::flash('error', "هذا البريد الالكتروني غير موجود او غير مفعل");
            $this->checkFailAttempts($attempts,$input['username']);
            return redirect('/backend/login');
        }

        $checkPassword = Hash::check($input['password'], $userObj->password);

        if ($checkPassword == null) {
            \Session::flash('error', "كلمة المرور غير صحيحة");
            $this->checkFailAttempts($attempts,$input['username']);
            return redirect('/backend/login');  
        }

        $blockedUser = BlockedUser::checkForUsername($username,\Request::ip());
        if($blockedUser){
            \Session::flash('error', "تم حظر هذا المستخدم. يرجي الانتظار حتي: ".$blockedUser->ended_at);
            return redirect('/backend/login');  
        }

        $isAdmin = in_array($userObj->group_id, [1,]) ? true : false;

        $session_time = (int) $userObj->session_time ? $userObj->session_time : Variable::getVar('وقت قفل الشاشه:');
        $endedTime = date('Y-m-d H:i:s', strtotime('+'.$session_time. ' minutes', strtotime(DATE_TIME)));

        $dataObj = new \stdClass();
        $dataObj->email = $userObj->email;
        $dataObj->group_id = (int) $userObj->group_id;

        session(['group_id' => $dataObj->group_id]);
        session(['user_id' => $userObj->id]);
        session(['email' => $dataObj->email]);
        session(['username' => $userObj->username]);
        session(['is_admin' => $isAdmin]);
        session(['group_name' => $userObj->Group->title]);

        $now = now()->format('Y-m-d H:i:s');

        $check = LoginHistory::checkForUsername($userObj->username);
        if($check){
            if($check->to_date <= $now){
                $check->ended = 1;
                $check->save();
                LoginHistory::insert([
                    'username' => $userObj->username,
                    'from_date' => $now,
                    'to_date' => $endedTime,
                    'sort'  => LoginHistory::newSortIndex(),
                    'ended' => 0,
                    'created_at' => $now,
                    'created_by' => $userObj->id,
                ]);
            }
        }else{
            LoginHistory::insert([
                'username' => $userObj->username,
                'from_date' => $now,
                'to_date' => $endedTime,
                'sort'  => LoginHistory::newSortIndex(),
                'ended' => 0,
                'created_at' => $now,
                'created_by' => $userObj->id,
            ]);
        }

        \Session::flash('success', "اهلا بك في تكافل " . $userObj->username);
        return redirect('/backend/dashboard');
	}

    public function checkFailAttempts($attempts,$username){
        $newNumber = $attempts + 1; 
        session()->put('login.attempts', $newNumber);
        if($newNumber == 3){
            $now = now()->format('Y-m-d H:i:s');
            $banPeriod = Variable::getVar('مدة الحظر:');
            $endedTime = date('Y-m-d H:i:s', strtotime('+'.$banPeriod. ' minutes', strtotime($now)));
            BlockedUser::insert([
                'username' => $username,
                'ip_address' => \Request::ip(),
                'ended_at' => $endedTime,
                'sort' => BlockedUser::newSortIndex(),
                'created_at' => $now,
            ]);
        }
    }

	public function logout() {
        \Auth::logout();
        LoginHistory::where('username',\Session::get('username'))->where('ended',0)->update([
            'ended' => 1,
            'to_date' => now()->format('Y-m-d H:i:s'),
        ]);
        session()->flush();
        \Session::flash('success', "نراك قريبا ;)");
        return redirect('/');
	}

}
