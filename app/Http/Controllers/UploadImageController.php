<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Image;

class UploadImageController extends Controller
{

	public function getImages(){
		// Fetching images in descending order
		$data = Image::orderBy('id', 'DESC')->get();
		return response()->json([
			'message' => 'Images fetched successfully.', 
			'data' => $data
		]);
	}

    public function uploadImages(Request $request){
    	$images = $request->file('images');
	    
	    // Checking if 'images' folder exist in public folder or not, if does not exist then creating folder
	    $folderPath = public_path('images');
	    if (!file_exists($folderPath)) {
	        mkdir($folderPath, 0777, true);
	    }

	    foreach ($images as $imageData) {

	    	// Using time(), uniqid() and original extension of image to set unique image name 
	        $fileName = time().'_'.uniqid().'.'.$imageData->getClientOriginalExtension();

	        while (file_exists($folderPath.'/'.$fileName)) {
	            $fileName = time().'_'.uniqid().'.'.$imageData->getClientOriginalExtension();
	        }
	        
	        // Storing image in 'images' folder with new image name
	        $imageData->move($folderPath, $fileName);

	        // Storing image location in database
	        $image = new Image();
	        $image->file_path = 'images/' . $fileName;
	        $image->save();
	    }

	    return response()->json(['message' => 'Images saved successfully.']);
    }

    public function updateImage(Request $request){
    	$image = $request->file('image');
    	$imageId = $request->input('imageId');
    	$folderPath = public_path('images');

    	// Fetching image with image id
    	$row = Image::where('id', $imageId)->first();
		$this->deletePreviousImage($row->file_path);

		// Using time(), uniqid() and original extension of image to set unique image name
		$fileName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension(); 

		// Storing image in 'images' folder with new image name
		$image->move($folderPath, $fileName);

		// Updating image location in database
		$row->update([
            'file_path' => 'images/' . $fileName
        ]);  	

        return response()->json(['message' => 'Images updated successfully.']);

    }

    public function deletePreviousImage($file_path){
    	// Getting full location of image
    	$absolutePath = public_path($file_path);

    	// Checking if image exists
    	if(file_exists($absolutePath)){
    		// Deleting image from 'images' folder
    		unlink($absolutePath);
    	}

    	return true;
    }

    public function deleteImage(Request $request){
    	$imageId = $request->input('imageId');
    	$imagePath = $request->input('imagePath');

    	$this->deletePreviousImage($imagePath);

    	// Deleting image from database
    	DB::table('images')
		    ->where('id', $imageId)
		    ->delete();

    }
}
