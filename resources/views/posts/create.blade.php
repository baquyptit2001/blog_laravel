@extends('layout.layout')

@section('title', 'Create Post')
@section('content')
    <h1 class="display-1">Create Post</h1>
    <form method="post" enctype="multipart/form-data">
        @csrf
        <x-errors.form-errors/>
        <div class="mb-3">
            <label for="postTitle" class="form-label">Title</label>
            <input type="text" class="form-control" id="postTitle" aria-describedby="postTitleHelp" name="title">
        </div>
        <x-forms.tinymce-editor/>
        <div class="mb-3">
            <label for="postCategory" class="form-label">Category</label>
            <select class="form-control selectpicker select-cate" tickIcon="x-lg" data-live-search="true" id="postCategory" name="category_id">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="text-center">
            <img src="{{ asset('noimage_food_viet247.jpg') }}" width="400px" id="previewImage">
        </div>
        <div class="mt-3">
            <label for="postImage" class="form-label">Post Image</label>
            <input type="file" class="form-control" id="postImage" aria-describedby="postImage" aria-label="Upload"
                   name="image" onchange="loadFile(event)">
        </div>
        <div class="mt-3">
            <button class="btn btn-success" type="submit">Submit</button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        let loadFile = function (event) {
            let output = document.getElementById('previewImage');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function () {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
        $( document ).ready(function() {
            $('.select-cate').selectpicker();
        });
    </script>
@endsection
