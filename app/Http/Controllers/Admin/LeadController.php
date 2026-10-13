<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Services\AdminResourceService;
use App\Http\Requests\Admin\LeadRequest;
use App\Services\CustomerService;

class LeadController extends Controller
{
    public function index(\Illuminate\Http\Request $request, AdminResourceService $resources)
    {
        $leads = $resources->paginate(Lead::class, $request, orderBy: [['id', 'desc']]);

        return view('admin.menu.leads.index', compact('leads'));
    }

    public function create()
    {
        return view('admin.menu.leads.create');
    }

    public function store(LeadRequest $request, AdminResourceService $resources, CustomerService $customers)
    {
        $validated = $request->validated();
        $customer = $customers->resolve($validated['name'], $validated['email'] ?? null, $validated['phone'] ?? null, null, 'lead');
        $validated['customer_id'] = $customer->id;

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

    public function update(LeadRequest $request, Lead $lead, AdminResourceService $resources, CustomerService $customers)
    {
        $validated = $request->validated();
        $customer = $customers->resolve($validated['name'], $validated['email'] ?? null, $validated['phone'] ?? null, null, 'lead');
        $validated['customer_id'] = $customer->id;

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
