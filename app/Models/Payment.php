<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 * 
 * @property int $payment_id
 * @property int $student_id
 * @property int $fee_id
 * @property float $amount
 * @property string $payment_method
 * @property string|null $transaction_reference
 * @property Carbon $payment_date
 * @property string|null $status
 * 
 * @property Student $student
 * @property StudentFee $student_fee
 *
 * @package App\Models
 */
class Payment extends Model
{
	protected $table = 'payments';
	protected $primaryKey = 'payment_id';
	public $timestamps = false;

	protected $casts = [
		'student_id' => 'int',
		'fee_id' => 'int',
		'amount' => 'float',
		'payment_date' => 'datetime'
	];

	protected $fillable = [
		'student_id',
		'fee_id',
		'amount',
		'payment_method',
		'transaction_reference',
		'payment_date',
		'status'
	];

	public function student()
	{
		return $this->belongsTo(Student::class);
	}

	public function student_fee()
	{
		return $this->belongsTo(StudentFee::class, 'fee_id');
	}
}
