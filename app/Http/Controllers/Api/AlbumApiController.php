<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumCollection;
use App\Http\Resources\AlbumResource;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;

class AlbumApiController extends Controller
{
    public function index(Request $request)
    {
        $albums = Album::orderBy('id', 'desc')->paginate(10);
        return (new AlbumCollection($albums))->response()->setStatusCode(200);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'title' => 'required|string|max:255',
                'album_type' => 'required|in:foto,video',
                'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $coverPath = null;
            if ($request->hasFile('cover')) {
                $coverPath = $request->file('cover')->store('album', 'public');
            }

            $data = $validator->validated();
            $data['cover'] = $coverPath;
            $album = Album::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Album created successfully.',
                'data' => new AlbumResource($album)
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
            $album = Album::with('fotos')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Album retrieved successfully.',
                'data' => new AlbumResource($album)
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Album not found.',
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
                'title' => 'required|string|max:255',
                'album_type' => 'required|in:foto,video',
                'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $album = Album::findOrFail($id);
            if ($request->hasFile('cover')) {
                if ($album->cover) {
                    Storage::disk('public')->delete($album->cover);
                }
                $coverPath = $request->file('cover')->store('album', 'public');
                $album->cover = $coverPath;
            }

            $data = $validator->validated();
            unset($data['cover']);
            $album->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Album updated successfully.',
                'data' => new AlbumResource($album)
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
                'message' => 'Album not found.',
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
            $album = Album::findOrFail($id);

            // Delete cover image
            if ($album->cover) {
                Storage::disk('public')->delete($album->cover);
            }

            // Delete related photos
            foreach ($album->fotos as $foto) {
                if ($foto->image) {
                    foreach ($foto->image as $image) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }

            $album->delete();

            return response()->json([
                'success' => true,
                'message' => 'Album deleted successfully.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Album not found.',
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
