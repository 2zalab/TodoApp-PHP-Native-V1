<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TodoStorage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TodoController extends Controller
{
    private TodoStorage $todoStorage;

    public function __construct(TodoStorage $todoStorage)
    {
        $this->todoStorage = $todoStorage;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $todos = $this->todoStorage->all();

        return response()->json([
            'success' => true,
            'message' => 'Todos récupérés',
            'data' => $todos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $todo = $this->todoStorage->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Todo créé avec succès',
            'data' => $todo
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $todo = $this->todoStorage->find($id);

        if (!$todo) {
            return response()->json([
                'success' => false,
                'message' => 'Todo non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $todo
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'sometimes|boolean'
        ]);

        $todo = $this->todoStorage->update($id, $validated);

        if (!$todo) {
            return response()->json([
                'success' => false,
                'message' => 'Todo non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Todo mis à jour avec succès',
            'data' => $todo
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->todoStorage->delete($id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Todo non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Todo supprimé avec succès'
        ]);
    }

    /**
     * Get statistics
     */
    public function stats(): JsonResponse
    {
        $stats = $this->todoStorage->stats();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
