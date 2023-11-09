<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class ImageController extends Controller
{
    public function index()
    {
        $images = Image::all();
        return response()->json(["status" => "success", "count" => count($images), "data" => $images]);
    }

    public function filterimage(Request $request)
    {
        $images = Image::where('unique_id', $request->unique_id)->get();
        return response()->json([
            'data' => $images
        ]);
    }

    public function update(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'unique_id' => 'required',
                'images' => 'required',
                'images.*' => 'required|mimes:pdf,xlsx,xls,docx,doc,jpeg,png,jpg,gif|max:10240',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => "Validasi error",
                'validateErr' => $validator->errors(),
                'status' => 422,
            ]);
        }

        if ($request->has('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . rand(1, 3) . '.' . $image->getClientOriginalExtension();
                $image->move('uploads/', $filename);

                Image::create([
                    'unique_id' => $request['unique_id'],
                    'image_name' => $filename
                ]);
            }
            return response()->json([
                'message' => 'File berhasil diupload',
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'File gagal diupload',
                'status' => 501,
            ]);
        }
    }

    public function show(Request $request)
    {
        $list = Image::where('relation_id', $request->id)->get(['unique_id', 'object_id', 'relation_id', 'image_name']);
        return response()->json($list);
    }

    public function upload(Request $request)
    {
        $data = json_decode($request->url, true);
        if ($request->has('images')) {
            foreach ($request->file('images') as $image) {
                $id = Image::where('relation_id', $request->id);
                $name = basename($image->getClientOriginalName(), '.' . $image->getClientOriginalExtension());
                $is_avail = Image::where(['relation_id' => $request->id, 'unique_id' => $name])->first();
                $filename = $request->id . '.' . $image->getClientOriginalExtension();
                $image->move('uploads/' . $request->object_id . '/' . $name, $filename);
                if ($is_avail) {
                    $id->update([
                        'unique_id' => $name,
                        'object_id' => $request->object_id,
                        'relation_id' => $request->id,
                        'image_name' => $filename
                    ]);
                } else {
                    Image::create([
                        'unique_id' => $name,
                        'object_id' => $request->object_id,
                        'relation_id' => $request->id,
                        'image_name' => $filename
                    ]);
                }
            }
        }
        foreach ($data as $key => $value) {
            if ($value === "") {
                $id = Image::where(['relation_id' => $request->id, 'unique_id' => $key]);
                $path = public_path('uploads/' . $request->object_id . '/' . $key . '/' . $request->id . '.png');
                if ($path) {
                    File::delete(File::glob($path));
                    $id->delete();
                }
            }
        }
        return response()->json([
            'message' => 'Gambar Berhasil diperbarui',
            'status' => 200,
        ]);
    }

    public function download($id)
    {
        $filepath = public_path("uploads/" . $id);
        $headers = array(
            'Content-Type: image/jpg',
        );
        return response()->download($filepath, $id, $headers);
    }
}
