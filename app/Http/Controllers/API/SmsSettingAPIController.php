<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SmsSettingResource;
use App\Models\SmsSetting;
use App\Repositories\SmsSettingRepository;
use Illuminate\Http\Request;

class SmsSettingAPIController extends AppBaseController
{
    /** @var SmsSettingRepository */
    private $smsSettingRepository;

    public function __construct(SmsSettingRepository $smsSettingRepository)
    {
        $this->smsSettingRepository = $smsSettingRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $smsSettings = SmsSetting::where('key', '!=', 'sms_status')->select('key', 'value')->get();
        $data = $smsSettings->toArray();
        $status = SmsSetting::where('key', 'sms_status')->select('key', 'value')->first();

        return $this->sendResponse(new SmsSettingResource(['sms_status' => $status, 'attributes' => $data]),
            'Sms Setting data retrieved successfully.');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @param  Request  $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $smsSettings = $this->smsSettingRepository->updateSmsSettings($input);

        return $this->sendResponse($input['sms_data'], 'Sms Setting data updated successfully');
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
