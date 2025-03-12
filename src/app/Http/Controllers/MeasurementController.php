<?php

namespace App\Http\Controllers;

use App\Exports\MeasurementExport;
use App\Services\GetMeasurementOfDayService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MeasurementController extends Controller
{
    public function __construct(public GetMeasurementOfDayService $getMeasurementOfDayService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): StreamedResponse|array
    {
        if ($request->has('static')) {
            return ['data' => $this->getMeasurementOfDayService->get($request)];
        }

        return $this->sseResponse(function () use ($request) {
            return $this->getMeasurementOfDayService->get($request);
        });
    }

    public function export(): BinaryFileResponse
    {
        return Excel::download(new MeasurementExport, 'data-'.now().'.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
