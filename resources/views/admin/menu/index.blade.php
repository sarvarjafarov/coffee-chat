@extends('layouts.admin')

@section('title', 'Workspace Menu Items')

@section('actions')
    <button class="btn btn-primary" data-toggle="modal" data-target="#newMenuItemModal">
        <i class="fas fa-plus-circle"></i> Add menu item
    </button>
@endsection

@section('content')
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Label</th>
                        <th>URL</th>
                        <th>Created</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->label }}</td>
                            <td><a href="{{ $item->url }}" target="_blank" rel="noopener">{{ $item->url }}</a></td>
                            <td>{{ $item->created_at->format('M d, Y') }}</td>
                            <td class="text-right">
                                <form method="POST" action="{{ route('admin.menu.destroy', $item) }}" onsubmit="return confirm('Remove this menu item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No menu items yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($items->hasPages())
            <div class="card-footer">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    <div class="modal fade" id="newMenuItemModal" tabindex="-1" role="dialog" aria-labelledby="newMenuItemModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newMenuItemModalLabel">Add menu item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.menu.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="label">Label</label>
                            <input type="text" class="form-control" id="label" name="label" required>
                        </div>
                        <div class="form-group">
                            <label for="url">URL</label>
                            <input type="url" class="form-control" id="url" name="url" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
