<html lang="en-US">
    <head>
        <meta charset="text/html">
    </head>
    <body>
        <p>{!! $content !!}</p>
        @if (isset($reason) && $reason != '')
            <div><b>Lý do từ chối</b>: </div>
            {!! nl2br($reason) !!}
        @endif

        @if (isset($list_work) && $list_work != '')
            <div><b>Công việc quản lý giao</b>: </div>
            {!! nl2br($list_work) !!}
        @endif

        @if (isset($days_config) && $days_config != '')
            <p>
                LƯU Ý: Bạn sẽ phải đăng ký và chờ phê duyệt lại nếu không phát sinh báo cáo trong {{ $days_config }} ngày.
            </p>
        @endif
    </body>
</html>