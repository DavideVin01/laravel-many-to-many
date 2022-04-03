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
    <form action="{{route('admin.posts.update', $post->id)}}" method="POST" enctype="multipart/form-data" novalidate>
        @method('PUT')
        @else
        <h1 class="pb-3"><strong>CREATE POST</strong></h1>
        <hr>

        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" novalidate>
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
                        <input type="file" class="form-control-file @error('image') is-invalid @enderror" id="image" name="image">
                        @error('image')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-1 mt-1">
                    @if($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->slug }}" width="65" height="60" id="preview">
                    @else
                    <img src="https://banksiafdn.com/wp-content/uploads/2019/10/placeholde-image.jpg" alt="Preview" width="65" height="60" id="preview">
                    @endif
                </div>
                <div class="col-12 tags @error ('tags') is-invalid @enderror">
                    <span class="mr-3">Choose tags:</span>
                    @foreach($tags as $tag)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="tag-{{ $tag->id }}" value="{{ $tag->id }}" name="tags[]" @if (in_array($tag->id, old('tags', $post_tags_ids ?? []))) checked @endif>
                        <label class="form-check-label" for="tag-{{ $tag->id }}">{{ $tag->label }}</label>
                    </div>
                    @endforeach
                </div>
                @error('tags')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
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
            if (imageInput.files && imageInput.files[0]){
                let reader = new FileReader();
                reader.readAsDataURL(imageInput.files[0]);

                reader.onload = e => {
                    imagePreview.setAttribute('src', e.target.result);
                }
            } else imagePreview.setAttribute('src', placeholder);
        });
    </script>
@endsection