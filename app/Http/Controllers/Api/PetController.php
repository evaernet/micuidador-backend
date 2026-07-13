<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PetRequest;
use App\Models\Pet;
use App\Services\PetService;
use Illuminate\Http\Request;

class PetController extends Controller
{
    protected PetService $petService;

    public function __construct(PetService $petService)
    {
        $this->petService = $petService;
    }

    public function index(Request $request)
    {
        $pets = $this->petService->listar($request->user());

        return response()->json(['pets' => $pets]);
    }
    public function store(PetRequest $request){
        $pet = $this->petService->crear(
            $request->user(),
            $request->validated(),
            $request->file('foto')
        );

        return response()->json(['pet' => $pet], 201);
    }

    public function update(PetRequest $request, Pet $pet)
    {
        try {
            $pet = $this->petService->actualizar(
                $request->user(),
                $pet,
                $request->validated(),
                $request->file('foto')
            );

            return response()->json(['pet' => $pet]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function destroy(Request $request, Pet $pet)
    {
        try {
            $this->petService->eliminar($request->user(), $pet);

            return response()->json(['message' => 'Mascota eliminada']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}