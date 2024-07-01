<?php

//function saveImage($request)
//{
//    if ($request->file('image')){
//        $image = $request->file('image');
//        $imageName = rand() . '.' . $image->getClientOriginalExtension();
//        $directory = 'adminAsset/image/';
//        $imgUrl = $directory . $imageName;
//        $image->move($directory, $imageName);
//        return $imgUrl;
//    }
//    if ($request->file('id_card')){
//        $image = $request->file('id_card');
//        $imageName = rand() . '.' . $image->getClientOriginalExtension();
//        $directory = 'adminAsset/id_card/';
//        $imgUrl = $directory . $imageName;
//        $image->move($directory, $imageName);
//        return $imgUrl;
//    }
//    if ($request->file('cover-image')){
//        $image = $request->file('cover-image');
//        $imageName = rand() . '.' . $image->getClientOriginalExtension();
//        $directory = 'adminAsset/cover-image/';
//        $imgUrl = $directory . $imageName;
//        $image->move($directory, $imageName);
//        return $imgUrl;
//    }
//}

use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

function saveImage($request, $type)
{
    $directoryMap = [
        'image' => 'adminAsset/image/',
        'id_card' => 'adminAsset/id_card/',
        'cover-image' => 'adminAsset/cover-image/',
    ];

    if ($request->file($type)) {
        $image = $request->file($type);
        $imageName = rand() . '.' . $image->getClientOriginalExtension();
        $directory = $directoryMap[$type];

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $imgUrl = $directory . $imageName;

        try {
            $image->move($directory, $imageName);
            return $imgUrl;
        } catch (FileException $e) {
            Log::error("File upload error: " . $e->getMessage());
            throw new \Exception("Error uploading file: " . $e->getMessage());
        }
    }

    return null;
}


function removeImage($imagePath)
{
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}
