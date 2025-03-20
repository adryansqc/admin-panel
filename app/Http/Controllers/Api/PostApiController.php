<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;

class PostApiController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input('page');
        $limit = $request->input('limit', null);
        $posts = Post::orderBy('id', 'desc')
            ->when($page, function ($query) use ($limit) {
                return $query->paginate($limit);
            }, function ($query) use ($limit) {
                return $query->limit($limit)->get();
            });

        return PostResource::collection($posts)->response()->setStatusCode(200);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'category_id' => 'required|exists:categories,id',
                'judul_berita' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'status' => 'required|in:draft,publish',
                'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'images_caption' => 'nullable|string|max:255',
                'content_body' => 'required|string',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $imagePath = null;
            if ($request->hasFile('images')) {
                $imagePath = $request->file('images')->store('posts', 'public');
            }

            $data = $validator->validated();
            $data['images'] = $imagePath;
            $data['jumlah_view'] = 0;
            $post = Post::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Data created successfully.',
                'data' => new PostResource($post)
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data retrieved successfully.',
                'data' => new PostResource($post)
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'category_id' => 'required|exists:categories,id',
                'judul_berita' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'status' => 'required|in:draft,publish',
                'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'images_caption' => 'nullable|string|max:255',
                'content_body' => 'required|string',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $post = Post::findOrFail($id);
            if ($request->hasFile('images')) {
                if ($post->images) {
                    Storage::disk('public')->delete($post->images);
                }
                $imagePath = $request->file('images')->store('posts', 'public');
                $post->images = $imagePath;
            }

            $data = $validator->validated();
            unset($data['images']); // Remove images from data as we handled it separately
            $post->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Data updated successfully.',
                'data' => new PostResource($post)
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);

            if ($post->images) {
                Storage::disk('public')->delete($post->images);
            }

            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data deleted successfully.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
