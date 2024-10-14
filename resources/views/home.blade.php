<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot> --}}

    @if (session('reject'))
        <x-alert type="red" message="{{ session('reject') }}" title="Error" />
    @endif

    @if (session('success'))
        <x-alert type="green" message="{{ session('success') }}" title="Success" />
    @endif

    @if (session('logout'))
        <x-alert type="green" message="{{ session('logout') }}" title="Logout Success" />
    @endif


    <x-create-post></x-create-post>

    {{-- Home --}}

    @foreach ($posts as $post)
        <div id="post-container-{{ $post->id }}" class="flex justify-center max-w-2xl align-center mx-auto mb-4">
            <div class="w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <div class="float-end justify-end px-4 pt-4">
                    @if (Auth::check() && Auth::user()->id === $post->user_id)
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

                <div class="m-5 mb-0 ">
                    <h5 class="text-lg font-medium text-gray-900 dark:text-white">{{ $post->user->name }}</h5>
                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-4">
                        {{ $post->created_at != $post->updated_at ? 'Edited' : '' }}
                        {{ $post->updated_at->diffForHumans() }}</span>

                    <div class="flex flex-col w-full pt-3">
                        <p id="post-body-{{ $post->id }}"
                            class="text-md text-gray-900 dark:text-gray-400 text-justify">{{ $post->body }}</p>
                        @if ($post->image)
                            <img src="/img/{{ $post->image }}" alt="" class="my-3 shadow-lg rounded-lg">
                        @endif
                    </div>

                    {{-- Comments section --}}
                    <div id="comments-section-{{ $post->id }}">
                        @if ($post->comments->count() > 0)
                            <span id="comment-count-{{ $post->id }}"
                                class="flex justify-end text-slate-400 my-2 text-sm">
                                {{ $post->comments->count() }}
                                {{ $post->comments->count() > 1 ? 'Comments' : 'Comment' }}
                            </span>
                            @foreach ($post->comments as $comment)
                                <div class="text-justify text-base bg-slate-200 rounded-lg p-3 mb-2 relative">
                                    <p class="font-medium">{{ $comment->user->name }}</p>
                                    <p class="text-slate-600 comment-body-{{ $comment->id }}">{{ $comment->body }}
                                    </p>
                                    <span class="text-slate-400 text-xs mt-2 block text-edited">
                                        {{ $comment->updated_at->diffForHumans() }}
                                        {{ $comment->created_at != $comment->updated_at ? '(edited)' : '' }}
                                    </span>

                                    <!-- Dropdown button, only visible to the comment owner -->
                                    @if (Auth::check() && Auth::id() === $comment->user_id)
                                        <button class="absolute top-2 right-2 text-gray-500 focus:outline-none"
                                            onclick="toggleCommentDropdown(event, {{ $comment->id }})">
                                            &#x22EE; <!-- This is a vertical ellipsis icon -->
                                        </button>

                                        <!-- Dropdown menu -->
                                        <div id="comment-dropdown-{{ $comment->id }}"
                                            class="comment-dropdown hidden absolute right-0 top-2 mt-0 ml-2 w-48 bg-white rounded-lg shadow-lg z-10">
                                            <ul class="py-1">
                                                <li>
                                                    <button
                                                        onclick="editComment({{ $comment->id }}, {{ $post->id }})"
                                                        class="block px-4 py-2 text-left text-gray-800 hover:bg-gray-100 w-full">Edit</button>
                                                </li>
                                                <li>
                                                    <button
                                                        onclick="deleteComment({{ $comment->id }}, {{ $post->id }})"
                                                        class="block px-4 py-2 text-left text-gray-800 hover:bg-gray-100 w-full">Delete</button>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <span id="comment-count-{{ $post->id }}"
                                class="flex justify-end text-slate-400 mb-2 text-sm">&nbsp;</span>
                        @endif
                    </div>


                    {{-- Add comment --}}
                    <div class="relative w-full mb-3">
                        @auth
                            <input type="text" name="comment" id="comment-{{ $post->id }}"
                                class="w-full p-2 pr-16 rounded-lg border border-gray-300"
                                placeholder="Comment as {{ auth()->user()->name }}" autocomplete="off"
                                onkeypress="checkEnter(event, {{ $post->id }})">
                            <button onclick="addComment({{ $post->id }})"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 flex items-center justify-center bg-blue-500 text-white rounded-lg w-8 h-8 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    class="h-4 w-4">
                                    <path d="M22 12l-20 12 7.289-12-7.289-12z" fill="white" />
                                </svg>
                            </button>
                        @endauth
                    </div>


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

        function addComment(postId) {
            const commentInput = document.getElementById(`comment-${postId}`);
            const commentText = commentInput.value.trim();

            if (commentText === '') {
                alert('Comment cannot be empty!'); // Alert if the comment is empty
                return;
            }

            // Use AJAX to send the comment to the server
            $.ajax({
                url: `/posts/${postId}/comments`, // Adjust the URL based on your routes
                type: 'POST',
                data: {
                    body: commentText,
                    _token: '{{ csrf_token() }}' // Include CSRF token
                },
                success: function(response) {
                    // Assuming response contains the new comment data
                    const commentSection = document.getElementById(`comments-section-${postId}`);
                    const newComment = document.createElement('div');
                    newComment.className = 'text-justify text-base bg-slate-200 rounded-lg p-3 mb-2';
                    newComment.innerHTML = `
        <p class="font-medium">${response.user.name}</p>
        <p class="text-slate-600">${response.body}</p>
        <span class="text-slate-400 text-xs mt-2 block">${response.created_at}</span>
    `;

                    // Append the new comment to the comment section
                    commentSection.appendChild(newComment);

                    // Update the comment count
                    const commentCountElement = document.getElementById(`comment-count-${postId}`);
                    const currentCount = parseInt(commentCountElement.textContent) || 0; // Get current count
                    const newCount = currentCount + 1; // Increment count

                    // Set the appropriate comment text format
                    if (newCount === 0) {
                        commentCountElement.textContent = ''; // No comments
                    } else if (newCount === 1) {
                        commentCountElement.textContent = '1 Comment'; // Single comment
                    } else {
                        commentCountElement.textContent = `${newCount} Comments`; // Multiple comments
                    }

                    // Clear the input field after adding the comment
                    commentInput.value = '';
                },
                error: function(xhr) {
                    // Handle errors here
                    alert('Failed to add comment. Please try again.');
                }
            });
        }

        // Check for 'Enter' key press
        function checkEnter(event, postId) {
            if (event.key === 'Enter') {
                addComment(postId);
                event.preventDefault(); // Prevent the default form submission
            }
        }

        // Dropdown Comment
        function toggleCommentDropdown(event, commentId) {
            event.stopPropagation(); // Prevent event from bubbling up
            // Hide other dropdowns
            $('.comment-dropdown').not(`#comment-dropdown-${commentId}`).addClass('hidden');

            // Toggle the current dropdown
            $(`#comment-dropdown-${commentId}`).toggleClass('hidden');
        }

        // Hide dropdowns when clicking outside
        $(document).on('click', function() {
            $('.comment-dropdown').addClass('hidden');
        });


        // Edit Comment
        function editComment(commentId, postId) {
            // Get the current comment body
            const commentBodyElement = document.querySelector(`.comment-body-${commentId}`);
            const currentCommentBody = commentBodyElement.textContent.trim();

            // Replace the comment body with an input field
            commentBodyElement.innerHTML =
                `<input type="text" id="edit-input-${commentId}" value="${currentCommentBody}" class="border p-1 w-full"/>`;

            // Add a listener for the Enter key to submit the edit
            const editInput = document.getElementById(`edit-input-${commentId}`);
            editInput.focus(); // Focus on the input field

            // Handle Enter key press
            editInput.addEventListener('keypress', function(event) {
                if (event.key === 'Enter') {
                    const updatedComment = editInput.value.trim();

                    // Update the comment via AJAX
                    $.ajax({
                        url: `/posts/${postId}/comments/${commentId}`,
                        type: 'PUT',
                        data: {
                            body: updatedComment,
                            _token: '{{ csrf_token() }}' // Include CSRF token
                        },
                        success: function(response) {
                            // Update the comment body and timestamp
                            commentBodyElement.innerHTML = `
                        ${response.body} 
                    `;
                        },
                        error: function(xhr) {
                            alert('Failed to update comment. Please try again.');
                        }
                    });
                } else if (event.key === 'Escape') {
                    // Cancel the edit and restore the original comment body
                    commentBodyElement.innerHTML = currentCommentBody;
                }
            });

            // Close dropdown if it is open
            const dropdown = document.getElementById(`comment-dropdown-${commentId}`);
            if (dropdown) {
                dropdown.classList.add('hidden');
            }
        }

        // Function to update the comment
        function updateComment(postId, commentId) {
            const editCommentDiv = document.getElementById(`edit-comment-${commentId}`);
            const editCommentInput = editCommentDiv.querySelector('.edit-comment-input');
            const newCommentText = editCommentInput.value.trim();

            if (newCommentText === '') {
                alert('Comment cannot be empty!'); // Alert if the comment is empty
                return;
            }

            $.ajax({
                url: `/posts/${postId}/comments/${commentId}`, // Adjust the URL based on your routes
                type: 'PUT',
                data: {
                    body: newCommentText,
                    _token: '{{ csrf_token() }}' // Include CSRF token
                },
                success: function(response) {
                    const commentBody = document.querySelector(`.comment-body.comment-body-${commentId}`);
                    // Update the comment body text
                    commentBody.textContent = newCommentText;

                    // Get the timestamp element for updating
                    const updatedAtElement = commentBody
                        .nextElementSibling; // Assuming the timestamp is the next sibling

                    // Update the updated_at text
                    updatedAtElement.textContent = `${response.updated_at} (edited)`; // Append '(edited)'

                    // Show the updated comment text and hide the edit input
                    commentBody.style.display = 'block';
                    editCommentDiv.style.display = 'none';
                },
                error: function(xhr) {
                    alert('Failed to update comment. Please try again.');
                }
            });
        }


        // Function to cancel the edit
        function cancelEdit(commentId) {
            const editCommentDiv = document.getElementById(`edit-comment-${commentId}`);
            const commentBody = document.querySelector(`.comment-body.comment-body-${commentId}`);

            // Hide the edit input and show the original comment text
            editCommentDiv.style.display = 'none';
            commentBody.style.display = 'block';
        }

        // Delete Comment
        function deleteComment(commentId, postId) {
            if (confirm("Are you sure you want to delete this comment?")) {
                $.ajax({
                    url: `/posts/${postId}/comments/${commentId}`, // Adjust the URL based on your routes
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}' // Include CSRF token
                    },
                    success: function(response) {
                        // Remove the comment from the DOM
                        const commentBody = document.querySelector(`.comment-body-${commentId}`).parentElement;
                        commentBody.remove();

                        // Update the comment count dynamically
                        const commentCountElement = document.getElementById(`comment-count-${postId}`);
                        const currentCount = parseInt(commentCountElement.textContent) || 0;
                        const newCount = currentCount - 1;

                        if (newCount === 0) {
                            commentCountElement.textContent = ''; // No comments
                        } else if (newCount === 1) {
                            commentCountElement.textContent = '1 Comment'; // Single comment
                        } else {
                            commentCountElement.textContent = `${newCount} Comments`; // Multiple comments
                        }
                    },
                    error: function(xhr) {
                        alert('Failed to delete comment. Please try again.');
                    }
                });
            }
        }

        // Function to toggle the comment dropdown
        function toggleCommentDropdown(event, commentId) {
            event.stopPropagation(); // Prevent event from bubbling up
            // Hide other dropdowns
            $('.comment-dropdown').not(`#comment-dropdown-${commentId}`).addClass('hidden');

            // Toggle the current dropdown
            $(`#comment-dropdown-${commentId}`).toggleClass('hidden');
        }

        // Hide dropdowns when clicking outside
        $(document).on('click', function() {
            $('.comment-dropdown').addClass('hidden');
        });


        // Function to hide all open dropdowns
        function hideAllDropdowns() {
            const dropdowns = document.querySelectorAll('[id^="comment-dropdown-"]');
            dropdowns.forEach(dropdown => {
                dropdown.classList.add('hidden'); // Hide the dropdowns
            });
        }

        // Event listener for clicks outside of dropdowns
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id^="comment-dropdown-"]');
            dropdowns.forEach(dropdown => {
                // Check if the click is outside the dropdown and its toggle button
                if (!dropdown.contains(event.target) && !dropdown.previousElementSibling.contains(event
                        .target)) {
                    dropdown.classList.add('hidden'); // Hide the dropdown
                }
            });
        });
    </script>
</x-app-layout>
