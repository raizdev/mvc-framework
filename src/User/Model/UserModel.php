<?php
namespace StarreDEV\User\Model;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model {
  
   protected $table = 'users';
   protected $fillable = ['id', 'username', 'email', 'password', 'ip_register', 'ip_current'];
   
}
?>