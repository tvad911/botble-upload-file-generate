{!! Form::hidden('lease_contract_file', $value ? json_encode($value) : null, ['id' => 'lease_contract_file-data', 'class' => 'form-control']) !!}
<input id="contract_files" name="contract_files[]" type="file" multiple accept="{!! config('plugins.lease-contract.file.accept') !!}" style="display: none;">
<div style="position: relative;">
    <p class="count-contract-file"></p>
    <div class="list-files-contract">
        <div class="row" id="list-files-items">
            @if (!empty($value))
                @foreach ($value as $key => $item)
                    <div class="col-md-2 col-sm-3 col-4 file-contract-item" data-id="{{ Arr::get($item, 'id') }}" data-img="{{ Arr::get($item, 'img') }}" data-options="{{ Arr::get($item, 'options') }}" data-id="{{ Arr::get($item, 'id') }}" data-name="{{ Arr::get($item, 'name') }}" data-folder="{{ Arr::get($item, 'folder') }}" data-mimetype="{{ Arr::get($item, 'mime_type') }}" data-url="{{ Arr::get($item, 'url') }}">
                        <div class="contract_file_wrapper">
                            @php
                                $mime_type = Arr::get($item, 'mime_type');
                                $url = get_lease_contract_thumbnail_image_file($mime_type);
                            @endphp
                            @if(empty($url))
                                <img src="{{ RvMedia::getImageUrl(Arr::get($item, 'folder') . '/' . Arr::get($item, 'url'), 'thumb') }}" alt="{{ trans('plugins/lease-contract::lease-contract-file.name') }}">
                            @else
                                <img src="{{ config('app.url') . '/vendor/core/plugins/' . $url }}" alt="{{ Arr::get($item, 'url') }}">
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="form-group">
        <a href="#" class="btn_select_file">{{ trans('plugins/lease-contract::lease-contract-file.select_file') }}</a>&nbsp;
        <a href="#" class="text-danger reset-file @if (empty($value)) hidden @endif">{{ trans('plugins/lease-contract::lease-contract-file.reset') }}</a>
    </div>
</div>

<div id="edit-file-item" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('plugins/lease-contract::lease-contract-file.update_file_description') }}</strong></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body with-padding">
                <p><input type="text" class="form-control" id="file-item-description" placeholder="{{ trans('plugins/lease-contract::lease-contract-file.update_file_description_placeholder') }}"></p>
            </div>

            <div class="modal-footer">
                <button class="float-left btn btn-info" id="download-file-item" data-link="" data-base="{{ config('app.url') }}">{{ trans('plugins/lease-contract::lease-contract-file.download_file') }}</button>
                <button class="float-left btn btn-danger" id="delete-file-item" href="#">{{ trans('plugins/lease-contract::lease-contract-file.delete_file') }}</button>
                <button class="float-right btn btn-secondary" data-dismiss="modal">{{ trans('core/base::forms.cancel') }}</button>
                <button class="float-right btn btn-primary" id="update-file-item">{{ trans('core/base::forms.update') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- end Modal -->
