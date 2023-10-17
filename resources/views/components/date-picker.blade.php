<input
    x-data
    x-ref="input"
    x-init="new Pikaday({ field: $refs.input })"
    type="text"
    {{ $attributes }}
>

<input type="text" id="datepicker">

<script type="module">
    new Pikaday({ field: document.getElementById('datepicker') })
</script>

