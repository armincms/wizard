<?php

namespace Armincms\Wizard\Http\Controllers;

use Illuminate\Routing\Controller; 
use Laravel\Nova\Http\Requests\CreateResourceRequest;

class ValidateStoreController extends Controller
{
    /**
     * Create a new resource.
     *
     * @param  \Laravel\Nova\Http\Requests\CreateResourceRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(CreateResourceRequest $request)
    {
        $resource = $request->resource();  
        $rules = $resource::rulesForCreation($request);

        $request->validate( collect($rules)->only($this->fields($request))->all() ); 
        
        return response()->json([], 200);
    }

    public function fields(CreateResourceRequest $request)
    { 
        return $request
                    ->newResource()
                    ->creationFieldsWithinPanels($request)
                    ->where('panel', $request->viaStep)
                    ->pluck('attribute')
                    ->all();
    }
}
