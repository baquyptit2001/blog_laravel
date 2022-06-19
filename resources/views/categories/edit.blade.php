@extends('layout.layout')

@section('title', 'Create Category')

@section('content')
<h1 class="display-1">Edit Category</h1>

<form method="post">
    @csrf
    <x-errors.form-errors />
    <div class="mb-3">
        <label for="categoryName" class="form-label">Category Name</label>
        <input type="text" class="form-control" id="categoryName" aria-describedby="categoryHelp" name="name" value="{{ $category->name }}">
        <div id="categoryHelp" class="form-text">We'll never share your email with anyone else.</div>
    </div>
    <div class="mb-3">
        <label for="categoryDescription" class="form-label">Category Description</label>
        <textarea class="form-control" id="categoryDescription" name="description">{{ $category->description }}</textarea>
    </div>
    <div class="mb-3">
        <label for="categorySortOrder" class="form-label">Sort Order</label>
        <input type="number" class="form-control" id="categorySortOrder" name="sort_order" value="{{ $category->sort_order }}">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
</form>

@endsection
