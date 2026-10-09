<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Services\AdminResourceService;
use App\Http\Requests\Admin\LeadRequest;

class LeadController extends Controller
{
    public function index(AdminResourceService $resources)
    {
        $leads = $resources->all(Lead::class, orderBy: [['id', 'desc']]);

        return view('admin.menu.leads.index', compact('leads'));
    }

    public function create()
    {
        return view('admin.menu.leads.create');
    }

    public function store(LeadRequest $request, AdminResourceService $resources)
    {
        $validated = $request->validated();

        $resources->create(Lead::class, $validated);

        return redirect()
            ->route('admin.menu.leads.index')
            ->with('success', 'Thêm lead thành công.');
    }

    public function show(Lead $lead)
    {
        return redirect()->route(
            'admin.menu.leads.edit',
            $lead
        );
    }

    public function edit(Lead $lead)
    {
        return view('admin.menu.leads.edit', compact('lead'));
    }

    public function update(LeadRequest $request, Lead $lead, AdminResourceService $resources)
    {
        $validated = $request->validated();

        $resources->update($lead, $validated);

        return redirect()
            ->route('admin.menu.leads.index')
            ->with('success', 'Cập nhật lead thành công.');
    }

    public function destroy(Lead $lead, AdminResourceService $resources)
    {
        $resources->delete($lead);

        return redirect()
            ->route('admin.menu.leads.index')
            ->with('success', 'Xóa lead thành công.');
    }
}
