<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Grade
 * 
 * @property int $grade_id
 * @property int $enrollment_id
 * @property int $assignment_id
 * @property float|null $points_earned
 * @property Carbon $graded_date
 * @property int|null $graded_by
 * 
 * @property Enrollment $enrollment
 * @property Assignment $assignment
 * @property Faculty|null $faculty
 *
 * @package App\Models
 */
class Grade extends Model
{
	protected $table = 'grades';
	protected $primaryKey = 'grade_id';
	public $timestamps = false;

	protected $casts = [
		'enrollment_id' => 'int',
		'assignment_id' => 'int',
		'points_earned' => 'float',
		'graded_date' => 'datetime',
		'graded_by' => 'int'
	];

	protected $fillable = [
		'enrollment_id',
		'assignment_id',
		'points_earned',
		'graded_date',
		'graded_by'
	];

	public function enrollment()
	{
		return $this->belongsTo(Enrollment::class);
	}

	public function assignment()
	{
		return $this->belongsTo(Assignment::class);
	}

	public function faculty()
	{
		return $this->belongsTo(Faculty::class, 'graded_by');
	}
}
