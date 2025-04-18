<?php

namespace Modules\System\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Modules\System\Models\Viewcount;
use Modules\System\Models\SystemLog;

use Modules\System\Http\Resources\Admin\ViewcountResource;
use Modules\System\Http\Resources\Admin\SystemLogResource;

use Illuminate\Http\Request;

use Jiannei\Response\Laravel\Support\Facades\Response;

class SystemController extends Controller
{
    public function getViewcount(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
        ], [], [
            'start_date' => '開始日期',
            'end_date' => '結束日期',
        ]);

        $viewcount = Viewcount::query();

        if ($request->anyFilled(['start_date', 'end_date'])) {
            $viewcount->where(function ($query) use ($request) {
                if (($start = $request->date('start_date')) !== null) {
                    $query->startOf('created_at', $start->startOfDay());
                }

                if (($end = $request->date('end_date')) !== null) {
                    $query->endOf('created_at', $end->endOfDay());
                }
            });
        }

        $data = collect([
            'count' => $viewcount->count(),
        ]);

        return Response::success(new ViewcountResource($data));
    }

    public function getRecordLog(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'keyword' => 'sometimes|nullable',
            'page_size' => 'sometimes|integer',
            'page' => 'sometimes|integer',
            'orderby' => 'sometimes|string',
        ], [], [
            'start_date' => '開始日期',
            'end_date' => '結束日期',
        ]);

        $systemLog = SystemLog::query()->with(['user']);

        if ($keyword = $request->input('keyword')) {
            $systemLog->whereKeyword(['created_at', 'description', 'sourceip'], $keyword);
        }

        if ($orderby = $request->input('orderby')) {
            $systemLog->queryOrderBy($orderby);
        }

        if ($request->filled(['page_size', 'page'])) {
            $data = $this->paginate($request, $systemLog);
        } else {
            $data = $systemLog->get();
        }

        return Response::success(SystemLogResource::collection($data));
    }
}
