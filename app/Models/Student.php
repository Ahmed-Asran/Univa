<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Student
 * 
 * @property int $student_id
 * @property int $user_id
 * @property string|null $phone
 * @property string|null $address
 * @property float|null $current_gpa
 * @property int|null $total_credits
 * @property string|null $level
 * @property bool|null $is_deleted
 * 
 * @property User $user
 * @property Collection|AssignmentSubmission[] $assignment_submissions
 * @property Collection|Enrollment[] $enrollments
 * @property Collection|Payment[] $payments
 * @property Collection|StudentFee[] $student_fees
 * @property Collection|SupportTicket[] $support_tickets
 *
 * @package App\Models
 */
class Student extends Model
{
	protected $table = 'students';
	protected $primaryKey = 'student_id';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'current_gpa' => 'float',
		'total_credits' => 'int',
		'is_deleted' => 'bool'
	];

	protected $fillable = [
		'user_id',
		'phone',
		'address',
		'current_gpa',
		'total_credits',
		'level',
		'is_deleted'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function assignment_submissions()
	{
		return $this->hasMany(AssignmentSubmission::class);
	}

	public function enrollments()
	{
		return $this->hasMany(Enrollment::class);
	}

	public function payments()
	{
		return $this->hasMany(Payment::class);
	}

	public function student_fees()
	{
		return $this->hasMany(StudentFee::class);
	}

	public function support_tickets()
	{
		return $this->hasMany(SupportTicket::class);
	}
}
