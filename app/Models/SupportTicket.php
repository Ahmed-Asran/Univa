<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SupportTicket
 * 
 * @property int $ticket_id
 * @property int $student_id
 * @property string $subject
 * @property string $description
 * @property string|null $status
 * @property Carbon $created_at
 * @property Carbon $resolved_at
 * 
 * @property Student $student
 *
 * @package App\Models
 */
class SupportTicket extends Model
{
	protected $table = 'support_tickets';
	protected $primaryKey = 'ticket_id';
	public $timestamps = false;

	protected $casts = [
		'student_id' => 'int',
		'resolved_at' => 'datetime'
	];

	protected $fillable = [
		'student_id',
		'subject',
		'description',
		'status',
		'resolved_at'
	];

	public function student()
	{
		return $this->belongsTo(Student::class);
	}
}
