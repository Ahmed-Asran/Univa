<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FeeType
 * 
 * @property int $fee_type_id
 * @property string $type_name
 * @property string|null $description
 * @property Carbon $created_at
 * 
 * @property Collection|StudentFee[] $student_fees
 *
 * @package App\Models
 */
class FeeType extends Model
{
	protected $table = 'fee_types';
	protected $primaryKey = 'fee_type_id';
	public $timestamps = false;

	protected $fillable = [
		'type_name',
		'description'
	];

	public function student_fees()
	{
		return $this->hasMany(StudentFee::class);
	}
}
