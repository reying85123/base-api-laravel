<?php

namespace Modules\Mailinfo\Http\Controllers\Admin;

use Modules\Mailinfo\Services\MailinfoService;

use Modules\Mailinfo\Models\Mailinfo;

use Modules\Mailinfo\Http\Requests\Admin\CreateMailinfoRequest;
use Modules\Mailinfo\Http\Requests\Admin\UpdateMailinfoRequest;

use Modules\Mailinfo\Http\Resources\Admin\MailinfoResource;

use Jiannei\Response\Laravel\Support\Facades\Response;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MailinfoController extends Controller
{
    /**
     * 取得信件設定列表
     *
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'keyword' => 'sometimes|string|nullable',
            'page_size' => 'sometimes|integer',
            'page' => 'sometimes|integer',
            'orderby' => 'sometimes|string',
        ]);

        $mailinfo = Mailinfo::query();

        if ($keyword = $request->input('keyword')) {
            $mailinfo->whereKeyword(['name'], $keyword);
        }

        if ($orderby = $request->input('orderby')) {
            $mailinfo->queryOrderBy($orderby);
        }

        if ($request->filled(['page_size', 'page'])) {
            $data = $this->paginate($request, $mailinfo);
        } else {
            $data = $mailinfo->get();
        }

        return Response::success(MailinfoResource::collection($data));
    }

    /**
     * 新增信件設定
     *
     * @param  \Modules\Mailinfo\Http\Requests\Mailinfo\CreateMailinfoRequest  $request
     * @param  MailinfoService $mailinfoService
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function store(CreateMailinfoRequest $request, MailinfoService $mailinfoService)
    {
        DB::beginTransaction();

        try {
            $mailinfoService->fill($request->validated());

            $mailinfoService->save();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new MailinfoResource($mailinfoService->getModel()->fresh()));
    }

    /**
     * 取得單一信件設定
     *
     * @param  int  $id
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function show($id)
    {
        return Response::success(new MailinfoResource(Mailinfo::findOrFail($id)));
    }

    /**
     * 更新信件設定
     *
     * @param  \Modules\Mailinfo\Http\Requests\Mailinfo\UpdateMailinfoRequest  $request
     * @param  int  $id
     * @param  MailinfoService $mailinfoService
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function update(UpdateMailinfoRequest $request, $id, MailinfoService $mailinfoService)
    {
        $mailinfoService->setModel(Mailinfo::findOrFail($id));

        DB::beginTransaction();

        try {
            $mailinfoService->fill($request->validated());
            $mailinfoService->save();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new MailinfoResource($mailinfoService->getModel()->fresh()));
    }

    /**
     * 刪除信件設定
     *
     * @param  int  $id
     * @param  MailinfoService $mailinfoService
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function destroy($id, MailinfoService $mailinfoService)
    {
        $mailinfoService->setModel(Mailinfo::findOrFail($id));

        DB::beginTransaction();

        try {
            $mailinfoService->delete();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::noContent();
    }

    public function test()
    {
        MailinfoService::sendMail(Mailinfo::first());
    }
}
