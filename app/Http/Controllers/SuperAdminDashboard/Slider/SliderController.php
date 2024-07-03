<?php

namespace App\Http\Controllers\SuperAdminDashboard\Slider;

use App\Http\Controllers\Controller;
use App\Http\Requests\SliderRequest;
use App\Models\slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{

    public function index()
    {
        return $sliders = slider::paginate(12);
    }


    public function store(SliderRequest $request)
    {
        $slider = new slider();
        $slider->slider_name = $request->slider_name;
        if ($request->hasFile('slider_image') && $request->file('slider_image')->isValid()) {
            $slider->slider_image = saveImage($request, 'slider_image');
        }
        $slider->save();
        return response()->json(['message' => 'Slider Added Successfully' , 'data' => $slider]);
    }


    public function show(string $id)
    {
        $slider = slider::find($id);
        if (empty($slider)){
            return response()->json(['message' => 'Slider does not exist'],404);
        }
        return $slider;
    }


    public function update(Request $request, string $id)
    {
        $slider = slider::find($id);
        if (empty($slider))
        {
            return response()->json('message', 'Slider Does Not Exist');
        }
        $slider->slider_name = $request->slider_name ?? $slider->slider_name;
        if ($request->hasFile('slider_image') && $request->file('slider_image')->isValid()) {
            if($slider->slider_image){
                removeImage($slider->slider_image);
            }
            $slider->slider_image = saveImage($request, 'slider_image');
        }
        $slider->save();
        return response()->json(['message' => 'Slider Updated Successfully', 'data' => $slider]);
    }

    public function destroy(string $id)
    {
        $slider = slider::find($id);

        if (empty($slider)) {
            return response()->json([
                'message' => 'Slider does not exist'
            ], 404);
        }
        if ($slider->slider_image){
            removeImage($slider->slider_image);
        }
        $slider->delete();

        return response()->json([
            'message' => 'Slider deleted successfully'
        ]);
    }
}
