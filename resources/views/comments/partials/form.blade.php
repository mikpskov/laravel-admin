<div class="shadow-md mb-10 p-4 bg-white">
    <form method="POST" action="{{ route('posts.comments.store', $item) }}">
        @csrf

        <main>
            <x-form.textarea name="body" rows="6"/>
        </main>

        <footer class="text-right mt-2">
            <x-form.button>
                {{ __('Send') }}
            </x-form.button>
        </footer>
    </form>
</div>
