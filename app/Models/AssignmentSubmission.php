<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AssignmentSubmission
 * 
 * @property int $submission_id
 * @property int $assignment_id
 * @property int $student_id
 * @property string|null $submission_text
 * @property string|null $file_path
 * @property Carbon $submitted_at
 * 
 * @property Assignment $assignment
 * @property Student $student
 *
 * @package App\Models
 */
class AssignmentSubmission extends Model
{
	protected $table = 'assignment_submissions';
	protected $primaryKey = 'submission_id';
	public $timestamps = false;

	protected $casts = [
		'assignment_id' => 'int',
		'student_id' => 'int',
		'submitted_at' => 'datetime'
	];

	protected $fillable = [
		'assignment_id',
		'student_id',
		'submission_text',
		'file_path',
		'submitted_at'
	];

	public function assignment()
	{
		return $this->belongsTo(Assignment::class);
	}

	public function student()
	{
		return $this->belongsTo(Student::class);
	}
}
