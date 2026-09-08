<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Services\CompleteCallService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AgentWebhookController extends Controller
{
    public function complete(Request $request, Call $call, CompleteCallService $complete): JsonResponse
    {
        $application = $complete->fromCall($call, $request->validate([
            'first_name'        => ['nullable', 'string', 'max:255'],
            'last_name'         => ['nullable', 'string', 'max:255'],
            'outcome'           => ['required', 'in:qualified,not_interested,no_answer,callback'],
            'needs'             => ['nullable', 'string'],
            'next_step'         => ['nullable', 'string', 'max:255'],
            'summary'           => ['required', 'string'],
            'transcript'        => ['nullable', 'array'],
            'transcript.*.role' => ['required_with:transcript', 'in:user,assistant,system'],
            'transcript.*.body' => ['required_with:transcript', 'string'],
        ]));

        return response()->json([
            'ok'                 => true,
            'application_status' => $application->status->value,
            'lead_id'            => $application->lead?->id,
        ]);
    }
}
