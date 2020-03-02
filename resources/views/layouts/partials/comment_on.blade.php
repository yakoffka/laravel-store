<div class="row">
    <div class="col-md-12">

        <h2>оставьте свой комментарий</h2>

        <form method="POST" action="/products/{{ $product->id }}/comments">
            @csrf
            @guest
                <div class="form-group">
                    <!-- <label for="user_name">Your name</label> -->
                    <label for="user_name"></label>
                    <input type="text" id="user_name" name="user_name" class="form-control" placeholder="Your name" value="{{ old('user_name') }}" required>
                </div>
            @endguest

            <div class="form-group">
                <!-- <label for="body">Add a comment</label> -->
                <label for="body"></label>
                <textarea id="body" name="body" cols="30" rows="4" class="form-control" placeholder="Add a your comment" required>{{ old('body') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">отправить</button>
        </form>

    </div>
</div>
