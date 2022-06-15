<div class="mb-3">
    <label for="myeditorinstance" class="form-label">Content</label>
    <textarea id="myeditorinstance" name="post">
        @if(isset($post))
            {!! $post->content !!}
        @endif
    </textarea>
</div>
