<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Services\UserService;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserForm;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Validation\UnauthorizedException;

class UserController extends Controller
{
    /**
     * عرض قائمة المستخدمين.
     */
     protected $userService;
     public function __construct(UserService $userService){
        $this->userService = $userService;
     }
    /**
     * Show All Users
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {

        $users = $this->userService->show();

        return response()->json(['usres' => $users], 200);
    }

    /**
     * create new user
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $user = $this->userService->addUser($validated);
        return response()->json(['message' => 'User created succesfully',
                                    'user' => $user], 201);
    }

    /**
     * Show A Spicific User
     */
    public function show($id)
    {

        $user = $this->userService->showUser($id);

        if (!$user) {
            return response()->json(['message' => 'User Not Exist'], 404);
        }

        return response()->json( ['user' => $user], 200);
    }

    /**
     *
     */
    public function update(UpdateUserForm $request, User $user)
    {
        // $data = $request->validate([
        //     'name' => 'sometimes|string|min:8|max:255',
        //     'email' => 'sometimes|string|email|max:255|unique:users,email',
        //     'password' => 'sometimes|string|min:8',
        //     'role' => 'sometimes|IN:admin,manager,employee',
        // ]);
        // $user->update($data);
        // return $user;
        if (!$user) {
            return response()->json(['message' => 'User Not Exist'], 404);
        }
        try {

            $data = $request->validated();
            $user = $this->userService->updateUser($data, $user);


            return response()->json([
                'message' => 'User Info Updated Successfully',
                'user' => $user
            ], 200);

        } catch (UnauthorizedException $e) {

            return response()->json([
                'message' => 'User does not have the right roles'
            ], 403);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'An error occurred during the update process',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * delete user
     */
    public function destroy(User $user)
    {
        if (!$user) {
            return response()->json(['message' => 'User Not Exist'], 404);
        }
        $this->userService->delete($user);

        return response()->json(['message' => 'User Deleted Succesfully'], 200);    }
}
