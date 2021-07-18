<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\PlurkTokens
 *
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property string $token_secret
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @method static Builder|PlurkTokens newModelQuery()
 * @method static Builder|PlurkTokens newQuery()
 * @method static Builder|PlurkTokens query()
 * @method static Builder|PlurkTokens whereCreatedAt($value)
 * @method static Builder|PlurkTokens whereId($value)
 * @method static Builder|PlurkTokens whereToken($value)
 * @method static Builder|PlurkTokens whereTokenSecret($value)
 * @method static Builder|PlurkTokens whereUpdatedAt($value)
 * @method static Builder|PlurkTokens whereUserId($value)
 * @mixin Eloquent
 * @property-read User $user
 */
class PlurkTokens extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
