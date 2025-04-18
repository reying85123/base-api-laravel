<?php

namespace App\Http\Controllers;

use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Eloquent 查詢用的分頁資料
     *
     * @param Request $request
     * @param Builder $query
     * @param array $columns
     * @param string $pageName
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(Request $request, Builder $query, $columns = ['*'], $pageName = 'page')
    {
        ['page_size' => $pageSize, 'page' => $page] = $request->only(['page_size', 'page']);

        abort_if(is_null($pageSize) || is_null($page), 400, '頁數屬性設定錯誤');

        return $query->paginate(intval($pageSize), $columns, $pageName, intval($page));
    }

    /**
     * 外部 API 資料的分頁處理
     *
     * @param Request $request
     * @param object $data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateExternalApiData(Request $request, $data)
    {
        ['page_size' => $pageSize, 'page' => $page] = $request->only(['page_size', 'page']);

        abort_if(is_null($pageSize) || is_null($page), 400, '頁數屬性設定錯誤');

        $paginator = new LengthAwarePaginator(
            $data->list,
            $data->meta->pagination->total,
            intval($pageSize),
            intval($page),
            ['path' => request()->url()]
        );
        return $paginator;
    }

    /**
     * Collection 的分頁處理
     */
    public function paginateFromCollection(Collection $items, int $page, int $pageSize, Request $request): LengthAwarePaginator
    {
        $pagedItems = $items->forPage($page, $pageSize)->values();

        return new LengthAwarePaginator(
            $pagedItems,
            $items->count(),
            $pageSize,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }
}
