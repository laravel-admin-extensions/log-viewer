<script data-exec-on-popstate>

    $(function () {
        LA.intervalIds = [];
        LA.addIntervalId = function (intervalId, persist) {
            this.intervalIds.push({id:intervalId, persist:persist});
        };

        LA.clearIntervalId = function (intervalId) {
            for (var id in this.intervalIds) {
                if (intervalId == this.intervalIds[id].id && !this.intervalIds[id].persist) {
                    clearInterval(intervalId);
                    this.intervalIds.splice(id, 1);
                }
            }
        };

        LA.cleanIntervalId = function () {
            for (var id in this.intervalIds) {
                if (!this.intervalIds[id].persist) {
                    clearInterval(this.intervalIds[id].id);
                    this.intervalIds.splice(id, 1);
                }
            }
        };

        $(document).on('pjax:complete', function(xhr) {
            $.admin.cleanIntervalId();
        });

        $('.log-refresh').on('click', function() {
            $.pjax.reload('#pjax-container');
        });

        var pos = {{ $end }};

        function changePos(offset){
            pos = offset;
        }

        function fetch() {
            $.ajax({
                url:'{{ $tailPath }}',
                method: 'get',
                data : {offset : pos},
                success:function(data) {
                    for (var i in data.logs) {
                        $('table > tbody > tr:first').before(data.logs[i]);
                    }
                    changePos(data.pos);
                }
            });
        }

        var refreshIntervalId = null;

        $('.log-live').click(function() {
            $("i", this).toggleClass("fa-play fa-pause");

            if (refreshIntervalId) {
                $.admin.clearIntervalId(refreshIntervalId);
                refreshIntervalId = null;
            } else {
                refreshIntervalId = setInterval(function() {
                    fetch();
                }, 2000);
                $.admin.addIntervalId(refreshIntervalId, false);
            }
        });
    });


</script>
<div class="row">

    <!-- /.col -->
    <div class="col-md-10">
        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="button" class="btn btn-primary btn-sm log-refresh"><i class="fa fa-refresh"></i> {{ trans('admin.refresh') }}</button>
                <button type="button" class="btn btn-default btn-sm log-live"><i class="fa fa-play"></i> </button>
                <div class="pull-right">
                    <div class="btn-group">
                        @if ($prevUrl)
                        <a href="{{ $prevUrl }}" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
                        @endif
                        @if ($nextUrl)
                        <a href="{{ $nextUrl }}" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
                        @endif
                    </div>
                    <!-- /.btn-group -->
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">

                <div class="table-responsive">
                    <table class="table table-hover">

                        <thead>
                            <tr>
                                <th>Level</th>
                                <th>Env</th>
                                <th>Time</th>
                                <th>Message</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>

                        @foreach($logs as $index => $log)

                            <tr>
                                <td><span class="label bg-{{\Encore\Admin\LogViewer\LogViewer::$levelColors[$log['level']]}}">{{ $log['level'] }}</span></td>
                                <td><strong>{{ $log['env'] }}</strong></td>
                                <td  style="width:150px;">{{ $log['time'] }}</td>
                                <td><code>{{ $log['info'] }}</code></td>
                                <td>
                                    @if(!empty($log['trace']))
                                    <a class="btn btn-primary btn-xs" data-toggle="collapse" data-target=".trace-{{$index}}"><i class="fa fa-info"></i>&nbsp;&nbsp;Exception</a>
                                    @endif
                                </td>
                            </tr>

                            @if (!empty($log['trace']))
                            <tr class="collapse trace-{{$index}}">
                                <td colspan="5"><div style="white-space: pre-wrap;background: #333;color: #fff; padding: 10px;">{{ $log['trace'] }}</div></td>
                            </tr>
                            @endif

                        @endforeach

                        </tbody>
                    </table>
                    <!-- /.table -->
                </div>
                <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /. box -->
    </div>

    <div class="col-md-2">

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Files</h3>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                    @foreach($logFiles as $logFile)
                        <li @if($logFile == $fileName)class="active"@endif>
                            <a href="{{ route('log-viewer-file', ['file' => $logFile]) }}"><i class="fa fa-{{ ($logFile == $fileName) ? 'folder-open' : 'folder' }}"></i>{{ $logFile }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!-- /.box-body -->
        </div>

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Info</h3>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                    <li class="margin: 10px;">
                        <a>Size: {{ $size }}</a>
                    </li>
                    <li class="margin: 10px;">
                        <a>Updated at: {{ date('Y-m-d H:i:s', filectime($filePath)) }}</a>
                    </li>
                </ul>
            </div>
            <!-- /.box-body -->
        </div>

        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>