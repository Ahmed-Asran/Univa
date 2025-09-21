<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Enrollment
 * 
 * @property int $enrollment_id
 * @property int $student_id
 * @property int $section_id
 * @property Carbon $enrollment_date
 * @property string|null $status
 * @property string|null $final_grade
 * @property int|null $result
 * 
 * @property Student $student
 * @property CourseSection $course_section
 * @property Collection|AcademicRecordVersion[] $academic_record_versions
 * @property Collection|Attendance[] $attendances
 * @property Collection|Grade[] $grades
 *
 * @package App\Models
 */
class Enrollment extends Model
{
	protected $table = 'enrollments';
	protected $primaryKey = 'enrollment_id';
	public $timestamps = false;

	protected $casts = [
		'student_id' => 'int',
		'section_id' => 'int',
		'enrollment_date' => 'datetime',
		'result' => 'int'
	];

	protected $fillable = [
		'student_id',
		'section_id',
		'enrollment_date',
		'status',
		'final_grade',
		'result'
	];

	public function student()
	{
		return $this->belongsTo(Student::class,'student_id');
	}

	public function course_section()
	{
		return $this->belongsTo(CourseSection::class, 'section_id','section_id');
	}

	public function academic_record_versions()
	{
		return $this->hasMany(AcademicRecordVersion::class);
	}

	public function attendances()
	{
		return $this->hasMany(Attendance::class);
	}

	public function grades()
	{
		return $this->hasMany(Grade::class);
	}
	public function section()
{
    return $this->belongsTo(CourseSection::class, 'section_id', 'section_id');
}
}
