<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\PostCreatedMail;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('updated_at', 'desc')->paginate(10);
        $categories = Category::all();
        $tags = Tag::orderBy('label', 'ASC')->get();
        return view('admin.posts.index', compact('posts', 'categories', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $post = new Post();
        $categories = Category::all();
        $tags = Tag::orderBy('label', 'ASC')->get();
        return view('admin.posts.create', compact('post', 'categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'title' => ['required', 'string', Rule::unique('posts')->ignore($post->id), 'min:5', 'max:50'],
            'content' => 'required|string',
            'image' => 'nullable|image',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|exists:tags,id',
        ]);

        $data = $request->all();
        $user = Auth::user();
        $data['slug'] = Str::slug($request->title, '-');

        $post = new Post();

        if (array_key_exists('image', $data)){
            $img_url = Storage::put('post_images', $data['image']);
            $data['image'] = $img_url;
        }

        $post->fill($data);

        $post->save();

        // Dopo aver creato il post, aggancio eventuali tag
        if (array_key_exists('tags', $data)) $post->tags()->attach($data['tags']);

        // Mando una mail di conferma
        $mail = new PostCreatedMail($post);
        Mail::to($user->email)->send($mail);

        return redirect()->route('admin.posts.show', $post->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $tags = Tag::orderBy('label', 'ASC')->get();
        
        $post_tags_ids = $post->tags->pluck('id')->toArray();

        $categories = Category::all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags', 'post_tags_ids'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => ['required', 'string', Rule::unique('posts')->ignore($post->id), 'min:5', 'max:50'],
            'content' => 'required|string',
            'image' => 'nullable|image',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|exists:tags,id',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title, '-');

        if (array_key_exists('image', $data)){
            if($post->image) Storage::delete($post->image);

            $img_url = Storage::put('post_images', $data['image']);
            $data['image'] = $img_url;
        }

        $post->update($data);

        if (!array_key_exists('tags', $data)) $post->tags()->detach();
        else $post->tags()->sync($data['tags']);

        return redirect()->route('admin.posts.show', $post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if (count($post->tags)) $post->tags()->detach();

        if($post->image) Storage::delete($post->image);

        $post->delete();

        return redirect()->route('admin.posts.index')->with('message', 'Post eliminato con successo.')->with('type', 'success');
    }
}
