<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 28 Feb 2019 21:12:50 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use TCG\Voyager\Models\User;
/**
 * Class PasswordReset
 * 
 * @property string $email
 * @property string $token
 * @property \Carbon\Carbon $created_at
 *
 * @package App\Models
 */
class PasswordReset extends Eloquent
{
	public $incrementing = 'id';
	public $timestamps = false;
	protected $table = 'password_reset';
	protected $hidden = [
		'reset_token'
	];

	protected $fillable = [
		'email',
		'reset_token',
		'updated_at'
	];
	public function user()
	{
		return $this->belongsTo(User::class,'email','email');
	}
}
