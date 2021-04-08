<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
         $transformerInput = [];

        // iterate through the request, key
        foreach ($request->request->all() as $input => $value) {
            $transformerInput[$transformer::originalAttribute($input)] = $value;
        }

        // replace request
        $request->replace($transformerInput);

        // pass to next 
        $response = $next($request);

        // check what kind of response
        if (isset($respone->exception) && $response->exception instanceof ValidationException) {
            $data = $respone->getData();

            // transform error 
            $transformedError = [];

            // loop through the error and create new error message 
            foreach ($data->error as $field => $value) {
                $trnasformField = $transformer::transformAttribute($field);

                // transform error and replace it in message too
                $transformedError[$trnasformField] = str_replace($field, $trnasformField, $error);
            }

            // collect the data back
            $data->error = $transformedError;

            // set it back
            $respone->setData($data);
        }
        return $respone;
    }
}
