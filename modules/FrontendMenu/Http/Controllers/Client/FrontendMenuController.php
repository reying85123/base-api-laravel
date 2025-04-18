<?php

namespace Modules\FrontendMenu\Http\Controllers\Client;

use Modules\FrontendMenu\Models\FrontendMenu;

use Modules\FrontendMenu\Http\Resources\Client\FrontendMenu\FrontendMenuResource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jiannei\Response\Laravel\Support\Facades\Response;

class FrontendMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate(
            $request,
            [
                'is_enable' => 'sometimes',
                'keyword' => 'sometimes',
                'page_size' => 'sometimes|integer',
                'page' => 'sometimes|integer',
                'orderby' => 'sometimes|string',
            ],
            [],
            []
        );

        $frontendMenu = FrontendMenu::query();

        $frontendMenu->defaultFrontFilter();

        if ($type = $request->input('type')) {
            $frontendMenu->whereType($type);
        }

        if ($frontendMenuId = $request->input('frontend_menu_id')) {
            $frontendMenu->whereId($frontendMenuId);
        }

        if ($request->has('keyword') && ($keyword = $request->get('keyword')) !== null) {
            $frontendMenu->whereKeyword(['name'], $keyword);
        }

        if ($request->has('orderby') && ($orderBy = $request->get('orderby')) !== null) {
            $frontendMenu->queryOrderBy($orderBy);
        }

        $data = $request->filled(['page_size', 'page']) ? $this->paginate($request, $frontendMenu) : $frontendMenu->get();

        return Response::success(FrontendMenuResource::collection($data));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $frontendMenu = FrontendMenu::query()->findOrFail($id);

        return Response::success(new FrontendMenuResource($frontendMenu));
    }
}
