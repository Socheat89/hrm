<x-layouts.admin>
    <div class="card card-soft p-3"><h5>{{ $branch->name }}</h5><p class="mb-1">{{ $branch->address }}</p><a href="{{ route('admin.branches.index') }}" class="btn btn-light btn-sm">Back</a></div>
</x-layouts.admin>
