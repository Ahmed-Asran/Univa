<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Faculty
 * 
 * @property int $faculty_id
 * @property int $user_id
 * @property int|null $department_id
 * @property string|null $position
 * @property bool|null $is_deleted
 * 
 * @property User $user
 * @property Department|null $department
 * @property Collection|CourseMaterial[] $course_materials
 * @property Collection|CourseSection[] $course_sections
 * @property Collection|Grade[] $grades
 *
 * @package App\Models
 */
class Faculty extends Model
{
	protected $table = 'faculty';
	protected $primaryKey = 'faculty_id';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'department_id' => 'int',
		'is_deleted' => 'bool'
	];

	protected $fillable = [
		'user_id',
		'department_id',
		'position',
		'is_deleted'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function department()
	{
		return $this->belongsTo(Department::class);
	}

	public function course_materials()
	{
		return $this->hasMany(CourseMaterial::class, 'uploaded_by');
	}

	public function course_sections()
	{
		return $this->hasMany(CourseSection::class);
	}

	public function grades()
	{
		return $this->hasMany(Grade::class, 'graded_by');
	}
}
