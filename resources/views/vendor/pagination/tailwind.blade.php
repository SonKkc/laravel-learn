
@if ($paginator->hasPages())
<nav class="flex items-center justify-between">
    <div class="flex w-0 flex-1">
        @if ($paginator->onFirstPage())
            <span class="inline-flex h-12 items-center justify-center rounded-xl bg-gray-50 px-4 text-sm font-medium text-gray-400 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="mr-2.5 h-5 w-5 text-gray-400">
                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd"></path>
                </svg>
                Previous
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex h-12 items-center justify-center rounded-xl bg-gray-50 px-4 text-sm font-medium text-gray-500 transition duration-300 ease-in-out hover:bg-gray-100 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="mr-2.5 h-5 w-5 text-gray-400">
                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd"></path>
                </svg>
                Previous
            </a>
        @endif
    </div>

    <div class="hidden space-x-2.5 md:flex">
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="inline-flex h-12 w-12 items-center justify-center text-base font-medium text-gray-500">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-red-600 text-sm font-medium text-white" aria-current="page" href="#">{{ $page }}</a>
                    @else
                        <a class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gray-50 text-sm font-medium text-gray-500 transition duration-300 ease-in-out hover:bg-gray-100 hover:text-gray-700" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach
    </div>

    <div class="flex w-0 flex-1 justify-end">
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex h-12 items-center justify-center rounded-xl bg-gray-50 px-4 text-sm font-medium text-gray-500 transition duration-300 ease-in-out hover:bg-gray-100 hover:text-gray-700">
                Next
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="ml-2.5 h-5 w-5 text-gray-400">
                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"></path>
                </svg>
            </a>
        @else
            <span class="inline-flex h-12 items-center justify-center rounded-xl bg-gray-50 px-4 text-sm font-medium text-gray-400 cursor-not-allowed">
                Next
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="ml-2.5 h-5 w-5 text-gray-400">
                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"></path>
                </svg>
            </span>
        @endif
    </div>
</nav>
@endif
