<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\MeasurementExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\GetMeasurementOfDayService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MeasurementController extends Controller
{
    public function __construct(public GetMeasurementOfDayService $getMeasurementOfDayService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): StreamedResponse
    {
        return $this->sseResponse(function () use ($request) {
            return $this->getMeasurementOfDayService->get($request);
        });
    }

    public function export(): BinaryFileResponse
    {
        return Excel::download(new MeasurementExport, 'data-' . now() . '.xlsx');
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
