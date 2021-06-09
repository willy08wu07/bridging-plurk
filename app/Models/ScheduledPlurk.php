<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ScheduledPlurk
 *
 * @property int $id
 * @property string $token 噗浪提供的使用者 token。
 * @property string $token_secret 噗浪提供的使用者 token。
 * @property string $qualifier 修飾詞。
 * @property string $content 噗文內容。長度限制與噗浪系統相同。
 * @property string $scheduled_time 排程的發噗時間。
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledPlurk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledPlurk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledPlurk query()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledPlurk whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledPlurk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledPlurk whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledPlurk whereQualifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledPlurk whereScheduledTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledPlurk whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledPlurk whereTokenSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledPlurk whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ScheduledPlurk extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $casts = [
        'scheduled_time' => 'datetime',
    ];
}
