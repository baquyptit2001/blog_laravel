@extends('layout.layout')

@section('title', 'Categories')

@section('content')
    <h1 class="display-1">Categories</h1>

    <table id="example" class="table table-striped" style="width:100%">
        <thead>
        <tr>
            <th class="no-search">ID</th>
            <th>Name</th>
            <th class="no-sort no-search">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>
                    <a href="{{ route('categories.edit', $category->slug) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('categories.destroy', $category->slug) }}" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>

@endsection

@section('scripts')
    <x-scripts.datatables/>
@endsection

