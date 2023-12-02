@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/post/delete.js'])

    <link href="{{ url('css/post.css') }}" rel="stylesheet">
    <link href="{{url('css/group.css')}}" rel="stylesheet">

    <script src="https://kit.fontawesome.com/38229b6c34.js" crossorigin="anonymous"></script>
</head>

@include('partials.navbar')

<div class="grid grid-cols-12">
    <main class="col-span-12 md:col-span-8 justify">
        @can('create', App\Models\Post::class)
        <a href="{{ route('post.createForm') }}" class="my-4 block mx-auto px-4 py-2 bg-black text-white text-center rounded">Create Post</a> 
        @endcan
        <ul class="tab-container center justify-center flex border border-black rounded shadow my-4">
            <li class="flex w-1/2 {{ $feed === 'posts' ? 'border-t-4 border-black' : '' }} p-2 justify-center">
                <a href="/group/{{$group->id}}" class="hover:underline">Posts</a>
            </li>
            <li class="flex w-1/2 {{ $feed === 'members' ? 'border-t-4 border-black' : '' }} p-2 justify-center">
                <a href="/group/{{$group->id}}/members" class="hover:underline">Members</a>
            </li>
        </ul>


        @if($feed === 'posts')
            @if($posts->count() == 0)
                <p class="text-center">No posts found.</p>
            @else
                @for($i = 0; $i < $posts->count(); $i++) 
                    @include('partials.post_card', ['post'=> $posts->get()[$i]])
                @endfor
            @endif
        @endif

        @if($feed === 'members')
            <select name="type" class="mt-1 p-2 w-full border focus:ring-2">
                <option value="allUsers" selected>All Users</option>
                <option value="groupOwners">Group Owners</option>
                <option value="members">Members</option>
            </select>
            @if($members->count() == 0)
                <p class="text-center">No members found.</p>
            @else
                @for($i = 0; $i < $members->count(); $i++) 
                    @include('partials.user_card', ['user' => $members->get()[$i], 'adminView' => false])
                @endfor
            @endif
        @endif


    </main>

    <aside class="border-2 border-gray-500 p-10 w-96 rounded-lg col-span-10 md:col-span-3 self-start items-start content-start mr-2 md:mr-5">

        <h2 class="text-3xl font-bold mb-4">{{$group->name}}</h2>

        <p class="mb-2">{{$group->description}}</p>

        <div class="border-b border-gray-500 my-4"></div>

        <p class="mb-2">{{$members->count()}} members</p>

        <p class="mb-2">{{$posts->count()}} posts</p>

        @if($group->is_private)
            <div class="flex items-center">
                <i class="fa-solid fa-lock" style="margin-right: 10px; margin-top:-7px"></i>
                <p class="mb-2 ml-1">Private</p>
            </div>
        @else
            <div class="flex items-center">
                <i class="fa-solid fa-eye" style="margin-right: 10px; margin-top:-7px"></i>
                <p class="mb-2">Public</p>
            </div>  
        @endif
    </aside>

</div>