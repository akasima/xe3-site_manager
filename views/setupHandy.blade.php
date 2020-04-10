@section('page_title')
    <h2>Site Manager Setup Handy</h2>
@stop
<style>
    .site-manager .error {color: red;}
    .server-env {}
    .server-env error{}
    /*.config-info {display:none;}*/
</style>
<div class="container-fluid container-fluid--part site-manager">

    <div class="row server-env">
        <div class="col-sm-12">
            <div class="panel-group">
                <form method="post" action="{{ route('settings.site_manager.updateHandy') }}">
                    {{ csrf_field() }}
                    <div class="panel">
                        <div class="panel-heading">
                            <h3>주요 설정</h3>
                            <p class="help-block">설정 변경하면 웹사이트에 오류가 발생할 수 있습니다. FTP 접속, 백업을 항상 신경써주세요.</p>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6 form-col-logoType">
                                    <div class="form-group">
                                        <label class="">SSL 항상 사용</label>
                                        <select class="form-control" name="xe/ssl/always">
                                            <option value="false" >사용 안함</option>
                                            <option value="true" @if($config['xe']['ssl']['always'] == true)selected="selected"@endif>사용</option>
                                        </select>
                                        <p class="help-block">사용 설정하면 사용자 페이지의 모든 http 요청을 https로 보냅니다.(관리자 예외)</p>
                                    </div>
                                </div>
                                <div class="col-md-6 form-col-logoType">
                                    <div class="form-group">
                                        <label class="">콘솔 allow url fopen</label>
                                        <select class="form-control" name="xe/console_allow_url_fopen">
                                            <option value="false" >사용 안함</option>
                                            <option value="true" @if($config['xe']['console_allow_url_fopen'] == true)selected="selected"@endif>사용</option>
                                        </select>
                                        <p class="help-block">업데이트할 때 allow url fopen 지원 유무 설정 (사용 권장, 사용 안함 설정하는 경우 웹서버 타임아웃 확인 필요, 사용함 설정후 업데이트 장애 발생하는 경우 사용안함 설정)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-col-logoType">
                                    <div class="form-group">
                                        <label class="">관리자 인증 시간</label>
                                        <input class="form-control" name="auth/admin/expire" value="{{$config['auth']['admin']['expire']}}">
                                        <p class="help-block">관리자 사이트에서 2중 잠금 기능 사용에 대한 설정입니다. 관리자 인증을 사용하지 않으려면 '0'을 입력하세요.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-col-logoType">
                                    <div class="form-group">
                                        <label class="">웹사이트 디버그</label>
                                        <select class="form-control" name="app/debug">
                                            <option value="false" >사용 안함</option>
                                            <option value="true" @if($config['app']['debug'] == true)selected="selected"@endif>사용</option>
                                        </select>
                                        <p class="help-block">사용 설정하면 웹사이트 에러가 웹브라우저에 출력됩니다. (정상 서비스 환경에서는 '사용 안함' 설정 권장)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-col-logoType">
                                    <div class="form-group">
                                        <label class="">이메일 드라이버</label>
                                        <input class="form-control" name="mail/driver" value="{{$config['mail']['driver']}}">
                                        <p class="help-block">메일 발송 방법을 설정합니다.(smtp, sendmail, log) 상세한 설정은 config/mail.php 참고해서 설정</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="pull-right">
                                <button type="submit" class="btn btn-primary btn-lg">{{xe_trans('xe::save')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row server-env">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-heading">
                        <h3>모든 설정</h3>
                        <p class="help-block">config 디렉토리에 있는 모든 설정을 볼 수 있습니다.</p>
                    </div>
                    <div class="panel-body">
                        {{--<p><button class="xe-btn xe-btn-defualt btn-show-config">펼치기</button></p>--}}
                        <div class="config-info">
                            @foreach ($config as $key1=>$depth1)
                                <strong>관련 파일 : config/{{$key1}} {{$key1}}.php or config/{{$appEnv}}/{{$key1}}.php</strong>
                                {!! allConfigHtml($key1, $depth1) !!}
                                <hr>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('.btn-show-config').on('click', function () {
            $('.config-info').toggle();
        });
    });
</script>
<?php
function str_masking_security($str, $offset = 2)
{
    $len = strlen($str);
    $start = $offset;

    /*
     * 대상 문자가 너무 짧을 경우 마스킹을 하나 더 길게
     * ex ) abcd => ab**
     * ex ) abc => a**
     */
    if ($len <= $offset + 1) {
        $start = $offset - 1;
    }

    $str = substr($str, 0, $start);
    for ($i = $start; $i < $len; $i++) {
        $str .= '*';
    }

    return $str;
}

        function allConfigHtml($key, $depth, $i = 1) {
            if (function_exists('str_masking') === false) {
                function str_masking($str, $offset = 2)
                {
                    $len = strlen($str);
                    $start = $offset;

                    /*
                     * 대상 문자가 너무 짧을 경우 마스킹을 하나 더 길게
                     * ex ) abcd => ab**
                     * ex ) abc => a**
                     */
                    if ($len <= $offset + 1) {
                        $start = $offset - 1;
                    }

                    $str = substr($str, 0, $start);
                    for ($i = $start; $i < $len; $i++) {
                        $str .= '*';
                    }

                    return $str;
                }
            }

            $html = '';
            if ($i == 1) {
                $html .= "<br/><strong>{$key}</strong>";
            }
            if ($i > 1) {
                $html .= "<li>{$key}</li>";
            }
            $html .= "<ul class='config-depth".$i."'>";
            foreach ($depth as $key2=>$depth2) {
                if (is_array($depth2) == false && is_bool($depth2) == true) {
                    $val = $depth2 ? ' true ' : ' false ';
                    $html .= "<li><span>{$key2}</span> => <span>{$val}</span></li>";
                } elseif (is_array($depth2) == false && is_object($depth2) == false) {
                    // password 보안
                    $class = '';
                    if ((string)$key2 == "password") {
                        $class = 'password';
                        $depth2 = str_masking_security($depth2, 2);

                    }
                    $html .= "<li><span>{$key2}</span> => <span class='{$class}'>{$depth2}</span></li>";
                } elseif (is_array($depth2) == false && $depth2 == null) {
                    $html .= "<li><span>{$key2}</span> => <span> IS NULL</span></li>";
                } elseif (is_array($depth2) == false && is_object($depth2)) {
                    $html .= "<li><span>{$key2}</span> => " . gettype($depth2) . " </li>";
                } else {
                    $html .= allConfigHtml($key2, $depth2, ++$i);
                }
            }
            $html .= "</ul>";

            return $html;
        }

