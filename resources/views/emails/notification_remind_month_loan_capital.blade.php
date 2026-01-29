@if (isset($data['remind']) && $data['remind'] != '')
    <p>
        {{ $data['remind'] }}
    </p>
@endif

<div style="overflow-x:auto;">
    <table style=" border-collapse: collapse;border-spacing: 0; width: 100%; border: 1px solid #ddd;">
        <thead>
            <tr>
                <th style="text-align: center;padding:5px; border: 1px solid #ddd;">Họ và tên</th>
                <th colspan="2">Kỳ trả nợ</th>
                <th style="text-align: center;padding:5px; border: 1px solid #ddd;">Gốc</th>
                <th style="text-align: center;padding:5px; border: 1px solid #ddd;">Tiền thiếu tháng trước</th>
                <th style="text-align: center;padding:5px; border: 1px solid #ddd;">Tiền dư tháng trước</th>
                <th style="text-align: center;padding:5px; border: 1px solid #ddd;">Lãi</th>
                <th style="text-align: center;padding:5px; border: 1px solid #ddd;">Phạt trả chậm</th>
                <th style="text-align: center;padding:5px; border: 1px solid #ddd;">Tổng số tiền phải trả</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;padding:5px; border: 1px solid #ddd;">{{ $data['fullname'] }}</td>
                <td style="text-align: center;padding:5px; border: 1px solid #ddd;">{{ BatvHelper::formatDate($data['repayment_period'], 'Y-m-d', 'd/m/Y', 'H:i:s', false)}}</td>
                <td style="text-align: center;padding:5px; border: 1px solid #ddd;">{{ $data['month'] }}</td>
                <td style="text-align: center;padding:5px; border: 1px solid #ddd;">{{ number_format($data['principal']) }}</td>
                <td style="text-align: center;padding:5px; border: 1px solid #ddd;">{{ number_format($data['wanting_month_prev_money'])  }}</td>
                <td style="text-align: center;padding:5px; border: 1px solid #ddd;">{{ number_format($data['redundancy_month_prev_money']) }}</td>
                <td style="text-align: center;padding:5px; border: 1px solid #ddd;">{{ number_format($data['interest'])  }}</td>
                <td style="text-align: center;padding:5px; border: 1px solid #ddd;">{{ number_format($data['interest_incurred'])  }}</td>
                <td style="text-align: center;padding:5px; border: 1px solid #ddd;">{{ number_format($data['principal'] + $data['interest'] + $data['interest_incurred'] + $data['wanting_month_prev_money'] - $data['redundancy_month_prev_money']) }}</td>
            </tr>
        </tbody>
    </table>
</div>