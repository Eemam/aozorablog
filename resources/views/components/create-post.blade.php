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