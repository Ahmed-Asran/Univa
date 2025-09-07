<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StudentFee
 * 
 * @property int $fee_id
 * @property int $student_id
 * @property int $fee_type_id
 * @property float $amount
 * @property Carbon $due_date
 * @property string|null $status
 * @property Carbon $created_at
 * 
 * @property Student $student
 * @property FeeType $fee_type
 * @property Collection|Payment[] $payments
 *
 * @package App\Models
 */
class StudentFee extends Model
{
	protected $table = 'student_fees';
	protected $primaryKey = 'fee_id';
	public $timestamps = false;

	protected $casts = [
		'student_id' => 'int',
		'fee_type_id' => 'int',
		'amount' => 'float',
		'due_date' => 'datetime'
	];

	protected $fillable = [
		'student_id',
		'fee_type_id',
		'amount',
		'due_date',
		'status'
	];

	public function student()
	{
		return $this->belongsTo(Student::class);
	}

	public function fee_type()
	{
		return $this->belongsTo(FeeType::class);
	}

	public function payments()
	{
		return $this->hasMany(Payment::class, 'fee_id');
	}
}
