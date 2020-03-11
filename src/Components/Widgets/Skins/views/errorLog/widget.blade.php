<style>
    .site-manager-error-log tbody .info-row span:nth-child(1) {background-color:#00A6C7; padding : 4px;}
    .site-manager-error-log tbody .info-row span:nth-child(3) {background-color:#a5673f; padding : 4px;}
</style>
<div class="panel site-manager-error-log">
    <div class="panel-heading">
        <h3>Error Log</h3>
    </div>
    <div class="panel-body">
        @if(sizeof($logFiles) == 0)
            <h4>에러 로그가 없습니다.</h4>
        @endif
        @foreach($logs as $index => $dayLog)
            <h4>
                {{ $logFiles[$index]['path'] }}
                <small>
                    파일 크기 :  {{ bytes($logFiles[$index]['size']) }}
                </small>
                <a href="{{route('settings.site_manager.getLogFile', ['file' => $logFiles[$index]['filename']])}}" class="xe-btn" target="_blank">로그파일</a>
            </h4>
            <table class="table">
                <thead>
                <tr>
                    <th>에러 로그</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($dayLog as $value)
                    <tr class="info-row">
                        <td>
                            <span class="badge">{{$value['time']}}</span>
                            <span>{{$value['env']}}</span>
                            <span class="badge log-level-{{$value['level']}}">{{$value['level']}}</span>
                            <span>{{$value['info']}}</span>
                        </td>
                        <td><button class="btn_show_trace">stacktrace</button></td>
                    </tr>
                    <tr class="error-log error-log-row" style="display:none;">
                        <td colspan="2" style="padding-left: 40px; background-color:#ccc;">
                            <div>{!! nl2br($value['trace']) !!}</div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
</div>

<script>
    $(function() {
        $('.site-manager-error-log .btn_show_trace').bind('click', function () {
            var $target = $(this).closest('tr').next('.error-log-row');
            if ($target.is(':visible')) {
                console.log(1);
                $('.error-log').hide();
            } else {
                console.log(2);
                $('.error-log').hide();
                $target.slideDown(3000);
            }
        });
    });
</script>
