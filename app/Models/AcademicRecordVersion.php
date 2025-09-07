<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AcademicRecordVersion
 * 
 * @property int $version_id
 * @property int $enrollment_id
 * @property string|null $grade
 * @property Carbon $changed_at
 * @property int $changed_by
 * 
 * @property Enrollment $enrollment
 * @property User $user
 *
 * @package App\Models
 */
class AcademicRecordVersion extends Model
{
	protected $table = 'academic_record_versions';
	protected $primaryKey = 'version_id';
	public $timestamps = false;

	protected $casts = [
		'enrollment_id' => 'int',
		'changed_at' => 'datetime',
		'changed_by' => 'int'
	];

	protected $fillable = [
		'enrollment_id',
		'grade',
		'changed_at',
		'changed_by'
	];

	public function enrollment()
	{
		return $this->belongsTo(Enrollment::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'changed_by');
	}
}
