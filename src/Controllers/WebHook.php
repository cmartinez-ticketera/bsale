<?php

namespace ticketeradigital\bsale\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use ticketeradigital\bsale\Events\ResourceUpdated;

class WebHook extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|string|in:post,put,delete',
            'topic' => 'required|string',
            'resource' => 'required|string',
            'resourceId' => 'string',
            'cpnId' => 'integer',
            'send' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $others = $request->except(['action', 'topic', 'resourceId', 'resource']);

        ResourceUpdated::dispatch(
            $request->action,
            $request->topic,
            $request->resourceId,
            $request->resource,
            $others
        );

        return response()->json([], 200);

    }
}
