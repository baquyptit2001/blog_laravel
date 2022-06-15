@extends('layout.layout')

@section('title', 'Posts')

@section('content')
    <table id="example" class="table table-striped" style="width:100%">
        <thead>
        <tr>
            <th class="no-search">ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Category</th>
            <th class="no-search">Date</th>
            <th class="no-sort no-search">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->user->name }}</td>
                <td>{{ $post->category->name }}</td>
                <td>{{ $post->created_at }}</td>
                <td>
                    <a href="{{ route('posts.edit', $post->slug) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('posts.destroy', $post->slug) }}" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
@endsection

@section('scripts')
    <x-scripts.datatables/>
@endsection
