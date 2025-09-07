<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Assignment
 * 
 * @property int $assignment_id
 * @property int $section_id
 * @property string $title
 * @property string|null $description
 * @property Carbon $due_date
 * @property string $type
 * @property Carbon $created_at
 * 
 * @property CourseSection $course_section
 * @property Collection|AssignmentSubmission[] $assignment_submissions
 * @property Collection|Grade[] $grades
 *
 * @package App\Models
 */
class Assignment extends Model
{
	protected $table = 'assignments';
	protected $primaryKey = 'assignment_id';
	public $timestamps = false;

	protected $casts = [
		'section_id' => 'int',
		'due_date' => 'datetime'
	];

	protected $fillable = [
		'section_id',
		'title',
		'description',
		'due_date',
		'type'
	];

	public function course_section()
	{
		return $this->belongsTo(CourseSection::class, 'section_id');
	}

	public function assignment_submissions()
	{
		return $this->hasMany(AssignmentSubmission::class);
	}

	public function grades()
	{
		return $this->hasMany(Grade::class);
	}
}
