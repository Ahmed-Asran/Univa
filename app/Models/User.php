<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $user_id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $name
 * @property bool|null $is_active
 * @property bool|null $is_deleted
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|AcademicRecordVersion[] $academic_record_versions
 * @property Collection|AcademicTerm[] $academic_terms
 * @property Collection|Announcement[] $announcements
 * @property Collection|AuditLog[] $audit_logs
 * @property Collection|CalendarEvent[] $calendar_events
 * @property Collection|Course[] $courses
 * @property Faculty|null $faculty
 * @property Collection|Notification[] $notifications
 * @property Collection|PasswordResetToken[] $password_reset_tokens
 * @property Student|null $student
 * @property Collection|Role[] $roles
 *
 * @package App\Models
 */
class User extends Model
{
	protected $table = 'users';
	protected $primaryKey = 'user_id';

	protected $casts = [
		'is_active' => 'bool',
		'is_deleted' => 'bool'
	];

	protected $fillable = [
		'username',
		'email',
		'password_hash',
		'name',
		'is_active',
		'is_deleted'
	];

	public function academic_record_versions()
	{
		return $this->hasMany(AcademicRecordVersion::class, 'changed_by');
	}

	public function academic_terms()
	{
		return $this->hasMany(AcademicTerm::class, 'updated_by');
	}

	public function announcements()
	{
		return $this->hasMany(Announcement::class, 'author_id');
	}

	public function audit_logs()
	{
		return $this->hasMany(AuditLog::class);
	}

	public function calendar_events()
	{
		return $this->hasMany(CalendarEvent::class, 'created_by');
	}

	public function courses()
	{
		return $this->hasMany(Course::class, 'updated_by');
	}

	public function faculty()
	{
		return $this->hasOne(Faculty::class);
	}

	public function notifications()
	{
		return $this->hasMany(Notification::class);
	}

	public function password_reset_tokens()
	{
		return $this->hasMany(PasswordResetToken::class);
	}

	public function student()
	{
		return $this->hasOne(Student::class);
	}

	public function roles()
	{
		return $this->belongsToMany(Role::class, 'user_roles')
					->withPivot('assigned_at');
	}
}
