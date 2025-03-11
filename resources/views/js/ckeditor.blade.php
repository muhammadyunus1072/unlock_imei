@push('js')
    <script src="{{asset('/vendor/ckeditor4/ckeditor.js')}}"></script>
    <script>
        CKEDITOR.config.height = 400;
        let processedInstances = new Set();

        CKEDITOR.on('instanceReady', function (evt) {
            const instanceName = evt.editor.name;

            if (!processedInstances.has(instanceName)) {
                evt.editor.on('blur', function () {
                    @this.set(instanceName, evt.editor.getData());
                });
                processedInstances.add(instanceName);
            }
        });
    </script>
@endpush