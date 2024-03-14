<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ImageUploadController extends Controller
{

    public function ImageUpload(Request $request){
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
        ]);
        if ($request->file('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('uploads'), $imageName);
            return response()->json([
                'message' => "Image uploaded successfully",
                'image_url' => url('uploads/' . $imageName),
                'image_name' => $imageName ,
            ], 200);
        } else {
            return response()->json(['error' => 'Image not provided.'], 400);
        }
    }
    public function imageDelete(Request $request){
        $request->validate([
            'image_name' => "required"
        ]);

        $filePath = public_path('uploads/' . $request->image_name);

        if (File::exists($filePath)) {
            File::delete($filePath);
            return response()->json(['message' => 'Image deleted successfully.'], 200);
        } else {
            return response()->json(['error' => 'Image not found.'], 404);
        }
    }


}
