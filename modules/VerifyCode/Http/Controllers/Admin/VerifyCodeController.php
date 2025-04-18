<?php

namespace Modules\VerifyCode\Http\Controllers\Admin;

use Modules\VerifyCode\Models\VerifyCode;

use Modules\VerifyCode\Services\VerifyCodeService;
use App\Services\JwtService;

use Modules\VerifyCode\Http\Requests\Admin\VerifyCode\CreateVerifyCodeRequest;

use Modules\VerifyCode\Http\Resources\Admin\VerifyCode\VerifyCodeResource;
use Modules\VerifyCode\Http\Resources\Admin\VerifyCode\VerifyCodeDetailResource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jiannei\Response\Laravel\Support\Facades\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class VerifyCodeController extends Controller
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
                'keyword' => 'sometimes',
                'page_size' => 'sometimes|integer',
                'page' => 'sometimes|integer',
                'orderby' => 'sometimes|string',
            ],
            [],
            []
        );

        $verifyCode = VerifyCode::query();

        if ($memberAccountId = $request->input('member_account_id')) {
            $verifyCode->whereIn('member_account_id', explode(',', $memberAccountId));
        }

        if ($request->has('keyword') && ($keyword = $request->get('keyword')) !== null) {
            $verifyCode->whereKeyword(['name'], $keyword);
        }

        if ($request->has('orderby') && ($orderBy = $request->get('orderby')) !== null) {
            $verifyCode->queryOrderBy($orderBy);
        }

        $data = $request->filled(['page_size', 'page']) ? $this->paginate($request, $verifyCode) : $verifyCode->get();

        return Response::success(VerifyCodeResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateVerifyCodeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateVerifyCodeRequest $request)
    {
        DB::beginTransaction();

        try {
            $inputs = $request->validated();
            $memberAccountId = Arr::get($inputs, 'member_account.id');
            $sub = $memberAccountId ?: ($inputs['account'] ?: ($inputs['email'] ?: $inputs['phone']));
            $contact = [
                "account" => $inputs['account'],
                "email" => $inputs['email'],
                "phone" => $inputs['phone'],
                "member_account_id" => $memberAccountId
            ];
            $token = JwtService::generateToken($sub, 5, $contact);
            $verifyCode = VerifyCodeService::generateVerifyCode($inputs['type'], $token, $inputs['email'], $inputs['phone'], $inputs['account'], $memberAccountId);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new VerifyCodeDetailResource($verifyCode));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $verifyCode = VerifyCode::query()->findOrFail($id);

        return Response::success(new VerifyCodeDetailResource($verifyCode));
    }
}
