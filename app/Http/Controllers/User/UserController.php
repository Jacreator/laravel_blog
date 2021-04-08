<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transformers\User\UserTransformer;

class UserController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware(['transform.input:' . UserTransformer::class])->only(['store', 'update']);
        $this->middleware('auth:api', ['except' => ['store']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        $usersTotal = User::all()->count();

        return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // set rules for validation
        $rules = [
            'name' => 'required|min:2|max:40',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ];
        // perform validation
        $this->validate($request, $rules);

        // check input feild and make password hash
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;

        // create users
        $user = User::create($data);

        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // set rules
        $rules = [
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:8|confirmed',
            'admin' => 'in:' . User::ADMIN_USER . ',' . User::REGULAR_USER,
        ];

        $this->validate($request, $rules);

        // check individually what may have been sent
        // name check and update
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        // email check and update verified, verification_token and email
        if ($request->has('email') && $request->email !== $user->email) {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        // password check
        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        // check admin and make sure it's only a verified user that can update
        if ($request->has('admin')) {
            if (!$user->isVerified()) {
                return $this->errorResponse('only a verified user can modify admin status', 409);
            }

            $user->admin = $request->admin;
        }

        // check if any value was changed
        if (!$user->isDirty()) {
            return $this->errorResponse('You need to specify at least one different value to update', 422);
        }

        // save user information
        $user->save();

        return $this->showOne($user, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->showOne($user);
    }

    /**
     *
     */
    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        // changne status to verified
        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;

        // save user
        $user->save();

        // return message
        return $this->showMessage('the account have been verified Successfully');
    }
}
