<!-- resources/views/components/alert.blade.php -->

<div class="alert alert-{{$type}} alert-dismissible fade show border-0 mb-4" role="alert">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    {{ $message }}
</div>
