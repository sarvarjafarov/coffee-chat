@extends('layouts.admin')

@section('title', 'Posts')

@section('actions')
    <div class="float-sm-right">
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> New Post
        </a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th>Updated</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($posts as $post)
                    <tr>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->slug }}</td>
                        <td>{{ $post->author?->name ?? '—' }}</td>
                        <td>
                            @if($post->is_published)
                                <span class="badge badge-success">Published</span>
                            @else
                                <span class="badge badge-secondary">Draft</span>
                            @endif
                        </td>
                        <td>{{ optional($post->published_at)->format('M d, Y H:i') ?? '—' }}</td>
                        <td>{{ $post->updated_at->diffForHumans() }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.posts.show', $post) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this post?');">
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
                        <td colspan="7" class="text-center text-muted">No posts yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer clearfix">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
