<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Notification
 * 
 * @property int $notification_id
 * @property int $user_id
 * @property string $title
 * @property string $message
 * @property string $type
 * @property string|null $priority
 * @property bool|null $is_sent
 * @property bool|null $is_read
 * @property Carbon $scheduled_for
 * @property int|null $retry_count
 * @property string|null $error_message
 * @property Carbon $created_at
 * @property Carbon $sent_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Notification extends Model
{
	protected $table = 'notifications';
	protected $primaryKey = 'notification_id';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'is_sent' => 'bool',
		'is_read' => 'bool',
		'scheduled_for' => 'datetime',
		'retry_count' => 'int',
		'sent_at' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'title',
		'message',
		'type',
		'priority',
		'is_sent',
		'is_read',
		'scheduled_for',
		'retry_count',
		'error_message',
		'sent_at'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
