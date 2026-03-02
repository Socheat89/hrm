<x-layouts.admin>
    <div class="card card-soft p-3">
        <h5 class="mb-3">{{ isset($department) ? 'Edit Department' : 'Create Department' }}</h5>
        <form method="POST" action="{{ isset($department) ? route('admin.departments.update', $department) : route('admin.departments.store') }}" class="row g-3">
            @csrf @if(isset($department)) @method('PUT') @endif
            <div class="col-md-6"><label class="form-label">Branch</label><select name="branch_id" class="form-select"><option value="">Global</option>@foreach($branches as $branch)<option value="{{ $branch->id }}" @selected(old('branch_id', ($department ?? null)?->branch_id ?? '')==$branch->id)>{{ $branch->name }}</option>@endforeach</select></div>
            <div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" value="{{ old('name', ($department ?? null)?->name ?? '') }}"></div>
            <div class="col-12 d-flex gap-2"><a href="{{ route('admin.departments.index') }}" class="btn btn-light">Back</a><button class="btn btn-primary">Save</button></div>
        </form>
    </div>
</x-layouts.admin>
