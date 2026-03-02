<x-layouts.admin>
    <div class="d-flex justify-content-between mb-3"><h5 class="mb-0">Departments</h5><a href="{{ route('admin.departments.create') }}" class="btn btn-primary">Add Department</a></div>
    <div class="card card-soft p-3">
        <div class="table-responsive">
            <table class="table"><thead><tr><th>Name</th><th>Branch</th><th class="text-end">Action</th></tr></thead><tbody>
                @forelse($departments as $department)
                    <tr><td>{{ $department->name }}</td><td>{{ $department->branch?->name ?? 'Global' }}</td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.departments.edit',$department) }}">Edit</a> <form method="POST" action="{{ route('admin.departments.destroy',$department) }}" class="d-inline" data-confirm="true">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>
                @empty
                    <tr><td colspan="3" class="text-center text-secondary">No departments.</td></tr>
                @endforelse
            </tbody></table>
        </div>
        {{ $departments->links() }}
    </div>
</x-layouts.admin>
