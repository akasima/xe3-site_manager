<div class="panel">
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
            </h4>
            <table class="table">
                <thead>
                <tr>
                    <th>에러 로그12</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($dayLog as $value)
                    <tr>
                        <td>{{$value['error']}}</td>
                        <td><button class="btn_show_all">stacktrace</button></td>
                    </tr>
                    <tr class="error-log error-log-row" style="display:none;">
                        <td colspan="2" style="padding-left: 40px; background-color:#ccc;">
                            <p> -> </p>
                            @foreach($value['stacktrace'] as $stacktrace)
                                <p>{{$stacktrace}}</p>
                            @endforeach
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
        $('.btn_show_all').bind('click', function () {
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
