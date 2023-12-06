<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Jiannei\Response\Laravel\Support\Facades\Response;
use Throwable;

class AuthorizationService extends MainService
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login(Request $request): JsonResponse|JsonResource
    {
        //判断用户存不存在
        if (! $user = $this->user->query()->where('username', $request['username'])
            ->orWhere('nickname', $request['username'])->first()) {
            Response::fail('用户不存在');
        }
        //验证密码是否正确
        if (! Hash::check($request['password'], $user->password)) {

            Response::fail('用户账号或密码错误', 403);
        }
        //验证是否通过审核
        if ($user->status != 1) {
            Response::fail('该账号被限制登陆，如有疑问，请联系管理员', 403);
        }
        $user->update(['login_times' => now()]);
        //生成token
        $tokenResult = $user->createToken('user');

        return Response::success([
            'access_token' => $tokenResult->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }

    public function changePassword($request): JsonResponse|JsonResource
    {
        if (! Hash::check($request->password, $request->user()->password)) {
            Response::fail('用户原密码不正确');
        }
        try {
            request()->user()->update(['password' => Hash::make($request->new_password)]);
        } catch (Throwable $e) {
            Response::fail($e->getMessage() ?? '密码修改失败，请重试');
        }

        return Response::success('密码修改成功');
    }

    public function userInfo($request): JsonResponse|JsonResource
    {
        $user_id = $request->user()->id;
        $user = $this->user->query()->find($user_id)->append('status_str', 'role_str');

        return Response::success($user);
    }

    public function logout($request): JsonResponse|JsonResource
    {
        $request->user()->currentAccessToken()->delete();

        return Response::success('退出成功...');
    }
}
