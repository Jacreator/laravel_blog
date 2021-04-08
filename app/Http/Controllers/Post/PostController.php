<?php

namespace App\Http\Controllers\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use App\Transformers\Post\PostTransformer;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PostController extends ApiController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(['transform.input:' . PostTransformer::class])->only(['store', 'update']);
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $post = Post::all();
        return $this->showAll($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return $this->showOne($post);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
         // set rules for validation
        $rules = [
            'title' => 'required|min:2|unique:posts',
            'image' => 'required|image',
            'content' => 'required|min:30',
        ];

        // perform validation
        $this->validate($request, $rules);

        // post instance
        $post = new Post;
        // check input feild and make password hash
        $post['title'] = $request->title;
        $post['image'] = $request->image->store('');
        $post['status'] = Post::UNVERIFIED_POST;
        $post['content'] = $request->content;
        $post['user_id'] = $user->id;

        if ($user->isVerified()) {
            // create post
            $post = $post->save();
            return $this->showMessage('Post Created successfully', 201);
        } else {
            return $this->errorResponse("Sorry you verify your account before you can create a post", 409);;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, Post $post)
    {
        // set rules
        $rules = [
            'title' => 'required|min:2|unique:posts',
            'image' => 'required|image',
            'content' => 'required|min:30',
        ];

        $this->validate($request, $rules);

        // check individually what may have been sent
        // title check and update
        if ($request->has('title')) {
            $post->title = $request->title;
        }

        // content check and update verified, verification_token and email
        if ($request->has('content') && $request->content !== $post->content) {
            $post->content = $request->content;
        }

        // check admin and make sure it's only a verified user that can update
        if ($request->has('status')) {
            if (!$user->isAdmin()) {
                return $this->errorResponse('only a verified user can modify post status', 409);
            }

            $post->status = $request->status;
        }

        // check if any value was changed
        if (!$user->isDirty()) {
            return $this->errorResponse('You need to specify at least one different value to update', 422);
        }

        // check if the author and the user updating is the same
        if ($this->postOwner($user, $post) || $user->isAdmin) {

            $post->save();
            // save post information
            return $this->showOne($post, 201);
        }    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Post $post)
    {
        if ($user->isAdmin() || $this->postOwner($user, $post)) {
            Storage::delete($post->image);
            $post->delete();
        }

        return $this->showOne($post);
    }

    protected function postOwner(User $user, Post $post)
    {
        if ($user->id != $post->user_id) {
            throw new HttpException(422, 'the Specified User is not the actual Owner for the product');
        }
    }
}
