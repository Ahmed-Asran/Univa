<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AuditLog
 * 
 * @property int $log_id
 * @property int $user_id
 * @property string $action_type
 * @property string $table_name
 * @property int $record_id
 * @property string|null $old_values
 * @property string|null $new_values
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property Carbon $created_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class AuditLog extends Model
{
	protected $table = 'audit_logs';
	protected $primaryKey = 'log_id';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'record_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'action_type',
		'table_name',
		'record_id',
		'old_values',
		'new_values',
		'ip_address',
		'user_agent'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
