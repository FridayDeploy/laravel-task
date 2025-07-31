<?php

namespace App\Http\Controllers;

use App\Contracts\AuthServiceInterface;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthPatientResource;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService,
    )
    {}

    public function login(AuthRequest $request): JsonResponse|AuthPatientResource
    {
        try {
            return $this->authService->loginPatient($request);
        } catch (\Throwable $e) {
            return response()->json(['errors' => ['Patient not found']], 401);
        }
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json();
    }
}
