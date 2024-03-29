@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/post/poll.js', 'resources/js/post/tag.js'])

    <title>{{ config('app.name', 'Laravel') }} | Create post</title>
    <link href="{{ url('css/post.css') }}" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />

    @php
        $url = Request::url();
        $logo = config('app.url', $url) . "/public/logo.png";
    @endphp

    @include('partials.head.ogtags', [
    'title' => "Gamma | Create Post",
    'url' => $url,
    'image' => $logo
    ])

</head>

@include('partials.navbar')

<main class="center">
    <div class="border border-black p-8 my-8 max-w-3xl mx-auto rounded-md shadow-md">
        <h2 class="text-2xl font-semibold mb-4">Create a New Post</h2>

        <form action="{{ route('post.create') }}" method="post" class="grid grid-cols-2 gap-4" enctype="multipart/form-data">
            @csrf

            <div class="mb-4 col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-600">Title: <span class="required-input">*</span></label>
                <input type="text" name="title" id="title" class="mt-1 p-2 border border-gray-300 rounded-md w-full" required>
                @error('title')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>  

                <div class="mb-4">
                    <label for="group" class="block text-sm font-medium text-gray-600 {{ $in_group_already ? 'hidden' : '' }}">Group:</label>
                    <select name="group" id="group" class="mt-1 p-2 border border-gray-300 rounded-md w-full {{ $in_group_already ? 'hidden' : '' }}">
                        @if ($in_group_already)
                            <option value="{{ $groupp->id }}">{{ $groupp->name }}</option>
                        @else
                            <option value="" seleced>None</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="mb-4">
                    <label for="is_private" class="block text-sm font-medium text-gray-600 {{ $in_group_already ? 'hidden' : '' }}">Privacy:</label>
                    <select name="is_private" id="is_private" class="mt-1 p-2 border border-gray-300 rounded-md w-full {{ $in_group_already ? 'hidden' : '' }}" required>
                        @can('publicPost', App\Models\Post::class)
                        <option value="0">Public</option>
                        @else
                        <option value="0" disabled>Public</option>
                        @endcan
                        <option value="1">Private</option>
                    </select>
                </div>

            <div class="mb-4 col-span-2">
                <label for="content" class="block text-sm font-medium text-gray-600">Content: <span class="required-input">*</span></label>
                <textarea name="content" id="content" rows="5" class="mt-1 p-2 border border-gray-300 rounded-md w-full resize-none" required></textarea>
                @error('content')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4 mt-4 col-span-2">
                <button id="add-poll-btn" name="content" class="mt-1 p-2 border border-gray-300 hover:bg-black hover:text-white transition-colors rounded-md w-full resize-none">
                    Add poll +
                </button>

                <article id="poll-creation" class="mt-1 p-2 rounded-md w-full resize-none hidden">
                    <h1 class="text-center text-xl font-bold">Create poll</h1>
                    <h2>Options</h2>
                    <div id="options">
                        <div class="flex flex-row space-x-1">
                            <label for="poll_options[]" class="sr-only">Option</label>
                            <input placeholder="Option" type="text" name="poll_options[]" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                            <button class="remove-option-btn mt-1 p-2 border border-gray-300 hover:bg-black hover:text-white transition-colors rounded-md">
                                -
                            </button>
                        </div>
                    </div>
                    <button id="add-poll-option-btn" class="mt-1 p-2 border border-gray-300 hover:bg-black hover:text-white transition-colors rounded-md w-full resize-none">
                        Add option +
                    </button>
                </article>
            </div>

            <div class="mb-4 col-span-2">
                <label for="attachment" class="block text-sm font-medium text-gray-600">Image:</label>
                <img id="image-preview" src="#" class="my-2 mx-auto w-1/2 hidden" alt="Image preview"/>
                <input type="file" name="attachment" id="attachment" class="hidden" onchange="document.getElementById('image-preview').src = window.URL.createObjectURL(this.files[0]); document.getElementById('remove-img').classList.remove('hidden'); document.getElementById('image-btn').classList.add('hidden'); document.getElementById('image-preview').classList.remove('hidden');">
                <button type="button" id="image-btn" class="form-button py-2 px-4 rounded-md" onclick="document.getElementById('attachment').click()">
                    Upload
                </button>
                <button type="button" id="remove-img" class="form-button py-2 px-4 rounded-md hidden" onclick="document.getElementById('attachment').value = ''; document.getElementById('image-preview').src = '#'; document.getElementById('remove-img').classList.add('hidden'); document.getElementById('image-btn').classList.remove('hidden'); document.getElementById('image-preview').classList.add('hidden');">
                    Remove
                </button>
                @error('attachment')
                    <p class="text-red-500 text-sm">{{ $message }}. Max size is 2mb.</p>
                @enderror
            </div>

            <div class="col-span-2">
                <button type="submit" class="form-button py-2 px-4 rounded-md">Create Post</button>
            </div>
        </form>
    </div>
</main>

@include('partials.footer')
