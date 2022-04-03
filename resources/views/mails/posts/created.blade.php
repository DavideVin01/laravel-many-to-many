@component('mail::message')
# Post creato con successo

<h2>Titolo Post: {{ $post->title }}</h2>
<p>Descrizione: {{ $post->content }}</p>
<address>Creato da Davide</address>
<p>Pubblicato il: {{ $post->updated_at }}</p>
<strong>Categoria: {{ $post->category->label }}</strong>

<div>Tags: </div>
<ul>
@forelse($post->tags as $tag)
    <li>{{ $tag->label }}</li>
    @empty
    -
    @endforelse
</ul>

@component('mail::button', ['url' => $url])
Vai al post
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent
