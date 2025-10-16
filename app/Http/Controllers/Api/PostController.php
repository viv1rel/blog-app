<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Posts\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'text' => 'required|string',
            ]);

            $post = $this->postService->createPost($request->user(), $data);

            return response()->json([
                'message' => 'Post created successfully',
                'post' => $post->load('user'),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'limit' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1',
            'sort_field' => 'sometimes|in:created_at,title',
            'sort_direction' => 'sometimes|in:asc,desc',
            'date_from' => 'sometimes|date',
            'date_to' => 'sometimes|date|after_or_equal:date_from',
        ]);

        $posts = $this->postService->getAllPosts($filters);

        return response()->json($posts);
    }

    public function myPosts(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'limit' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1',
            'sort_field' => 'sometimes|in:created_at,title',
            'sort_direction' => 'sometimes|in:asc,desc',
            'date_from' => 'sometimes|date',
            'date_to' => 'sometimes|date|after_or_equal:date_from',
        ]);

        $posts = $this->postService->getUserPosts($request->user(), $filters);

        return response()->json($posts);
    }
}
