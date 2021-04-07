<?php

namespace App\Transformers\Post;

use App\Models\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Post $post)
    {
        return [
            // change db to what you want
            "identifier"=> (int) $post->id,
            "postTitle"=> (string) $post->title,
            "postSlug"=> (string) $post->slug,
            "postImage" => (string) $post->image,
            "publish"=> (int) $post->status,
            "postContent"=> (string) $post->content,
            "createdDate"=>  $post->created_at,
            "lastChanged"=>  $post->updated_at,
            "deleteDate" => isset($post->deleted_at) ? (string) $post->deleted_at : null,
            "link" => [
                [
                    "rel" => 'self',
                    "href" => route('post.show', $post->id)
                ],
                [
                    "rel" => 'self',
                    "href" => route('user.show', $post->user_id)
                ],
            ]
        ];
    }

    // to make sure the transformed identifier can be used on url with
    // database names
    public static function originalAttribute($index){
        $attribute = [
            // change db to what you want
            "identifier"=> 'id',
            "postTitle"=> 'title',
            "postSlug"=> 'slug',
            "postImage" => 'image',
            "publish"=> 'status',
            "postContent"=> 'content',
            "createdDate"=>  'created_at',
            "lastChanged"=>  'updated_at',
            "deleteDate" => 'deleted_at'
        ];

        return isset($attribute[$index]) ? $attribute[$index]: null;
    }

    public static function transformAttribute($index){
        $attribute = [
            // change db to what you want
            'id' => "identifier",
            'title' => "postTitle",
            'slug' => "postSlug",
            'image' => "postImage",
            'status' => "publish",
            'content' => "postContent",
            'created_at' => "createdDate",
            'updated_at' => "lastChanged",
            'deleted_at' =>  "deleteDate"
        ];

        return isset($attribute[$index]) ? $attribute[$index]: null;
    }
}
