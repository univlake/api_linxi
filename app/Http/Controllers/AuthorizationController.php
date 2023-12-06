<?php

namespace App\Http\Controllers;

use App\Services\AuthorizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorizationController extends Controller
{
    protected AuthorizationService $authorizationService;

    public function __construct(AuthorizationService $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    public function login(Request $request): JsonResponse|JsonResource
    {
        return $this->authorizationService->login($request);
    }

    public function changePassword(Request $request): JsonResponse|JsonResource
    {
        return $this->authorizationService->changePassword($request);
    }

    public function userInfo(Request $request): JsonResponse|JsonResource
    {
        return $this->authorizationService->userInfo($request);
    }

    public function logout(Request $request): JsonResponse|JsonResource
    {
        return $this->authorizationService->logout($request);
    }
}
