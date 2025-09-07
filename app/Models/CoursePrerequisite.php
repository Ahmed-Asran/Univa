<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CoursePrerequisite
 * 
 * @property int $prerequisite_id
 * @property int $course_id
 * @property int $prerequisite_course_id
 * 
 * @property Course $course
 *
 * @package App\Models
 */
class CoursePrerequisite extends Model
{
	protected $table = 'course_prerequisites';
	protected $primaryKey = 'prerequisite_id';
	public $timestamps = false;

	protected $casts = [
		'course_id' => 'int',
		'prerequisite_course_id' => 'int'
	];

	protected $fillable = [
		'course_id',
		'prerequisite_course_id'
	];

	public function course()
	{
		return $this->belongsTo(Course::class, 'prerequisite_course_id');
	}
}
