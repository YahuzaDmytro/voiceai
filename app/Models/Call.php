<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                             $id
 * @property int                             $lead_id
 * @property string|null                     $provider
 * @property string|null                     $external_id
 * @property string                          $status
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $ended_at
 * @property int|null                        $duration
 * @property string|null                     $transcript
 * @property string|null                     $recording_url
 * @property array<array-key, mixed>|null    $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Application|null $application
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 *
 * @method static \Database\Factories\CallFactory                    factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereRecordingUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereTranscript($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Call extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at'   => 'datetime',
            'metadata'   => 'array',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
