<?php

namespace App\Services;

use App\Models\Call;
use App\Models\Lead;

class CallService
{
    public function create(Lead $lead): Call
    {
        return $lead->calls()->create([
            'provider' => 'livekit',
            'status' => 'pending',
        ]);
    }
}
