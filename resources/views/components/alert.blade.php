<div id="alert-{{ $type }}" class="bg-{{ $type }}-50 border-s-4 w-1/4 z-10 fixed top-16 right-0 mt-4 border-{{ $type }}-500 p-4 dark:bg-{{ $type }}-800/30 transition-opacity duration-500 ease-out"
    role="alert" tabindex="-1" aria-labelledby="hs-bordered-{{ $type }}-style-label">
    <div class="flex">
        <div class="shrink-0">
            <span class="inline-flex justify-center items-center size-8 rounded-full border-4 bg-{{ $type }}-200 text-{{ $type }}-800 dark:bg-{{ $type }}-800 dark:text-{{ $type }}-400">
                @if ($type === 'red')
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                @elseif ($type === 'green')
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                @endif
            </span>
        </div>
        <div class="ms-3">
            <h3 id="hs-bordered-{{ $type }}-style-label" class="text-gray-800 font-semibold dark:text-white">
                {{ $title }}
            </h3>
            <p class="text-sm text-gray-700 dark:text-neutral-400">
                {{ $message }}
            </p>
        </div>
    </div>
</div>

<script>
    // Hide the alert after 5 seconds (5000 milliseconds)
    setTimeout(function() {
        const alertElement = document.getElementById('alert-{{ $type }}');
        if (alertElement) {
            alertElement.classList.add('opacity-0'); // Transition opacity
            setTimeout(() => alertElement.remove(), 500); // Remove element after transition
        }
    }, 5000); // Time before alert disappears (5 seconds)
</script>
