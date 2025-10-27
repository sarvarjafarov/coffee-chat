@extends('layouts.admin')

@section('title', 'Header Menu')

@section('actions')
    <button class="btn btn-primary" data-toggle="modal" data-target="#menuItemModal">
        <i class="fas fa-plus-circle"></i> Add link
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
                        <th>Order</th>
                        <th>Visible</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>
                            <input type="text" class="form-control form-control-sm" name="label" value="{{ $item->label }}" form="update-{{ $item->id }}" required>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm" name="url" value="{{ $item->url }}" form="update-{{ $item->id }}" required>
                        </td>
                        <td style="width:120px">
                            <input type="number" class="form-control form-control-sm" name="sort_order" min="0" value="{{ $item->sort_order }}" form="update-{{ $item->id }}">
                        </td>
                        <td style="width:120px">
                            <input type="hidden" name="is_visible" value="0" form="update-{{ $item->id }}">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_visible" value="1" id="visible-{{ $item->id }}" form="update-{{ $item->id }}" {{ $item->is_visible ? 'checked' : '' }}>
                                <label class="form-check-label" for="visible-{{ $item->id }}">Visible</label>
                            </div>
                        </td>
                        <td class="text-right" style="width:180px">
                            <form id="update-{{ $item->id }}" method="POST" action="{{ route('admin.site-menu.update', $item) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-sm btn-outline-primary mr-1">Save</button>
                            </form>
                            <form method="POST" action="{{ route('admin.site-menu.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Remove this menu link?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No menu items defined. Use the button above to add links.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="menuItemModal" tabindex="-1" role="dialog" aria-labelledby="menuItemModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="menuItemModalLabel">Add header link</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.site-menu.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="add-label">Label</label>
                            <input type="text" class="form-control" id="add-label" name="label" required>
                        </div>
                        <div class="form-group">
                            <label for="add-url">URL</label>
                            <input type="url" class="form-control" id="add-url" name="url" placeholder="https://..." required>
                        </div>
                        <div class="form-group">
                            <label for="add-sort-order">Sort order</label>
                            <input type="number" class="form-control" id="add-sort-order" name="sort_order" min="0" value="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
