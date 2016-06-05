@extends('campaign.unsubscribe.layout')

@section('title', 'Unsubscribe')

@section('content')
    <form id="subscribeform" method=post name="subscribeform" action="{!! url('/unsubscribe/save/') !!}" style="line-height:26px;padding:15px;text-align:center;">
        {!! csrf_field() !!}
        <div class="unsubscribeContent">
            <div class="mailSubscribe">
                <span style="font-size:20px; font-weight: bold;">{{$email}}</span>
                <br />
                <span>is subscribed to our mailing list(s).</span>
            </div>
            <div class="subsInnerContent">
                <strong class="msgTitle">Unsubscribe from our mailing list</strong> <br />
                To help us improve our services, we would be grateful if you could tell us why:
                <br />
                <div class="selectReason" style="margin-top:35px;min-width:250px;">
                    <select class="inputType" id="selectUnsubscribeReason" name="unsubscribe_reason">
                        <option value="">Please select reason</option>
                        @foreach($reasons as $reason)
                            <option value="{{$reason}}">{{$reason}}</option>
                        @endforeach
                    </select>
                    <br />
                    <textarea  style='display:none;margin-top:22px;' id="unsubscribereason"  name="unsubscribereason_others" class="hideTextarea inputType" rows="4" placeholder="Provide your reason here..."></textarea>
                    @if (count($errors) > 0)
                        @foreach ($errors->all() as $error)
                            <span  class="errorMsg">{{ $error }}</span>
                            <br/>
                        @endforeach
                    @endif
                </div>
            </div>
            <input type="hidden" name="email" value="{{$email}}" />
            <span  style='display:none' class="errorMsg">Please Enter Reason</span>
            <br /><br />
            <div style="text-align:center">
                <input class="primaryBtn" type=submit name="unsubscribe" value="Unsubscribe">
            </div>
        </div>
    </form>

<script type='text/javascript'>
    $('document').ready(function(){
        $('#selectUnsubscribeReason').change(function() {
            if ( $(this).val() == "Others") {
                $('#unsubscribereason').val("").fadeIn();
            }else{
                $('#unsubscribereason').val("").fadeOut();
            }
        });

        $('#subscribeform').on('submit', function(){
            var reason =$.trim( $("#selectUnsubscribeReason").val());
            var reason_others =$.trim( $("#unsubscribereason").val());
            if ( reason =="" ){
                $(".errorMsg").css('display','block');
                return false;
            }else if(reason_others =="" && reason == "others") {
                $(".errorMsg").css('display','block');
                return false;
            }
        });
    });
</script>
@endsection
