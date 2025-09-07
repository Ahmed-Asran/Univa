<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ClassSchedule
 * 
 * @property int $schedule_id
 * @property int $section_id
 * @property string|null $day_of_week
 * @property Carbon $start_time
 * @property Carbon $end_time
 * @property string|null $classroom
 * 
 * @property CourseSection $course_section
 *
 * @package App\Models
 */
class ClassSchedule extends Model
{
	protected $table = 'class_schedules';
	protected $primaryKey = 'schedule_id';
	public $timestamps = false;

	protected $casts = [
		'section_id' => 'int',
		'start_time' => 'datetime',
		'end_time' => 'datetime'
	];

	protected $fillable = [
		'section_id',
		'day_of_week',
		'start_time',
		'end_time',
		'classroom'
	];

	public function course_section()
	{
		return $this->belongsTo(CourseSection::class, 'section_id');
	}
}
