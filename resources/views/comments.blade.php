@if($comments)
    <div class="cmtlist newest">
        <span class="cmtlist_title">全部评论（{{ $comments->total() }}）</span>
        <div class="cmtlis">
            @foreach($comments as $comment)
                <div class="item ">
                    <div class="float-left photo-container">
                        <div class="photo-wrapper">
                            <img src="{{ $comment->user['avatar'] }}"
                                 alt="{{ $comment->user['nick_name'] }}"></div>
                    </div>
                    <div class="comment-container">
                        <div class="info">
                            <div class="user float-left"><span>{{ $comment->user['nick_name'] }}</span></div>
                        </div>
                        <div class="time-label">{{ date('Y-m-d H:i:s', $comment->created_at) }}</div>
                        <div class="content">
                            <p>{{ $comment->content }}</p>
                            @if($comment->parent)
                                <div class="child">
                                    <span>回复<i>{{ array_get($comment->parent,'user.nick_name')}}</i> :</span>
                                    <div class="content">
                                        {{array_get( $comment->parent,'content')}}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

@endif