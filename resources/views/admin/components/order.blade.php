<x-admin.select
    name="order"
    :options="$orders"
    :selected="$selected"
/>

@pushOnce('scripts')
<script>
(() => {
    document
        .querySelector('select[name="order"]')
        .addEventListener('change', function() {
            let parts = this.value.split('_')
            const direction = parts.pop()
            const column = parts.join('_')

            // const queryParams = new URLSearchParams(window.location.search)
            // queryParams.set(`orders[${column}]`, direction)
            // history.replaceState(null, null, `?${queryParams.toString()}`)

            window.location.replace(
                `?orders[${column}]=${direction}`
            )
        })
})()
</script>
@endPushOnce
