<?php

namespace Modules\FrontendMenu\Http\Controllers\Admin;

use Modules\FrontendMenu\Models\FrontendMenu;

use Modules\FrontendMenu\Services\FrontendMenuService;

use Modules\FrontendMenu\Http\Requests\Admin\FrontendMenu\CreateFrontendMenuRequest;
use Modules\FrontendMenu\Http\Requests\Admin\FrontendMenu\UpdateFrontendMenuRequest;

use Modules\FrontendMenu\Http\Resources\Admin\FrontendMenu\FrontendMenuResource;
use Modules\FrontendMenu\Http\Resources\Admin\FrontendMenu\FrontendMenuDetailResource;
use Modules\FrontendMenu\Http\Resources\Admin\FrontendMenu\FrontendMenuPermissionResourceCollection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jiannei\Response\Laravel\Support\Facades\Response;

use Illuminate\Support\Facades\DB;

class FrontendMenuController extends Controller
{

    protected $frontendMenuService;

    public function __construct(
        FrontendMenuService $frontendMenuService
    ) {
        $this->frontendMenuService = $frontendMenuService;
    }

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
            [
                'is_enable' => '篩選啟用',
            ]
        );

        $frontendMenu = FrontendMenu::query();

        if ($request->has('is_enable')) {
            $frontendMenu->whereIsEnable($request->boolean('is_enable'));
        }

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
     * Store a newly created resource in storage.
     *
     * @param  CreateFrontendMenuRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFrontendMenuRequest $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validated();

            $parentId = $request->input('parent.id', null);
            $this->frontendMenuService->associateParent($parentId);

            $this->frontendMenuService->fill($validatedData)->save();

            $items = $request->input('items', []);
            $this->frontendMenuService->updateItems($items);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new FrontendMenuDetailResource($this->frontendMenuService->getModel()->fresh()));
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

        return Response::success(new FrontendMenuDetailResource($frontendMenu));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateFrontendMenuRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFrontendMenuRequest $request, $id)
    {
        $frontendMenu = FrontendMenu::query()->findOrFail($id);

        $this->frontendMenuService->setModel($frontendMenu);

        DB::beginTransaction();

        try {
            $validatedData = $request->validated();

            $parentId = $request->input('parent.id', null);
            $this->frontendMenuService->associateParent($parentId);

            $this->frontendMenuService->fill($validatedData)->save();

            $items = $request->input('items', []);
            $this->frontendMenuService->updateItems($items);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new FrontendMenuDetailResource($this->frontendMenuService->getModel()->fresh()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $frontendMenu = FrontendMenu::query()->findOrFail($id);

        $this->frontendMenuService->setModel($frontendMenu);

        DB::beginTransaction();

        try {
            $this->frontendMenuService->delete();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::noContent();
    }

    public function getPermission()
    {
        $menus = FrontendMenu::with(['childs', 'permissions'])->get();

        $menuPermissionResource = new FrontendMenuPermissionResourceCollection($menus->where('parent_id', null));
        $menuPermissionResource->setMenus($menus);

        return Response::success($menuPermissionResource);
    }
}
