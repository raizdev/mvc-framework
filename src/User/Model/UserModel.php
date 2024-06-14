<?php
namespace Raizdev\User\Model;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model {
  
   protected $table = 'users';
   protected $fillable = ['id', 'username', 'password', 'email'];
   protected $hidden = ['password'];
}
?>