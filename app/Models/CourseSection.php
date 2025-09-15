<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CourseSection
 * 
 * @property int $section_id
 * @property int $course_id
 * @property int $term_id
 * @property int|null $faculty_id
 * @property string $section_number
 * @property int|null $current_enrollment
 * @property string|null $content
 * @property bool|null $is_deleted
 * 
 * @property Course $course
 * @property AcademicTerm $academic_term
 * @property Faculty|null $faculty
 * @property Collection|Announcement[] $announcements
 * @property Collection|Assignment[] $assignments
 * @property Collection|ClassSchedule[] $class_schedules
 * @property Collection|CourseMaterial[] $course_materials
 * @property Collection|Enrollment[] $enrollments
 *
 * @package App\Models
 */
class CourseSection extends Model
{
	protected $table = 'course_sections';
	protected $primaryKey = 'section_id';
	public $timestamps = false;

	protected $casts = [
		'course_id' => 'int',
		'term_id' => 'int',
		'faculty_id' => 'int',
		'current_enrollment' => 'int',
		'is_deleted' => 'bool'
	];

	protected $fillable = [
		'course_id',
		'term_id',
		'faculty_id',
		'section_number',
		'current_enrollment',
		'content',
		'is_deleted'
	];

	public function course()
	{
		return $this->belongsTo(Course::class, 'course_id', 'course_id');
	}

	public function academic_term()
	{
		return $this->belongsTo(AcademicTerm::class, 'term_id');
	}

	public function faculty()
	{
		return $this->belongsTo(Faculty::class);
	}

	public function announcements()
	{
		return $this->hasMany(Announcement::class);
	}

	public function assignments()
	{
		return $this->hasMany(Assignment::class, 'section_id');
	}

	public function class_schedules()
	{
		return $this->hasMany(ClassSchedule::class, 'section_id');
	}

	public function course_materials()
	{
		return $this->hasMany(CourseMaterial::class, 'section_id');
	}

	public function enrollments()
	{
		return $this->hasMany(Enrollment::class, 'section_id');
	}
}
