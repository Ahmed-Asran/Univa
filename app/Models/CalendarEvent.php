<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CalendarEvent
 * 
 * @property int $event_id
 * @property string $title
 * @property string|null $description
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property string $event_type
 * @property int $created_by
 * @property Carbon $created_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class CalendarEvent extends Model
{
	protected $table = 'calendar_events';
	protected $primaryKey = 'event_id';
	public $timestamps = false;

	protected $casts = [
		'start_date' => 'datetime',
		'end_date' => 'datetime',
		'created_by' => 'int'
	];

	protected $fillable = [
		'title',
		'description',
		'start_date',
		'end_date',
		'event_type',
		'created_by'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}
}
