<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ImageResult;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResultController extends Controller
{
    public function uploadResult(Request $request): JsonResponse
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'order_id' => 'required|exists:orders,id',
                'image' => 'required|image|mimes:jpg,jpeg,png|max:1024',
                'path' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $order = Order::find($request->order_id);

            // Check if an image result already exists for this order
            $existing = ImageResult::where('order_id', $order->id)->first();
            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image result for this order already exists.',
                ], 409);
            }

            if (!$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image file not found in request.'
                ], 400);
            }

            $file = $request->file('image');
            $customPath = 'results/dslrbooth';

            // Store file
            $storedPath = $file->store($customPath, 'public');
            $fileUrl = asset('storage/' . $storedPath);

            // Save image result to DB
            ImageResult::create([
                'order_id' => $order->id,
                'customer_id' => $order->customer_id, // assumes relation exists
                'location' => $request->input('path'),
                'url' => $fileUrl,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded and result saved successfully.',
                'file_url' => $fileUrl,
            ]);

        } catch (\Exception $e) {
            // Optional: log error for debugging
            Log::error('Upload error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the request.',
                'error' => $e->getMessage(), // Remove this in production
            ], 500);
        }
    }
}
