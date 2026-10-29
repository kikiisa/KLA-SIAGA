<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOpdRequest;
use App\Http\Requests\UpdateOpdRequest;
use App\Models\Opd;
use App\Services\OpdManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OpdController extends Controller
{
    public function __construct(private readonly OpdManagementService $opdManagementService) {}

    /**
     * Display a paginated list of OPDs.
     */
    public function index(): View
    {
        return view('admin.opds.index', [
            'opds' => $this->opdManagementService->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new OPD.
     */
    public function create(): View
    {
        return view('admin.opds.create');
    }

    /**
     * Store a newly created OPD.
     */
    public function store(StoreOpdRequest $request): RedirectResponse
    {
        $this->opdManagementService->create($request->validated());

        return redirect()
            ->route('admin.opds.index')
            ->with('status', 'OPD berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the given OPD.
     */
    public function edit(Opd $opd): View
    {
        return view('admin.opds.edit', [
            'opd' => $opd,
        ]);
    }

    /**
     * Update the given OPD.
     */
    public function update(UpdateOpdRequest $request, Opd $opd): RedirectResponse
    {
        $this->opdManagementService->update($opd, $request->validated());

        return redirect()
            ->route('admin.opds.index')
            ->with('status', 'Data OPD berhasil diperbarui.');
    }

    /**
     * Delete the given OPD.
     */
    public function destroy(Opd $opd): RedirectResponse
    {
        $this->opdManagementService->delete($opd);

        return redirect()
            ->route('admin.opds.index')
            ->with('status', 'OPD berhasil dihapus.');
    }
}
