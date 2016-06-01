<div style="margin: 5px;">
    <link href="{!! asset('/css/classic.css') !!} " rel="stylesheet">
    <link href="{!! asset('/css/classic.date.css') !!} " rel="stylesheet">
    <link href="{!! asset('/css/classic.time.css') !!} " rel="stylesheet">
    <form method="POST" id="campaign-publish-form" action="{!! url('/campaign/publish/'. $campaign->id ) !!}">
        {!! csrf_field() !!}
        <table style="border: 1px solid #ccc; width: 500px;">
            <tr class="row">
                <td class="title" colspan="2">
                    Publish Campaign
                </td>
            </tr>
            <tr class="row">
                <td class="title">
                    <input type="text" id="publish_date" placeholder="Publish Date" />
                    <input type="text" id="publish_time" placeholder="Publish Time" />
                    <input type="hidden" id="publish_dt" name="publish_dt"  />
                </td>
                <td class="title">
                    <input type="submit" class="btn btn-primary btn-lg" style="background-color: #ff0000" value="PUBLISH">
                </td>
            </tr>

            @if (count($errors) > 0)
                <tr class="row">
                    <td class="title" colspan="2">
                        @foreach ($errors->all() as $error)
                            <span class="errorMsg">{{ $error }}</span>
                            <br/>
                        @endforeach
                    </td>
                </tr>
            @endif

        </table>
    </form>

    <script src="{!! asset('/js/picker.js') !!}"></script>
    <script src="{!! asset('/js/picker.date.js') !!}"></script>
    <script src="{!! asset('/js/picker.time.js') !!}"></script>

    <script type="text/javascript">
        $(function() {
            $('#publish_date').pickadate({
                format: 'mmm-dd-yyyy'
            });
            $('#publish_time').pickatime({
                format: 'h:i A'
            });

            $("#campaign-publish-form").submit(function(){
                if(!campaignFormValidation()) return false;
                $("#publish_dt").val(getPublishDateStr());
                return confirm("After publish, you won't able edit this campaign." +
                    "\nAre you sure to publish on "+getPublishDateStr()+"?");
            });

        });

        function campaignFormValidation(){
            if($.trim($("#publish_date").val()).length <=0){
                alert("Publish date must not be empty.");
                return false;
            }

            if($.trim($("#publish_time").val()).length <=0){
                alert("Publish time must not be empty.");
                return false;
            }
//
//            var publishDate = getPublishDate();
//            console.log(publishDate);
//            if(!publishDate) {
//                alert("Invalid publish date.")
//                return false;
//            }
//
//            var curDate = new Date();
//            if(curDate >= publishDate){
//                alert("Publish datetime should future datetime.")
//                return false;
//            }

            return true;
        }

        function getPublishDate(){
            var dateStr =  getPublishDateStr();
            try{
                return Date.parse(dateStr);
            }catch (e){
                return 0;
            }
        }

        function getPublishDateStr(){
            return  $('#publish_date').val()   + " " + $('#publish_time').val() ;
        }
    </script>

</div>
