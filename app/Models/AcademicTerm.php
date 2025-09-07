<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AcademicTerm
 * 
 * @property int $term_id
 * @property string $term_name
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property bool|null $is_current
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User|null $user
 * @property Collection|CourseSection[] $course_sections
 *
 * @package App\Models
 */
class AcademicTerm extends Model
{
	protected $table = 'academic_terms';
	protected $primaryKey = 'term_id';

	protected $casts = [
		'start_date' => 'datetime',
		'end_date' => 'datetime',
		'is_current' => 'bool',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'term_name',
		'start_date',
		'end_date',
		'is_current',
		'created_by',
		'updated_by'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'updated_by');
	}

	public function course_sections()
	{
		return $this->hasMany(CourseSection::class, 'term_id');
	}
}
