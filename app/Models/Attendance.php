<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Attendance
 * 
 * @property int $attendance_id
 * @property int $enrollment_id
 * @property Carbon $class_date
 * @property string $status
 * @property Carbon $marked_at
 * 
 * @property Enrollment $enrollment
 *
 * @package App\Models
 */
class Attendance extends Model
{
	protected $table = 'attendance';
	protected $primaryKey = 'attendance_id';
	public $timestamps = false;

	protected $casts = [
		'enrollment_id' => 'int',
		'class_date' => 'datetime',
		'marked_at' => 'datetime'
	];

	protected $fillable = [
		'enrollment_id',
		'class_date',
		'status',
		'marked_at'
	];

	public function enrollment()
	{
		return $this->belongsTo(Enrollment::class);
	}
}
