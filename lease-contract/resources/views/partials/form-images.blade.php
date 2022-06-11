<div class="object-files-wrapper">
    <a href="#" class="add-new-object-file js-btn-trigger-add-file"
       data-name="files[]">{{ trans('plugins/lease-contract::form.button_add_image') }}
    </a>
    @php $object_files = old('files', !empty($object) ? $object->files : null); @endphp
    <div class="files-wrapper">
        <div data-name="files[]" class="text-center cursor-pointer js-btn-trigger-add-file default-placeholder-object-file @if (is_array($object_files) && !empty($object_files)) hidden @endif">
            <img src="{{ url('vendor/core/images/placeholder.png') }}" alt="{{ __('File') }}" width="120">
            <br>
            <p style="color:#c3cfd8">{{ __('Using button') }} <strong>{{ __('Chose Image') }}</strong> {{ __('to add more image') }}.</p>
        </div>
        <ul class="list-unstyled list-gallery-media-files clearfix @if (!is_array($object_files) || empty($object_files)) hidden @endif" style="padding-top: 20px;">
            @if (is_array($object_files) && !empty($object_files))
                @foreach($object_files as $file)
                    <li class="object-file-item-handler">
                        @include('plugins/lease-contract::partials.components.image', [
                            'name' => 'files[]',
                            'value' => $file
                        ])
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>

<script id="object_select_file_template" type="text/x-custom-template">
    @include('plugins/lease-contract::partials.components.image', [
        'name' => '__name__',
        'value' => null
    ])
</script>
