<?php

namespace ticketeradigital\bsale\Controllers;

use Illuminate\Http\Request;
use ticketeradigital\bsale\Events\ResourceUpdated;

class WebHook extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:post,put,delete',
            'topic' => 'required|string',
            'resource' => 'required|string',
            'resourceId' => 'string',
            'cpnId' => 'integer',
            'send' => 'nullable|numeric',
        ]);

        $others = $request->except(['action', 'topic', 'resource']);

        ResourceUpdated::dispatch(
            $request->action,
            $request->topic,
            $request->resourceId,
            $others
        );

    }
}
