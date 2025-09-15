<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Course
 * 
 * @property int $course_id
 * @property string $course_code
 * @property string $course_name
 * @property string|null $description
 * @property int $credit_hours
 * @property bool|null $is_active
 * @property bool|null $is_deleted
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User|null $user
 * @property Collection|CoursePrerequisite[] $course_prerequisites
 * @property Collection|CourseSection[] $course_sections
 *
 * @package App\Models
 */
class Course extends Model
{
	protected $table = 'courses';
	protected $primaryKey = 'course_id';

	protected $casts = [
		'credit_hours' => 'int',
		'is_active' => 'bool',
		'is_deleted' => 'bool',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'course_code',
		'course_name',
		'description',
		'credit_hours',
		'is_active',
		'is_deleted',
		'created_by',
		'updated_by'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'updated_by');
	}

	public function course_prerequisites()
	{
		return $this->hasMany(CoursePrerequisite::class, 'course_id', 'course_id');
	}

	public function course_sections()
	{
		return $this->hasMany(CourseSection::class);
	}
	public function prerequisites()
{
    return $this->belongsToMany(
        Course::class,
        'course_prerequisites',
        'course_id',               // the course that needs prerequisites
        'prerequisite_course_id'   // the required course
    );
}
}
