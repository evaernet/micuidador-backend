<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function show(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }

    public function update(ProfileRequest $request)
    {
        $user = $this->profileService->actualizar(
            $request->user(),
            $request->validated(),
            $request->file('foto')
        );

        return response()->json(['user' => $user]);
    }
}
