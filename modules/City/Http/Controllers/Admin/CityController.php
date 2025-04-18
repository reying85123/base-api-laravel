<?php

namespace Modules\City\Http\Controllers\Admin;

use Modules\City\Models\City;

use Modules\City\Http\Resources\Admin\CityResource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Jiannei\Response\Laravel\Support\Facades\Response;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'keyword' => 'sometimes|string|nullable',
            'page_size' => 'sometimes|integer',
            'page' => 'sometimes|integer',
            'orderby' => 'sometimes|string',
        ]);

        $city = City::query();

        if($keyword = $request->input('keyword')){
            $city->whereKeyword(['name'], $keyword);
        }

        if($orderby = $request->input('orderby')){
            $city->queryOrderBy($orderby);
        }

        if($request->filled(['page_size', 'page'])){
            $data = $this->paginate($request, $city);
        }else{
            $data = $city->get();
        }

        return Response::success(CityResource::collection($data));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Response::success(new CityResource(City::findOrFail($id)));
    }
}
