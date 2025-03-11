@push('js')
    <script src="{{ asset('vendor/imask/imask.min.js') }}"></script>
    <script>
        var imaskMap = [];
        var imaskOption = {
        };

        $(() => {
            init_imask();

            Livewire.hook('commit', ({
                component,
                commit,
                respond,
                succeed,
                fail
            }) => {
                // Equivalent of 'message.sent'

                succeed(({
                    snapshot,
                    effect
                }) => {
                    // Equivalent of 'message.received'

                    queueMicrotask(() => {
                        // Equivalent of 'message.processed'

                        setTimeout(function() {
                            reinit_imask(false);
                        }, 50);
                    })
                })

                fail(() => {
                    // Equivalent of 'message.failed'
                })
            })
        });

        function init_imask() {
            $('.currency').each(function(index, element) {
                const maxAttributeValue = $(element).attr('max');
                const minAttributeValue = $(element).attr('min');
                const maxOption = maxAttributeValue ? parseFloat(maxAttributeValue) : null;
                const minOption = minAttributeValue ? parseFloat(minAttributeValue) : null;

                imaskOption = {};
                imaskOption.mask = Number;

                imaskOption.thousandsSeparator = '.';
                if($(element).attr('imask-no-decimal') == undefined)
                {
                    imaskOption.radix = ',';
                }
                imaskOption.scale = 10;
                if (maxOption !== null) {
                    imaskOption.max = maxOption;
                }
                if (minOption !== null) {
                    imaskOption.min = minOption;
                } else {
                    delete imaskOption.min;
                }
                if ($(element).attr('signed') !== undefined) {
                    imaskOption.signed = true;
                    // imaskOption.thousandsSeparator = '.';
                    // // delete imaskOption.radix;
                    // imaskOption.radix = '.';

                }
                imaskMap.push({
                    element: element,
                    imask: IMask(element, imaskOption),
                })
            });
            $('.phone').each(function(index, element) {

                // imaskOption.mask = '[8]00-0000-0000';
                imaskOption.mask = '000-0000-0000';
                imaskMap.push({
                    element: element,
                    imask: IMask(element, imaskOption),
                });


                $(element).on('blur', (event) => {
                    const value = event.target.value;
                    const modelName = $(element).attr('model-name');
                    @this.set(modelName, value)
                });
            });
        }

        function destroy_imask(save_state = true) {
            Object.values(imaskMap).forEach(val => {
                if (save_state) {
                    $(val.element).val(val.imask.unmaskedValue);
                }
                val.imask.destroy();
            });
            imaskMap = [];
        }

        function reinit_imask(save_state = true) {
            destroy_imask(save_state);
            init_imask()
        }
    </script>
@endpush
