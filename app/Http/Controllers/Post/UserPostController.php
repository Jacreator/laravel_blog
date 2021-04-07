<?php

namespace App\Http\Controllers\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transformers\User\UserTransformer;

class UserPostController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        // $this->middleware('trasform.input:' . UserTransformer::class)->only(['store', 'update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {

        $posts = $user->posts;


        return $this->showAll($posts);
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
        $this->validate($request->all(), $rules);

        // post instance
        $post = new Post;
        // check input feild and make password hash
        $post['title'] = $request->title;
        $post['slug'] = Post::sluggable();
        $post['image'] = $request->image->store('');
        $post['status'] = Post::UNVERIFIED_POST;
        $post['content'] = $request->content;
        $post['user_id'] = $user->id;

        if ($user->isVerified()) {
            // create users
            $post = $post->save();
            return $this->showOne($post);
        } else {
            return $this->errorResponse("Sorry you verify your account before you can create a post", 409);;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
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
        if (!$post->isDirty()) {
            return $this->errorResponse('You need to specify at least one different value to update', 422);
        }

        // check if the author and the user updating is the same
        if (postOwner($user, $post) && $user->isAdmin) {
            $post->save();
        }

        // save post information
        return $this->showOne($post, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Post $post)
    {
        if ($user->isAdmin() && $user->isVerified() ) {
            Storage::delete($post->image);
            $post->delete();
        }

        return $this->showOne($post);
    }

    protected function postOwner(User $user, Post $post)
    {
        if ($user->id != $post->author) {
            throw new HttpException(422, 'the Specified User is not the actual Owner for the product');
        }
    }
}
