<?php

namespace App\Transformers\User;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
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
    public function transform(User $user)
    {
        return [
            // change db to what you want
            "identifier"=> (int) $user->id,
            "userName"=> (string) $user->name,
            "userEmail"=> (string) $user->email,
            "whenVerified"=> $user->email_verified_at,
            "isVerified"=> (int) $user->verified,
            "isAdmin"=> ($user->admin === 'true'),
            "createdDate"=>  $user->created_at,
            "lastChanged"=>  $user->updated_at,
            "deleteDate" => isset($user->deleted_at) ? (string) $user->deleted_at : null,
            "link" => [
                [
                    "rel" => 'self',
                    "href" => route('user.show', $user->id)
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
            "userName"=> 'name',
            "userEmail"=> 'email',
            "whenVerified"=> 'verified_at',
            "isVerified"=> 'verified',
            "isAdmin"=> 'admin',
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
            'name' => "userName",
            'email' => "userEmail",
            'verified_at' => "whenVerified",
            'verified' => "isVerified",
            'admin' => "isAdmin",
            'created_at' => "createdDate",
            'updated_at' => "lastChanged",
            'deleted_at' =>  "deleteDate"
        ];

        return isset($attribute[$index]) ? $attribute[$index]: null;
    }
}
