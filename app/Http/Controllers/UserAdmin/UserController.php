<?php

namespace App\Http\Controllers\UserAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\User\UserAmountLog;
use App\Models\User\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\GD\Driver;
use Intervention\Image\ImageManager;

class UserController extends Controller
{

    public function updateUserInfo(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['code' => 401, 'msg' => '请先登录'], 401);
            }

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'numeric', 'regex:/^1[3-9]\d{9}$/'],
                'email' => ['required', 'string', 'email', 'max:255', 'regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/'],
                'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults(), 'regex:/^(?=.*[a-zA-Z])(?=.*[\d\W])|(?=.*[\d])(?=.*[\W])|(?=.*[a-zA-Z])(?=.*[\W]).{8,16}$/'],
            ], [
                'email.regex' => '邮箱格式不正确',
                'email.required' => '请输入邮箱',
                'email.unique' => '邮箱已存在',
                'phone.unique' => '手机号已存在',
                'phone.regex' => '手机号格式不正确',
                'name.required' => '请输入用户名',
                // 'phone.required' => '请输入手机号',
                'password.min' => '密码至少8位',
                'password.regex' => '8 到 16 个字符，且至少包含一个数字和一个字母',
                'password.confirmed' => '两次密码需完全一致',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 200);
            }

            DB::beginTransaction();

            $user->name = $request->input('name');
            $user->phone = $request->input('phone');
            $user->email = $request->input('email');
            $user->avatar = $request->input('avatar', $user->avatar);

            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }

            $user->save();

            DB::commit();
            return response()->json(['code' => 200, 'message' => '用户信息修改成功']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }



    public function updateUserProfile(Request $request)
    {

        try {
            $user = Auth::user();
            DB::beginTransaction();
            if (Auth::check()) {
                $data = $request->get("data", []);
                $userInfo = new UserInfo();
                $userInfo->user_id = Auth::id() ;
                $userInfo->company = $data['company'];
                $userInfo->department = $data['department']??'';
                $userInfo->first_name = $data['first_name']??'';
                $userInfo->last_name = $data['last_name']??'';
                $userInfo->address = $data['address']??'';
                $userInfo->city = $data['city']??'';
                $userInfo->code = $data['code']??'';
                $userInfo->info = $data['about']??'';
                $userInfo->country = $data['country']??'';

                $res = UserInfo::where('user_id',Auth::id() )->first();
                if (!$res){
                    $userInfo->save();
                }else{
                    $res->user_id = Auth::id() ;
                    $res->company = $data['company'];
                    $res->department = $data['department']??'';
                    $res->first_name = $data['first_name']??'';
                    $res->last_name = $data['last_name']??'';
                    $res->address = $data['address']??'';
                    $res->city = $data['city']??'';
                    $res->code = $data['code']??'';
                    $res->info = $data['about']??'';
                    $res->country = $data['country']??'';
                    $res->save();
                }
                DB::commit();
                return ['code' => 200,'msg'=>'修改成功'];
            }else{
                return ['code' => 201,'msg'=>'请先登录'];
            }
        } catch (Exception $e) {
            DB::rollBack();
            return ['code' => 201, 'msg'=>'保存异常，请刷新重试'];
        }
    }


    public function updateUserAvatar(Request $request){
//        try {
        $image = new ImageManager(new Driver());
        $img   = $image->read($request->file('file'));

        $path = '/upload/avatar/' . date('Y-m-d');
        $fullPath = public_path($path);

        // 检查并创建目录
        if (!File::exists($fullPath)) {
            File::makeDirectory($fullPath, 0777, true);
        }

        // 处理图像尺寸
        $img->cover(128, 128); // 新版本使用cover()替代fit()

        $f_name = date('YmdHis') . mt_rand(100, 999);
        $f_path = $path . '/' . $f_name . '.png';

        // 按比例调整尺寸
        if ($img->width() >= $img->height()) {
            $height = round((302 / $img->width()) * $img->height(), 3);
            $img->resize(302, $height);
        } else {
            $width = round((302 / $img->height()) * $img->width(), 3);
            $img->resize($width, 302);
        }
        $res = $img->save(public_path($f_path));
        if ($res) {
            return [ 'success' => true,  'code' => 200, 'msg' => '修改成功', 'url' => $f_path,'f_name' => $f_name ];
        } else {
            return [ 'success' => false, 'msg' => '修改失败', 'img' => $img ];
        }
//        } catch (\Exception $e) {
//            return [ 'success' => false, 'msg' => $e->getMessage() ];
//        }
    }


    public function user_info(Request $request)
    {
        $user = Auth::user();
        return view('user_admin.user_info.index', ['user' => $user,'data' => $user->info]);
    }
    public function userRecordAmounts(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 1);
        $recordAmounts = [];
        if ($type == 1){
            $recordAmounts = UserAmountLog::where('user_id', $user->id)->orderBy('id', 'desc')->simplePaginate(20);
        }
        if ($type == 2){
            $recordAmounts = UserAmountLog::where('user_id', $user->id)->where('type', 2)->orderBy('id', 'desc')->simplePaginate(20);
        }
        if ($type == 3){
            $recordAmounts = UserAmountLog::where('user_id', $user->id)->whereIn('type', [1,3])->orderBy('id', 'desc')->simplePaginate(20);
        }
        return view('user_admin.user_info.record-amounts', [
            'user' => $user,
            'data' => $recordAmounts,
        ]);
    }

}
