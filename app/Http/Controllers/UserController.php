<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Auth;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return response(User::with('role')->paginate(), Response::HTTP_OK);
    }

    /**
     * @param User $user
     * @return Response
     */
    public function show(User $user): Response
    {
        return response($user, Response::HTTP_OK);
    }

    /**
     * @param UserCreateRequest $request
     * @return Response
     */
    public function store(UserCreateRequest $request): Response
    {
        $user = User::create(
            $request->only('first_name', 'last_name', 'username', 'email')
            + [
                'password' => Hash::make('bassword'),
                'role_id' => 3
            ]
        );

        return response($user, Response::HTTP_CREATED);
    }

    /**
     * @param UserUpdateRequest $request
     * @param User $user
     * @return Response
     */
    public function update(UserUpdateRequest $request, User $user): Response
    {
        $user->update($request->only('first_name', 'last_name', 'username', 'email'));

        return response($user, Response::HTTP_ACCEPTED);
    }

    /**
     * @param User $user
     * @return Response
     */
    public function destroy(User $user): Response
    {
        $user->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @return Response
     */
    public function user(): Response
    {
        return response(Auth::user(), Response::HTTP_OK);
    }

    public function info(UpdateInfoRequest $request): Response
    {
        $user = Auth::user();
        $user->update($request->only('first_name', 'last_name', 'username', 'email'));

        return response($user, Response::HTTP_ACCEPTED);
    }

    public function password(UpdatePasswordRequest $request): Response
    {
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return response($user, Response::HTTP_ACCEPTED);
    }
}
