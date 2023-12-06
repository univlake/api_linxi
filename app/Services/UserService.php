<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Jiannei\Response\Laravel\Support\Facades\Response;
use Overtrue\Pinyin\Pinyin;

class UserService extends MainService
{
    protected User $user;

    protected Pinyin $pinyin;

    public function __construct(User $user, Pinyin $pinyin)
    {
        $this->user = $user;
        $this->pinyin = $pinyin;
    }

    public function index($request): JsonResponse|JsonResource
    {
        if ($request->user()->role_id == 2) {
            Response::fail('无权限');
        }

        $data = $this->user->query()
            ->orderByDesc('created_at')
            ->select('nickname',
                'username',
                'tel',
                'id',
                'role_id',
                'email',
                'status', 'login_times')
            ->when($request->nickname, function ($query) use ($request) {
                return $query->where('nickname', 'like', '%'.$request->nickname.'%');
            })->when($request->tel, function ($query) use ($request) {
                return $query->where('tel', 'like', '%'.$request->tel.'%');
            })->paginate($request->per_page ?: 15);

        $data->map(function ($model) {
            $model->append('status_str','role_str');
        });

        return Response::success($data);
    }

    public function store($request): JsonResponse|JsonResource
    {
        if ($request->user()->role_id == 2) {
            Response::fail('无权限');
        }
        $username = static::findAvailableUsername($request->nickname);
        $user = $this->user->fill($request->all());
        $user->username = $username;
        $user->password = Hash::make($this->pinyin->abbr($request->nickname).'123456');
        $user->user_id = $request->user()->id;
        $user->last_user_id = $request->user()->id;
        try {
            $user->save();
        } catch (Exception $e) {
            Response::fail($e->getMessage() ?? '新增账号失败');
        }

        return Response::success($user);
    }

    public function update($request, $id): JsonResponse|JsonResource
    {
        if ($request->user()->role_id == 2) {
            Response::fail('无权限');
        }

        $user = $this->user->query()
            ->find($id);
        $user->update($request->all());

        return Response::success('更新数据成功');
    }

    public function show($id): JsonResponse|JsonResource
    {
        $data = $this->user->query()
            ->find($id)
            ->append('status_str', 'role_str');

        return Response::success($data);
    }

    public function destroy($user): JsonResponse|JsonResource
    {
        $user->delete();

        return Response::success('删除数据成功');
    }

    public function stint($id): JsonResponse|JsonResource
    {
        $this->user->query()
            ->find($id)
            ->update(['status' => 2]);

        return Response::success('限制该用户登陆成功');
    }

    public static function findAvailableUsername($name): bool|string
    {
        $pinyin = new Pinyin();
        $username = $pinyin->abbr($name)->join('').mt_rand(1000, 9999);

        for ($i = 0; $i < 10; $i++) {
            // 判断是否已经存在
            if (! User::query()->where('username', $username)->exists()) {
                return $username;
            }
        }
        Log::warning('用户名生成失败');

        return false;
    }
}
