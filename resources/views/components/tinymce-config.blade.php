@props(['textarea', 'height' => 400])

<script src="{{ 'https://cdn.tiny.cloud/1/' . config('services.tinymce.token') . '/tinymce/7/tinymce.min.js' }}" referrerpolicy="origin"></script>

<script type="text/javascript">
    $(document).ready(function() {
        tinymce.init({
            selector: '{{ $textarea }}',
            height: "{{ $height }}",
            placeholder: 'Informe a resposta automática para a resolução do problema',
            menubar: false,
            images_upload_url: "{{ route('tinymce.add.images') }}",
            images_upload_credentials: true,
            images_upload_headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            resize_image: {
                max_width: 50,
            },
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        });
    });
</script>
