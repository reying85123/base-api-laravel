<?php

namespace Modules\Area\Http\Controllers\Admin;

use Modules\Area\Models\Area;

use Modules\Area\Http\Resources\Admin\AreaResource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Jiannei\Response\Laravel\Support\Facades\Response;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'city_id' => 'sometimes|integer|nullable',
            'keyword' => 'sometimes|string|nullable',
            'page_size' => 'sometimes|integer',
            'page' => 'sometimes|integer',
            'orderby' => 'sometimes|string',
        ], [], [
            'city_id' => '縣市ID',
        ]);

        $area = Area::query();

        if($cityId = $request->input('city_id')){
            $area->whereParentId($cityId);
        }

        if($keyword = $request->input('keyword')){
            $area->whereKeyword(['name'], $keyword);
        }

        if($orderby = $request->input('orderby')){
            $area->queryOrderBy($orderby);
        }

        if($request->filled(['page_size', 'page'])){
            $data = $this->paginate($request, $area);
        }else{
            $data = $area->get();
        }

        return Response::success(AreaResource::collection($data));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Response::success(new AreaResource(Area::findOrFail($id)));
    }
}
