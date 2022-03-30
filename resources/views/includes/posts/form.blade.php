@extends('layouts.app')

@section('content')
@if($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>
@endif

@if($post->exists)
    <h1 class="pb-3"><strong>EDIT POST</strong></h1>
    <hr>
    <form action="{{route('admin.posts.update', $post->id)}}" method="POST" novalidate>
        @method('PUT')
        @else
        <h1 class="pb-3"><strong>CREATE POST</strong></h1>
        <hr>

        <form action="{{ route('admin.posts.store') }}" method="POST" novalidate>
            @endif
            @csrf
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title Post</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="{{ old('title', $post->title) }}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="label">Category Name</label>
                        <select class="custom-select @error('category_id') is-invalid @enderror" name="category_id">
                            <option value="">No Category Name Selected</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @if (old('category_id', $post->category_id) == $category->id) selected @endif>
                                    {{ $category->label }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="content" class="form-label">Description</label>
                        <textarea class="form-control" id="content" name="content" rows="5" placeholder="Insert post description">{{ old('content', $post->content) }}</textarea>
                    </div>
                </div>
                <div class="col-11">
                    <div class="mb-4">
                        <label for="image" class="form-label">Image URL</label>
                        <input type="url" class="form-control" id="image" name="image" placeholder="Insert Image URL" value="{{ old('image', $post->image) }}">
                    </div>
                </div>
                <div class="col-1 mt-1">
                    <img src="https://banksiafdn.com/wp-content/uploads/2019/10/placeholde-image.jpg" alt="Image" width="65" height="60" id="preview">
                </div>
            </div>
            <hr>
            <div class="col-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
            </div>
    </form>

@endsection

@section('scripts')
    <script>
        const placeholder = "https://banksiafdn.com/wp-content/uploads/2019/10/placeholde-image.jpg";

        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('preview');

        imageInput.addEventListener('change', e => {
            const preview = imageInput.value ?? placeholder;
            imagePreview.setAttribute('src', preview)
        });
    </script>
@endsection