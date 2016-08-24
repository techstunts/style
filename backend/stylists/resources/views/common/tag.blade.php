@include('common.iconlibrary')
<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#tags_{{$product->id}}">Tags</button>
<div id="tags_{{$product->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-header">
            <h4 class="modal-title">Tags for {{$product->name}}</h4>
        </div>

        <div class="modal-content extra text">
            <div class="modal-body" data-product_id="{{$product->id}}">
                @if(count($product->product_tags) > 0)
                    @foreach($product->product_tags as $product_tag)
                        @if($product_tag->tag)
                            <span>{{$product_tag->tag->name}}
                                <span class="cross_mark"><a href="#"><i
                                                class="material-icons" style="font-size: 8px;">close</i></a></span>
                            </span>
                        @endif
                    @endforeach
                @else
                    <span>No tags for this product</span>
                @endif
            </div>
            <div>
                {!! csrf_field() !!}
                <input name="product_id" type="hidden" value="{{$product->id}}">
                <input class="input-tag" >
            </div>
        </div>
    </div>
</div>