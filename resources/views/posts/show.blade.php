<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Data Post</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="background: lightgray">

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <img src="{{ asset('storage/posts/'.$post->image) }}" class="w-100 rounded">
                        <hr>
                        <h4>{{ $post->title }}</h4>
                        <p class="tmt-3">
                            {!! $post->content !!}
                        </p>
                    </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="card border-0 shadow-sm rounded mt-4">
                    <div class="card-body">
                        <h5>Komentar ({{ $post->comments->count() }})</h5>
                        <hr>
                        
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('comments.store') }}" method="POST" class="mb-4">
                            @csrf
                            <input type="hidden" name="commentable_id" value="{{ $post->id }}">
                            <input type="hidden" name="commentable_type" value="App\Models\Post">
                            <div class="form-group">
                                <textarea name="body" class="form-control" rows="3" placeholder="Tulis komentar Anda..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim Komentar</button>
                        </form>

                        @forelse($post->comments as $comment)
                            <div class="media mb-3">
                                <div class="media-body">
                                    <h6 class="mt-0 font-weight-bold">{{ $comment->user->name ?? 'User' }} <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small></h6>
                                    {{ $comment->body }}
                                </div>
                            </div>
                            <hr>
                        @empty
                            <div class="text-center text-muted">Belum ada komentar.</div>
                        @endforelse
                    </div>
                </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
