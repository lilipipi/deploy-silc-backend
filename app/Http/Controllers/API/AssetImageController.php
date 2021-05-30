<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\AssetImage as AssetImageResource;
use App\Http\Resources\AssetImageCollection;
use App\Models\AssetImage;
use App\Models\User;
use App\Models\Role;
use Exception;

class AssetImageController extends BaseController
{
    public function showAll()
    {
        return new AssetImageCollection(AssetImage::all());
    }

    public function show($id)
    {
        return AssetImageController::searchImage($id);
    }
    
    public function store(Request $request)
    {
        $assetImage = AssetImage::create($request->all());

        return (new AssetImageResource($assetImage))
            ->response()
            ->setStatusCode(201);
    }

    public function delete($id)
    {
        $response = AssetImageController::searchImage($id);

        if (get_class($response) !== "App\Models\AssetImage") {
            return $response;
        }
        $response->delete();

        return $response;
    }

    public function update($id)
    {
        $response = AssetImageController::searchImage($id);

        if (get_class($response) !== "App\Models\AssetImage") {
            return $response;
        }
        $update = request()->all();
        $response->update($update);

        return $response;
    }

    public static function searchImage($id)
    {
        try {
            return AssetImage::findOrFail($id);
        } catch (Exception $e) {
            $res = ("Image with id " . $id . " not found");
            return response()->json($res, 404);
        }
    }
}