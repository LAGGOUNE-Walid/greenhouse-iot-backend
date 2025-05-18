<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use Illuminate\Http\Request;
use App\Exports\MeasurementExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\GetAllMeasurementsServices;
use App\Services\GetMeasurementOfDayService;
use App\Http\Resources\MeasurementCollection;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MeasurementController extends Controller
{
    public function __construct(
        public GetMeasurementOfDayService $getMeasurementOfDayService,
        public GetAllMeasurementsServices $getAllMeasurementsServices
        ) {}


    /**
     * @group Measurements
     *
     * Get measurements streamed response using server sent event.
     * Or get measurements in simple http json response
     *
     * @queryParam static when passing this query parameter with any value the response will be json response otherwise streamed response will return 
     * @queryParam interval the retention periode , -1 to get last measurements, 0 to get today measurements, N and 0 < N <= 31 to get last N days measurements
     * @response 200 data: {} scenario="Streamed response"
     * @response 200 {} scenario="JSON response"
     * 
     */
    public function index(Request $request): StreamedResponse|array
    {
        if ($request->has('static')) {
            if ($request->has("all")) {
                return ['data' => $this->getAllMeasurementsServices->get()];
            }
            return ['data' => $this->getMeasurementOfDayService->get($request)];
        }

        return $this->sseResponse(function () use ($request) {
            return $this->getMeasurementOfDayService->get($request);
        });
    }
    /**
     * @group Measurements
     *
     *  Export all measurements into .xlsx file
     *
     *  @responseFile 
     */
    public function export(): BinaryFileResponse
    {
        return Excel::download(new MeasurementExport, 'data-' . now() . '.xlsx');
    }

    public function all(Request $request) : MeasurementCollection
    {
        $measurements = Measurement::orderByDesc("created_at")->paginate(12);
        $measurements->setPath(config("app.url")."measurements-table");
        return new MeasurementCollection($measurements);
    }

}
