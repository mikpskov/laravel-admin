<x-admin.select
    :name="$name"
    :options="$items"
    :selected="$selected"
/>

@pushOnce('scripts')
<script>
(() => {
    document
        .getElementById('{{ $name }}')
        .addEventListener('change', function() {
            document.cookie = `{{ $name }}=${this.value}`;
            location.reload();
        });
})()
</script>
@endPushOnce
