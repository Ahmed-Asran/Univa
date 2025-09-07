<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Announcement
 * 
 * @property int $announcement_id
 * @property string $title
 * @property string $content
 * @property int $author_id
 * @property int|null $course_section_id
 * @property bool|null $is_published
 * @property Carbon $created_at
 * 
 * @property User $user
 * @property CourseSection|null $course_section
 *
 * @package App\Models
 */
class Announcement extends Model
{
	protected $table = 'announcements';
	protected $primaryKey = 'announcement_id';
	public $timestamps = false;

	protected $casts = [
		'author_id' => 'int',
		'course_section_id' => 'int',
		'is_published' => 'bool'
	];

	protected $fillable = [
		'title',
		'content',
		'author_id',
		'course_section_id',
		'is_published'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'author_id');
	}

	public function course_section()
	{
		return $this->belongsTo(CourseSection::class);
	}
}
