<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Asset;
use App\Models\User;
// use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Asset as AssetResource;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use App\Http\Controllers\API\EventHistoryController;
use App\Models\EventHistory;

class AssetController extends BaseController
{
    public function adminShowAll()
    {
        $assets = Asset::all();
        return $this->sendResponse($assets, 'All available assets retrieved successfully.');
    }

    public function basicShowAll()
    {
        $assets = Asset::all()->take(4);

        return $this->sendResponse($assets, 'Shown first 4 assets for basic users.');
    }

    public function showAll()
    {
        /** @var \App\Models\User */
        $assets = Auth::user()->assets;
        return $this->sendResponse(AssetResource::collection($assets), 'Assets retrieved successfully.');
    }

    public function showUserAsset($id)
    {
        $user = User::find($id);
        $assets = $user->assets;
        return $this->sendResponse(AssetResource::collection($assets), 'Assets retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->except(['isVerified', 'eventHistory', 'assetImage']);
        $asset = new Asset($input);
        /** @var \App\Models\User */
        Auth::user()->assets()->save($asset);

        //Assign event history
        $eventHistories = $request->json('eventHistory');
        
        if($eventHistories){
            foreach($eventHistories as &$event){
                $asset->EventHistories()->create($event);
            }
        }

        //Assign asset images
        $assetImages = $request->json('assetImage');
        
        if($assetImages){
            foreach($assetImages as &$image){
                $asset->AssetImages()->create($image);
            }
        }

        $data = [
            'asset' => $asset,
            'eventHistory' => $asset->EventHistories()->get(),
            'assetImage' => $asset->AssetImages()->get()
        ];

        return $this->sendResponse($data, 'Asset created successfully.');
    }

    public function show($id)
    {
        $asset = Auth::user()->assets()->find($id);

        if (is_null($asset)) {
            return $this->sendError('Asset not found for this user.');
        }

        $data = [
            'asset' => $asset,
            'eventHistory' => $asset->EventHistories()->get(),
            'assetImage' => $asset->AssetImages()->get()
        ];

        return $this->sendResponse($data, 'Asset retrieved successfully.');
    }

    public function showSingleAdmin($id)
    {
        $asset = Asset::find($id);
        return $this->sendResponse($asset, 'Asset retrieved successfully.');
    }

    public function update($id)
    {
    
        $currentUser = Auth::user();

        $userRole = $currentUser->role()->first();

        // If user isn't admin, they can only update their own assets
        if($userRole->role != 'admin'){
            $asset = Auth::user()->assets()->find($id);
            if (is_null($asset)) {
                return $this->sendError('Asset not found.');
            }
        }

        // Update Event History if set
        $eventHistories = $asset->EventHistories()->get();
        if(request()->json('eventHistory')){
            $inputHistories = request()->json('eventHistory');
            
            $i=0;
            foreach($eventHistories as  $event){
                $event->update($inputHistories[$i]);
                $i+=1;
            }
        }
        $asset->update(request()->except(['isVerified', 'eventHistory', 'assetImage']));

        // Update Asset Image if set
        $assetImages = $asset->AssetImages()->get();
        if(request()->json('assetImage')){
            $inputImages = request()->json('assetImage');

            $i=0;
            foreach($assetImages as $image){
                $image->update($inputImages[$i]);
                $i+=1;
            }
        }

        $data = [
            'asset' => $asset,
            'eventHistory' => $asset->EventHistories()->get(),
            'assetImage' => $asset->AssetImages()->get()
        ];
        
        return $this->sendResponse($data, 'Asset updated successfully.');
    }

    public function verify($id)
    {
        $asset = Auth::user()->assets()->find($id);
        if (is_null($asset)) {
            return $this->sendError('Asset not found.');
        }
        // $asset->update(request()->all());
        $asset->update(request()->only(['isVerified']));
        return $this->sendResponse(new AssetResource($asset), 'Asset verified successfully.');
    }

    public function delete($id)
    {
        $asset = Auth::user()->assets()->find($id);
        if (is_null($asset)) {
            return $this->sendError('Asset not found.');
        }
        $asset->delete();
        return $this->sendResponse(null, 'Asset deleted successfully');
    }
}