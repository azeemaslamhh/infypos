<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\SmsTemplateUpdateRequest;
use App\Http\Resources\SmsTemplateCollection;
use App\Http\Resources\SmsTemplateResource;
use App\Models\SmsTemplate;
use App\Repositories\SmsTemplateRepository;
use Illuminate\Http\Request;

class SmsTemplateAPIController extends AppBaseController
{
    /** @var SmsTemplateRepository */
    private $smsTemplateRepository;

    public function __construct(SmsTemplateRepository $smsTemplateRepository)
    {
        $this->smsTemplateRepository = $smsTemplateRepository;
    }

    /**
     * @param  Request  $request
     * @return SmsTemplateCollection
     */
    public function index(Request $request)
    {
        $perPage = getPageSize($request);

        $smsTemplates = $this->smsTemplateRepository;

        $smsTemplates = $smsTemplates->paginate($perPage);

        SmsTemplateResource::usingWithCollection();

        return new SmsTemplateCollection($smsTemplates);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * @param  SmsTemplate  $smsTemplate
     * @return SmsTemplateResource
     */
    public function edit(SmsTemplate $smsTemplate)
    {
        return new SmsTemplateResource($smsTemplate);
    }

    /**
     * @param  SmsTemplateUpdateRequest  $request
     * @param $id
     * @return SmsTemplateResource
     */
    public function update(SmsTemplateUpdateRequest $request, $id)
    {
        $input = $request->all();

        $smsTemplate = $this->smsTemplateRepository->updateSmsTemplate($input, $id);

        return new SmsTemplateResource($smsTemplate);
    }

    /**
     * @param $id
     * @return SmsTemplateResource
     */
    public function changeActiveStatus($id)
    {
        $smsTemplate = SmsTemplate::findOrFail($id);
        $status = ! $smsTemplate->status;
        $smsTemplate->update(['status' => $status]);

        return new SmsTemplateResource($smsTemplate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
