@include('common.iconlibrary')
<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#tags_{{$$entity->id}}">Tags</button>
<div id="tags_{{$$entity->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-header">
            <h4 class="modal-title">Tags for {{$$entity->name}}</h4>
        </div>

        <div class="modal-content extra text">
            <div class="modal-body" data-product_id="{{$$entity->id}}">
                @if(count($$entity->tags) > 0)
                    @foreach($$entity->tags as $product_tag)
                        @if($product_tag->tag)
                            <span>{{$product_tag->tag->name}}
                                <span class="cross_mark"><a href="#"><i
                                                class="material-icons" style="font-size: 8px;">close</i></a></span>
                            </span>
                        @endif
                    @endforeach
                @else
                    <span>No tags for this {{$entity}}</span>
                @endif
            </div>
            <div>
                {!! csrf_field() !!}
                <input name="{{$entity.'_id'}}" type="hidden" value="{{$$entity->id}}">
                <input class="input-tag" placeholder="Tag here">
            </div>
        </div>
    </div>
</div>