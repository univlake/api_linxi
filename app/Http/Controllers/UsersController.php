<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request): JsonResponse|JsonResource
    {
        return $this->userService->index($request);
    }

    public function store(Request $request): JsonResponse|JsonResource
    {
        return $this->userService->store($request);

    }

    public function update(Request $request, $id): JsonResponse|JsonResource
    {
        return $this->userService->update($request, $id);

    }

    public function show($id): JsonResponse|JsonResource
    {
        return $this->userService->show($id);

    }

    public function destroy(User $user): JsonResponse|JsonResource
    {
        return $this->userService->destroy($user);

    }

    public function stint($id): JsonResponse|JsonResource
    {
        return $this->userService->stint($id);

    }
}
