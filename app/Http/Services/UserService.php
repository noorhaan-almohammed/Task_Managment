<?php

namespace App\Http\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserService{

    public function show(){
      $users = User::get();
      return $users;
    }
     public function addUser(array $data){
        $user = User::create($data);
        return $user;
     }
     public function showUser($id){
       $user = User::findOrFail($id);
       return $user->fresh();
     }

     public function updateUser(array $data , User $user){
        dd($data);
        $user->update($data);
        return $user;
     }

     public function delete(User $user){
         $user->delete();
     }

}
