<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

trait ApiResponser{
    private function successResponse($data, $code){
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code){
        return response()->json(['error'=> $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200){

        // check if collection is empty
        if ($collection->isEmpty()) {
            return $this->successResponse(['data' => $collection], $code);
        }

        // collect the transformer from the collection
        $transformer = $collection->first()->transformer;

        // filter data when needed
        $collection = $this->filterData($collection, $transformer);

        // sort collection
        $collection = $this->sortData($collection, $transformer);

        // paginate collection
        $collection = $this->paginateCollection($collection);

        // transform collection
        $collection = $this->transformData($collection, $transformer);

        // cache collection
        $collection = $this->cacheResponse($collection);

        return $this->successResponse($collection, $code);
    }

    protected function showOne(Model $instance, $code = 200){
        
        // get the transformer from the instance of the model
        $transformer = $instance->transformer;

        // perform transformation on data
        $instance = $this->transformData($instance, $transformer);

        return $this->successResponse($instance, $code);
    }

    protected function showMessage($message, $code = 200){
        return $this->successResponse(['data' => $message], $code);
    }

    // a transformation function
    protected function transformData($data, $transformer){
        // transform the data with factal
        $transaformation = fractal($data, new $transformer);

        // return transfered data as an array
        return $transaformation->toArray();
    }

    // sorting a collection
    protected function sortData(Collection $collection, $transformer){
        // get the sort attribute
        if(request()->has('sort_by')){
            // pass the transformer using it's origianlAttribute method
            // sort the collection
            $sorted = $collection->sortBy($transformer::originalAttribute(request()->sort_by));
            return $sorted;
        }
        return $collection;
    }

    // filter collection base on what is sent in the request
    protected function filterData(Collection $collection, $transformer){
        
        foreach (request()->query() as $query => $value) {
            // get atribute
            $attribute = $transformer::originalAttribute($query);
            if (isset($attribute, $value)) {
                $collection = $collection->where($attribute, $value);
            }
        }
        return $collection;
    }

    // custom paginate function 
    protected function paginateCollection(Collection $collection)
    {
        // rules for input
        $rules = [
            'per_page' => 'integer|min:2|max:50'
        ];

        // validation using support facade
        Validator::validate(request()->all(), $rules);

        // get current page
        $page = LengthAwarePaginator::resolveCurrentPage();

        // number per page
        $perPage = 15;
        if (request()->has('per_page')) {
            $perPage = (int) request()->per_page;
        }

        // result
        $result = $collection->slice(($page) * $perPage, $perPage)-> values();

        $paginated = new LengthAwarePaginator($result, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPage(),
        ]);

        // add other request on the pagination
        $paginated -> appends(request()->all());
        return $paginated;
    }

    protected function cacheResponse($data)
    {
        // get url
        $url = request()->url();

        // get query
        $queryParams = request()->query();

        // sort with ksort
        ksort($queryParams);

        // build new queryString
        $queryString = http_build_query($queryParams);

        // make a full url
        $fullUrl = "{$url}?{$queryString}";
        // time in seconds
        $cacheTime = 30;

        // divide time in seconds
        $cacheDiv = 60;

        // return cahce
        return Cache::remember($fullUrl, $cacheTime/$cacheDiv, function() use($data){
            return $data;
        });
    }
}