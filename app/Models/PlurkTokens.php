<?php

namespace App\Models;

use App\Models\PlurkUser\IPlurkUser;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use RuntimeException;

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
 */
class PlurkTokens extends Model implements IPlurkUser
{
    use HasFactory;

    protected $fillable = [
        'token',
        'token_secret',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function getUserToken()
    {
        return $this->token;
    }

    function getUserTokenSecret()
    {
        return $this->token_secret;
    }

    function isAuthorized()
    {
        return true;
    }

    function setOAuthUserToken(string $token, string $tokenSecret)
    {
        throw new RuntimeException('Unsupported operation.');
    }

    function setPermanentUserToken(string $token, string $tokenSecret)
    {
        $this->token = $token;
        $this->token_secret = $tokenSecret;
        $this->save();
    }

    function clearUserToken()
    {
        /* 介面的定義原本是要在此清除 token，不過這裡打算永久保留，故不做任何動作。
         *
         * 日後要找機會修訂這部份的定義。
         */
    }
}
