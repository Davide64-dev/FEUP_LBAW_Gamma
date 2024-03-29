@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/post/delete.js', 'resources/js/group/enter_leave.js', 'resources/js/group/filter.js', 'resources/js/group/promote.js', 'resources/js/group/block.js', 'resources/js/group/scroll.js', 'resources/js/group/filter.js'])

    <title>{{ config('app.name', 'Laravel') }} | Group {{ $group->name }}</title>

    @php
        $url = Request::url();
        $logo = config('app.url', $url) . "/public/logo.png";
        $title = "Gamma | Group " . $group->name;
    @endphp

    @include('partials.head.ogtags', [
    'title' => $title,
    'url' => $url,
    'image' => $logo
    ])


    <link href="{{ url('css/post.css') }}" rel="stylesheet">

    <script src="https://kit.fontawesome.com/38229b6c34.js" crossorigin="anonymous"></script>
</head>

@include('partials.navbar',['no_margin'=>true])

<div class="flex flex-col md:block md:mb-10">
    <img src="{{ $group->getBannerImage('medium') }}" alt="{{ $group->name }}'s Banner Image" class="w-full h-32 md:h-56 object-cover max-w-full">
    <div class="flex flex-col md:flex-row justify-between px-4 md:mx-56">
        <div class="flex items-center">
            <img src="{{ $group->getGroupImage('medium') }}" alt="{{ $group->name }}'s Image" class="w-24 h-24 md:w-32 md:h-32 ml-4 object-cover rounded-full -mt-14 border-2 border-white max-w-full">
            <h2 class="text-2xl font-bold m-4 w-full break-words overflow-auto">{{ $group->name }}</h2>
        </div>
        <div class="mx-4 mt-4 flex flex-row">
            @auth
                @can('alreadyIn', $group)
                    <form id="groupForm" action="{{ route('groups.leave', $group) }}" method="post" data-method="delete">
                        @csrf
                        @method('DELETE')
                        <button type="submit" id="leaveGroupButton" class="form-button-red font-bold py-2 px-4 rounded">
                            Leave Group
                        </button>
                    </form>
                @else
                    @can('PendingOption', $group)
                        <form id="groupForm" action="{{ route('groups.remove_request', $group) }}" method="post" data-method="delete">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="form-button-red font-bold py-2 px-4 rounded">
                                Remove Request
                            </button>
                        </form>
                    @else
                        <form id="groupForm" action="{{ route('groups.enter', $group) }}" method="post" data-method="post">
                            @csrf
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Enter this group
                            </button>
                        </form>
                    @endcan
                @endcan
                @can('can_modify', $group)
                    <a href="{{ route('group.edit', $group) }}" class="block px-4 py-2 form-button text-center rounded hover:no-underline ml-4 h-fit">
                        Edit
                    </a>
                    <a href="{{ route('group.inviteform', $group) }}" class="block px-4 py-2 form-button-blue text-center rounded hover:no-underline ml-4 h-fit">
                        Invitations
                    </a>
                @endcan
            @endauth
        </div>
    </div>


    <div class="md:grid md:grid-cols-12 md:gap-4 flex flex-col-reverse">
        <main class="col-span-12 md:col-span-8 justify mx-auto w-full">
            @can('alreadyIn', $group)
                <a href="{{ route('post.createFormGroup', ['id' => $group->id]) }}" class="my-4 block mx-auto px-4 py-2 form-button text-center rounded hover:no-underline">Create Post</a> 
            @endcan
            <ul class="tab-container center justify-center flex border border-black rounded shadow my-4">
                <li class="flex w-1/2 {{ $feed === 'posts' ? 'border-t-4 border-black' : '' }} p-2 justify-center">
                    <a href="/group/{{ $group->id }}" class="hover:underline">Posts</a>
                </li>
                <li class="flex w-1/2 {{ $feed === 'members' ? 'border-t-4 border-black' : '' }} p-2 justify-center">
                    <a href="/group/{{ $group->id }}/members" class="hover:underline">Members</a>
                </li>
            </ul>

            @can('viewPostsAndMembers', $group)
                @if($feed === 'posts')
                    <section id="posts">
                        <h1 class="sr-only">Posts</h1>
                        @if($posts->count() == 0)
                            <p class="text-center">No posts found.</p>
                        @else
                            @foreach($posts as $post)
                                @include('partials.post_card', ['post' => $post])
                            @endforeach
                        @endif
                    </section>
                @endif

                @if($feed === 'members')
                    <select id="group-member-filter" name="type" class="mt-1 p-2 w-full border focus:ring-2">
                        <option value="allUsers" selected>All Users</option>
                        <option value="groupOwners">Group Owners</option>
                        <option value="members">Members</option>
                    </select>
                    @if($members->count() == 0)
                        <p class="text-center">No members found.</p>
                    @else
                        <div id="member-cards">
                            @foreach($members as $member)
                                @include('partials.user_card', ['user' => $member, 'adminView' => false, 'is_group' => true, 'group' => $group])
                            @endforeach
                        </div>
                    @endif
                @endif
            @else
                <div class="justify-center h-screen">
                    <div class="bg-red-100 border border-red-500 p-4">
                        <p class="text-red-500">You do not have permission</p>
                    </div>
                </div>
            @endcan
        </main>

        <aside class="md:shadow-md md:border-2 md:border-gray-500 px-2 md:p-10 w-96 rounded-lg col-span-10 md:col-span-3 md:self-start items-start content-start mr-2 md:mr-5 self-center">
            <h3 class="text-xl font-bold mb-2">About Us</h3>
            <p class="mb-2 w-full break-words">{{ $group->description }}</p>
            <div class="border-b border-gray-500 my-4"></div>
            <p class="mb-2">{{ $members->count() }} members</p>
            <p class="mb-2">{{ $posts->count() }} posts</p>
            @if($group->is_private)
                <div class="flex items-center">
                    <i class="fa-solid fa-lock" style="margin-right: 10px; margin-top: -7px"></i>
                    <p class="mb-2 ml-1">Private</p>
                </div>
            @else
                <div class="flex items-center">
                    <i class="fa-solid fa-eye" style="margin-right: 10px; margin-top: -7px"></i>
                    <p class="mb-2">Public</p>
                </div>
            @endif
        </aside>
    </div>
</div>

@include('partials.snackbar')

@include('partials.footer')
