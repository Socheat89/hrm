<x-layouts.admin>
    <h5 class="mb-3">SaaS Subscription</h5>
    <div class="row g-3 mb-3">
        <div class="col-md-4"><div class="card card-soft p-3"><small class="text-secondary">Monthly Income</small><h4>${{ number_format($monthlyIncome,2) }}</h4></div></div>
        <div class="col-md-4"><div class="card card-soft p-3"><small class="text-secondary">Active Subscriptions</small><h4>{{ $activeSubscriptions }}</h4></div></div>
        <div class="col-md-4"><div class="card card-soft p-3"><small class="text-secondary">Expired Companies</small><h4>{{ $expiredCompanies }}</h4></div></div>
    </div>

    <div class="card card-soft p-3 mb-3">
        <h6 class="mb-3">Plan List</h6>
        <div class="row g-3">
            @foreach($plans as $plan)
                <div class="col-lg-4">
                    <div class="border rounded-3 p-3 h-100">
                        <div class="d-flex justify-content-between"><h6>{{ $plan->name }}</h6><span class="badge bg-light text-dark">${{ number_format($plan->price,2) }}/mo</span></div>
                        <div class="small text-secondary mb-2">{{ $plan->employee_limit }} employees, {{ $plan->branch_limit }} branches</div>
                        <ul class="small mb-0 ps-3">
                            @foreach($plan->feature_list ?? [] as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card card-soft p-3">
        <h6 class="mb-3">Company List</h6>
        <div class="table-responsive">
            <table class="table align-middle"><thead><tr><th>Company</th><th>Plan</th><th>Status</th><th>Expiry</th><th class="text-end">Action</th></tr></thead><tbody>
                @forelse($companies as $company)
                    <tr>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->subscriptionPlan?->name ?? '-' }}</td>
                        <td><span class="badge bg-{{ $company->status === 'active' ? 'success' : ($company->status === 'expired' ? 'danger' : 'secondary') }}">{{ ucfirst($company->status) }}</span></td>
                        <td>{{ $company->expiry_date?->toDateString() ?? '-' }}</td>
                        <td class="text-end"><button class="btn btn-sm btn-outline-primary" disabled>Manage</button></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-secondary">No companies found.</td></tr>
                @endforelse
            </tbody></table>
        </div>
        {{ $companies->links() }}
    </div>
</x-layouts.admin>
