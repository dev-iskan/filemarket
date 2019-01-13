<script>
    window.onload = function () {
        var token = document.head.querySelector('meta[name="csrf-token"]');
        var drop = new Dropzone('#file', {
            addRemoveLinks: true,
            url : '{{route('upload.store', $file)}}',
            headers: {
                'X-CSRF-TOKEN': token.content
            }
        });

        @foreach($file->uploads as $upload)
            drop.emit('addedfile', {
                id: '{{$upload->id}}',
                name: '{{$upload->filename}}',
                size: '{{$upload->size}}'
            })
        @endforeach

        drop.on('success', function (file, response) {
            file.id = response.id
        });
        drop.on('removedfile', function (file) {
            axios.delete('/{{$file->identifier}}/upload/'+file.id)
                .catch(function (error) {
                    drop.emit('addedfile', {
                        id: file.id,
                        name: file.name,
                        size: file.size
                    })
                })
        })
    }
</script>