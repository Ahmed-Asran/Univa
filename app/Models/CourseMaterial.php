<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CourseMaterial
 * 
 * @property int $material_id
 * @property int $section_id
 * @property string $title
 * @property string|null $description
 * @property string $type
 * @property string|null $file_path
 * @property int $uploaded_by
 * @property Carbon $upload_date
 * 
 * @property CourseSection $course_section
 * @property Faculty $faculty
 *
 * @package App\Models
 */
class CourseMaterial extends Model
{
	protected $table = 'course_materials';
	protected $primaryKey = 'material_id';
	public $timestamps = false;

	protected $casts = [
		'section_id' => 'int',
		'uploaded_by' => 'int',
		'upload_date' => 'datetime'
	];

	protected $fillable = [
		'section_id',
		'title',
		'description',
		'type',
		'file_path',
		'uploaded_by',
		'upload_date'
	];

	public function course_section()
	{
		return $this->belongsTo(CourseSection::class, 'section_id');
	}

	public function faculty()
	{
		return $this->belongsTo(Faculty::class, 'uploaded_by');
	}
}
