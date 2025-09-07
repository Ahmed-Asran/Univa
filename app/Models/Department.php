<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Department
 * 
 * @property int $department_id
 * @property string $department_name
 * @property Carbon $created_at
 * 
 * @property Collection|Faculty[] $faculties
 *
 * @package App\Models
 */
class Department extends Model
{
	protected $table = 'department';
	protected $primaryKey = 'department_id';
	public $timestamps = false;

	protected $fillable = [
		'department_name'
	];

	public function faculties()
	{
		return $this->hasMany(Faculty::class);
	}
}
