<div class="image col-md-6 border-around">
    <form id="UploadImageForm" action="{{env('API_ORIGIN')}}/file/upload" enctype="multipart/form-data" style="display: initial;">
        {!! csrf_field() !!}
        <div class="row">
            <div class="col-md-8">
                <input name="image" type="file" class="file-loading">
            </div>
        </div>
        <input name="entity_id" type="hidden" value="{{!empty($product) ? $product->id : ''}}">
        <input name="url" type="hidden" value="{{env('API_ORIGIN')}}/file/upload">
        <input name="entity_type_id" type="hidden" value="{{$entity_type_id}}">
        @include('common.image_type.select', ['image_types' => $image_types])
        <br>
        <div class="row">
            <div class="col-md-8">
                <input type="submit" style="display: block;" class="btn btn-primary btn-lg" value="Upload Image">
            </div>
        </div>
    </form>
    <br>
</div>