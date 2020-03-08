@section('page_title')
    <h2>Site Manager</h2>
@stop
<style>
    .site-manager .error {color: red;}
    .server-env {}
    .server-env error{}
</style>
<div class="container-fluid container-fluid--part site-manager">

    <div class="row server-env">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-heading">
                        <h3>상태</h3>
                        <p class="help-block">서버 상태를 확인하세요.</p>
                        <a href="{{route('settings.site_manager.phpinfo')}}" target="_blank">php 설정 정보 확인 </a>
                    </div>
                    <div class="panel-body">
                        <ul>
                        @foreach($serverEnv as $index => $val)
                            <li>
                                @if($val['status'] == true)
                                    <label>{{$val['text']}}</label>
                                    <span>OK</span>
                                @else
                                    <label class="error">{{$val['text']}}</label>
                                    <span class="error">Error</span>
                                @endif
                                <small> : {!! $val['message'] !!}</small>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row cache-clear">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-heading">
                        <h3>Cache Clear</h3>
                        <p class="help-block">캐시 파일을 삭제합니다.</p>
                    </div>
                    <div class="panel-body">
                        <a href="{{route('settings.site_manager.cacheClear')}}" class="xe-btn xe-btn-danger">캐시 삭제</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row log-clear">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-heading">
                        <h3>Log Clear</h3>
                        <p class="help-block">로그 파일을 삭제합니다.</p>
                    </div>
                    <div class="panel-body">
                        <p><a href="{{route('settings.site_manager.getLogFile', [])}}">에러 관리 </a></p>
                        <p><a href="{{route('settings.site_manager.logClear')}}" class="xe-btn xe-btn-danger">로그 삭제</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row log-clear">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-heading">
                        <h3>Easy Solution</h3>
                        <p class="help-block">웹사이트 주요 문제를 손쉽게 해결합니다.</p>
                    </div>
                    <div class="panel-body">
                        <p><a href="{{route('settings.site_manager.solution', ['type' => 'unlimited_update_loading'])}}" class="xe-btn xe-btn-success">플러그인 무한 업데이트 해결</a></p>
                        <p><a href="{{route('settings.site_manager.solution', ['type' => 'fix_composer_home_path'])}}" class="xe-btn xe-btn-success">사이트 Composer 홈 디렉토리</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>