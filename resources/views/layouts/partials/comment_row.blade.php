<li class="list-group-item" id="comment_{{ $comment->id }}">
    <div class="comment_header">
        {{__($comment->creator->roles->first()->name )}}
        <span class="commentator_name">{{ $comment->user_name }}</span>

        <!-- created_at/updated_at -->
        {{__('comment_published_at: ')}}{{ $comment->formattedCreatedAt() }}
        @if ( $comment->updated_at->timestamp !== $comment->created_at->timestamp )
            ({{__('comment_updated_at: ')}}{{ $comment->formattedUpdatedAt() }})
        @endif

        @auth
            @if( $comment->creator->id === auth()->user()->id )
                <span class="blue">{{__('Your_comment')}}</span>
            @endif

            <div class="comment_buttons">

                <div class="comment_num">#{{ $num_comment+1 }}</div>

                <!-- button edit comment -->
                @auth
                    @if ( (auth()->user()->id === $comment->user_id) || auth()->user()->can('create_products') )
                        <button type="button" class="btn btn-outline-success edit" data-toggle="collapse"
                                data-target="#collapse_{{ $comment->id }}" aria-expanded="false" aria-controls="coll"
                        >
                            <i class="fas fa-pen-nib"></i>
                        </button>
                    @endif
                @endauth

            <!-- button delete comment -->
                @permission('delete_comments')
                <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                @endpermission
            </div>
        @endauth

    </div>

    <div class="comment_str">{!! $comment->body !!}</div>{{-- @deprecated! enable html entities!! --}}

    <div class="date_comment gray">
        <!-- created_at/updated_at -->
        {{__('comment_published_at: ')}}{{ $comment->formattedCreatedAt() }}
        @if ( $comment->updated_at->timestamp !== $comment->created_at->timestamp )
            ({{__('comment_updated_at: ')}}{{ $comment->formattedUpdatedAt() }})
        @endif
    </div>
    <!-- form edit -->
    @auth
        @if ( auth()->user()->id === $comment->user_id && auth()->user()->can('create_products') )
            <form action="/comments/{{ $comment->id }}" method="POST" class="collapse" id="collapse_{{ $comment->id }}">
                @method('PATCH')
                @csrf
                <label for="body_{{ $comment->id }}"></label>
                <textarea id="body_{{ $comment->id }}" name="body" cols="30" rows="4"
                          class="form-control card" placeholder="Add a comment"
                >{{$comment->breakBody()}}</textarea>
                <button type="submit" class="btn btn-success">редактировать</button>
            </form>
        @endif
    @endauth

</li>
