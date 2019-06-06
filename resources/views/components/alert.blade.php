<div class="container">
    <div class="alert alert-{{ $type }}">
        {{-- <div class="alert-title">{{ $title }}</div>
        {{ $slot }} --}}
        <div class="alert-title"><strong>{{ $title }}</strong>: {{ $slot }}</div>
    </div>
</div>