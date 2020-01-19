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

        $resource::validateForCreation($request); 

        return response()->json([], 200);
    }
}
