<?php

namespace Armincms\Wizard\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller; 
use Laravel\Nova\Http\Requests\UpdateResourceRequest;

class ValidateUpdateController extends Controller
{
    /**
     * Create a new resource.
     *
     * @param  \Laravel\Nova\Http\Requests\UpdateResourceRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(UpdateResourceRequest $request)
    {
        $model = $request->findModelQuery()->lockForUpdate()->firstOrFail();

        $resource = $request->newResourceWith($model); 
        $resource::validateForUpdate($request, $resource);

        if ($this->modelHasBeenUpdatedSinceRetrieval($request, $model)) {
            return response('', 409)->throwResponse();
        }

        return response()->json([]);
    }

    /**
     * Determine if the model has been updated since it was retrieved.
     *
     * @param  \Laravel\Nova\Http\Requests\UpdateResourceRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return bool
     */
    protected function modelHasBeenUpdatedSinceRetrieval(UpdateResourceRequest $request, $model)
    {
        $resource = $request->newResource();

        // Check to see whether Traffic Cop is enabled for this resource...
        if ($resource::trafficCop($request) === false) {
            return false;
        }

        $column = $model->getUpdatedAtColumn();

        if (! $model->{$column}) {
            return false;
        }

        return $request->input('_retrieved_at') && $model->usesTimestamps() && $model->{$column}->gt(
            Carbon::createFromTimestamp($request->input('_retrieved_at'))
        );
    }
}
