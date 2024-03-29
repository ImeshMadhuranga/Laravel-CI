<div class="modal fade" id="editBlogModal" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">{{ __('messages.vcard.edit_blog') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {!! Form::open(['id'=>'editBlogForm', 'files' => 'true']) !!}
                <div class="row">
                    <div class="col-sm-12 mb-5">
                        {{ Form::hidden('blog_id', null,['id' => 'blogId']) }}
                        {{ Form::label('title',__('messages.front_cms.title').(':'), ['class' => 'form-label required fs-6 fw-bolder text-gray-700 mb-3']) }}
                        {{ Form::text('title', null, ['class' => 'form-control', 'id' => 'editTitle', 'required', 'placeholder' => __('messages.form.blog'), 'maxlength'=>'100']) }}
                    </div>
                    <div class="col-sm-12 mb-5">
                        {{ Form::label('editBlogDescription', __('messages.common.description').':', ['class' => 'form-label required fs-6 fw-bolder text-gray-700 mb-3']) }}
                        {{ Form::textarea('description', null, ['class' => 'form-control', 'id' => 'editBlogDescription', 'required']) }}
                    </div>
                    <div class="col-sm-12 mb-5">

                        <div class="mb-3" io-image-input="true">
                            <label for="blogImgId"
                                   class="form-label">{{ __('messages.vcard.blog_icon').':' }}</label>
                            <div class="d-block">
                                <div class="image-picker">
                                    <div class="image previewImage" id="editBlogPreview"
                                         style="background-image: url('{{ asset('assets/images/default_service.png') }}')"></div>
                                    <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                                          data-placement="top" data-bs-original-title="{{__('messages.tooltip.image')}}">
                                        <label>
                                            <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                                            <input type="file" id="editBlogIcon" name="blog_icon"
                                                   class="image-upload file-validation d-none" accept="image/*"/> </label>
                                    </span>
                                </div>
                                <div class="form-text">{{__('messages.allowed_file_types')}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        {{ Form::button(__('messages.common.save'), ['type'=>'submit','class' => 'btn btn-primary me-3','id'=>'serviceUpdate']) }}
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('messages.common.discard') }}</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
