<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot> --}}

    @if (session('reject'))
        <x-toast id="toast-warning"
            color="text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-700 dark:text-orange-200"
            icon="Warning">{{ session('reject') }}</x-toast>
    @endif

    @if (session('success'))
        <x-toast id="toast-success" color="text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200"
            icon="Check">{{ session('success') }}</x-toast>
    @endif

    @if (session('logout'))
        <x-toast id="toast-success" color="text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200"
            icon="Check">{{ session('logout') }}</x-toast>
    @endif

    <div class="py-12">

        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow">

            <div class="py-8 px-5 ">
                <div class="heading text-center font-bold text-2xl m-5 mt-0 text-gray-800 ">Create Post</div>
                <form action="" method="POST" enctype="multipart/form-data" x-data="activateImagePreview()">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id ?? null }}">
                    <div
                        class="editor rounded-lg mx-auto sm:px-5 flex flex-col text-gray-800 border bg-white border-gray-300 p-4 shadow-lg max-w-2xl">

                        <textarea class="content rounded-lg bg-slate-100 sec p-3 h-60 border resize-none border-gray-300 outline-none"
                            spellcheck="false" name="body" placeholder="What's on your mind?"></textarea>

                        <div class="flex mt-4 justify-between">
                            <!-- icons -->
                            <div class="icons flex text-gray-500">
                                <label id="select-image">
                                    <svg class="mr-2 cursor-pointer hover:text-gray-700 border rounded-full p-1 h-7"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    <input
                                        class="hidden image w-full h-10.5 leading-9 rounded overflow-hidden text-sm text-gray-900 bg-gray-50 border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        name="image" id="single_file" accept="image/*"
                                        @change="showPreview(event, $refs.previewSingle)" type="file">
                                </label>
                            </div>
                            <!-- Buttons -->
                            <div class="buttons flex justify-end">
                                <button type="submit"
                                    class="btn submit border border-blue-500 p-1 px-4 font-semibold cursor-pointer text-white rounded ml-2 bg-blue-500 disabled:bg-slate-500 disabled:border-slate-500"
                                    disabled>
                                    Post</button>
                            </div>
                        </div>

                        <!-- Preview image here -->
                        <div x-ref="previewSingle" class="mt-2"></div>
                    </div>
                </form>
            </div>

        </div>

    </div>

    {{-- Home --}}

    @foreach ($posts as $post)
        <div id="post-container-{{ $post->id }}" class="flex justify-center max-w-2xl align-center mx-auto mb-4">
            <div
                class="w-full  bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <div class="float-end justify-end px-4 pt-4">
                    @if (Auth::check() && Auth::user()->id === $post->user_id)
                        <!-- Check if the user is logged in and is the post owner -->
                        <button id="dropdownButton-{{ $post->id }}"
                            data-dropdown-toggle="dropdown-{{ $post->id }}"
                            class="inline-block text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-1.5"
                            type="button">
                            <span class="sr-only">Open dropdown</span>
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 16 3">
                                <path
                                    d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z" />
                            </svg>
                        </button>
                        <!-- Dropdown menu -->
                        <div id="dropdown-{{ $post->id }}"
                            class="z-10 hidden text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                            <ul class="py-2" aria-labelledby="dropdownButton-{{ $post->id }}">
                                <li>
                                    <a href="#" onclick="editPost({{ $post->id }}, event)"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a>
                                </li>
                                <li>
                                    <a href="#" onclick="deletePost({{ $post->id }})"
                                        class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Post Content -->
                <div class="m-5 mb-0 ">
                    <h5 class="text-lg font-medium text-gray-900 dark:text-white">{{ $post->user->name }}</h5>
                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-4">
                        {{ $post->created_at != $post->updated_at ? 'Edited' : '' }}
                        {{ $post->updated_at->diffForHumans() }}</span>
                </div>
                <div class="flex flex-col w-full p-5">
                    <p id="post-body-{{ $post->id }}"
                        class="text-md text-gray-500 dark:text-gray-400 text-justify">{{ $post->body }}</p>
                    <img src="/img/{{ $post->image }}" alt="" class="my-3 shadow-lg rounded-lg">
                </div>
            </div>
        </div>
    @endforeach


    {{-- End Home --}}

    <script>
        $('.content').on('keyup', function() {
            count = this.value.length
            if (count > 0) {
                $('.submit').prop('disabled', false)
            } else {
                $('.submit').prop('disabled', true)
            }
        });

        $('.image').change(function() {
            $('.submit').prop('disabled', false)
        })

        function activateImagePreview() {
            return {
                showPreview(event, previewBox) {
                    previewBox.replaceChildren();

                    for (const i in event.target.files) {
                        let img = document.createElement('img');
                        img.className = 'aspect-auto h-32 shadow';
                        img.src = URL.createObjectURL(event.target.files[i]);
                        previewBox.appendChild(img);
                    }
                }
            }
        }

        function editPost(postId, event) {
            // Prevent the default anchor link behavior
            event.preventDefault();

            // Get the current post body
            var postBody = $('#post-body-' + postId).text().trim();

            // Create a textarea and a save button, replacing the post body
            var editForm = `
        <textarea id="edit-textarea-${postId}" class="w-full text-md resize-none text-gray-500 dark:text-gray-400 text-justify bg-gray-100 dark:bg-gray-800 p-2 rounded">${postBody}</textarea>
        <div class="text-right">
            <button onclick="savePost(${postId})" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">Save</button>
            <button onclick="cancelEdit(${postId}, '${postBody}')" class="mt-2 ml-2 bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
        </div>
    `;

            // Replace the post content with the textarea and button
            $('#post-body-' + postId).html(editForm);

            // Autofocus the textarea
            $('#edit-textarea-' + postId).focus();
        }


        function cancelEdit(postId, originalBody) {
            // Restore the original post body if cancel is clicked
            $('#post-body-' + postId).html(originalBody);
        }

        function savePost(postId) {
            var newBody = $('#edit-textarea-' + postId).val(); // Get the new post content

            $.ajax({
                url: '/posts/' + postId,
                type: 'PUT',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                    body: newBody
                },
                success: function(response) {
                    if (response.success) {
                        // Replace the textarea with the updated post content
                        $('#post-body-' + postId).text(newBody);

                        // Restore the scroll position
                        $(window).scrollTop(currentScrollPosition);
                    } else {
                        alert('Error updating the post');
                    }
                },
                error: function(xhr) {
                    alert('An error occurred while saving the post');
                    console.error(xhr.responseText);
                }
            });
        }


        function deletePost(postId) {
            // Save the current scroll position
            var currentScrollPosition = $(window).scrollTop();

            if (confirm('Are you sure you want to delete this post?')) {
                $.ajax({
                    url: '/posts/' + postId,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content') // Laravel CSRF token
                    },
                    success: function(response) {
                        if (response.success) {
                            // Remove the post container from the page
                            $('#post-container-' + postId).remove();

                            // Restore the scroll position
                            $(window).scrollTop(currentScrollPosition);
                        } else {
                            alert('Error deleting the post');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        alert('An error occurred while deleting the post');
                    }
                });
            }
        }
    </script>
</x-app-layout>
