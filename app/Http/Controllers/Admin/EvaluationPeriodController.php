<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\EvaluationPeriodDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvaluationPeriodRequest;
use App\Http\Requests\UpdateEvaluationPeriodRequest;
use App\Http\Requests\UpdateEvaluationPeriodStatusRequest;
use App\Models\EvaluationPeriod;
use App\Services\PeriodManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EvaluationPeriodController extends Controller
{
    public function __construct(private readonly PeriodManagementService $periodManagementService) {}

    /**
     * Display a list of evaluation periods.
     */
    public function index(): View
    {
        return view('admin.periods.index');
    }

    /**
     * Return the evaluation period data for DataTables.
     */
    public function data(EvaluationPeriodDataTable $dataTable): JsonResponse
    {
        return $dataTable->json();
    }

    /**
     * Show the form for creating a new evaluation period.
     */
    public function create(): View
    {
        return view('admin.periods.create');
    }

    /**
     * Store a newly created evaluation period.
     */
    public function store(StoreEvaluationPeriodRequest $request): RedirectResponse
    {
        $this->periodManagementService->create($request->validated());

        return redirect()
            ->route('admin.periods.index')
            ->with('status', 'Periode evaluasi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the given evaluation period.
     */
    public function edit(EvaluationPeriod $period): View
    {
        return view('admin.periods.edit', [
            'period' => $period,
        ]);
    }

    /**
     * Update the given evaluation period.
     */
    public function update(UpdateEvaluationPeriodRequest $request, EvaluationPeriod $period): RedirectResponse
    {
        $this->periodManagementService->update($period, $request->validated());

        return redirect()
            ->route('admin.periods.index')
            ->with('status', 'Periode evaluasi berhasil diperbarui.');
    }

    /**
     * Change the lifecycle status of the given evaluation period.
     */
    public function updateStatus(UpdateEvaluationPeriodStatusRequest $request, EvaluationPeriod $period): RedirectResponse
    {
        $status = $request->validated('status');

        $this->periodManagementService->changeStatus($period, $status);

        $action = match ($status) {
            EvaluationPeriod::STATUS_ACTIVE => 'diaktifkan',
            EvaluationPeriod::STATUS_CLOSED => 'ditutup',
            EvaluationPeriod::STATUS_ARCHIVED => 'diarsipkan',
            default => 'diperbarui',
        };

        return redirect()
            ->route('admin.periods.index')
            ->with('status', 'Periode evaluasi berhasil '.$action.'.');
    }

    /**
     * Delete the given evaluation period.
     */
    public function destroy(EvaluationPeriod $period): RedirectResponse
    {
        $this->periodManagementService->delete($period);

        return redirect()
            ->route('admin.periods.index')
            ->with('status', 'Periode evaluasi berhasil dihapus.');
    }
}
