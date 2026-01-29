
<div style="overflow-x:auto;">
    <table style=" border-collapse: collapse;border-spacing: 0; width: 100%; border: 1px solid #ddd;">
        <thead>
            <tr>
                <th style="text-align: center;padding:5px; border: 1px solid #ddd;">Họ và tên</th>
                <th style="text-align: center;padding:5px; border: 1px solid #ddd;">Thông tin thay đổi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $value)
                <tr>
                    <td style="text-align: left;padding:5px; border: 1px solid #ddd;"> {{ $value['fullname'] }} </td> 
                    <td style="text-align: left;padding:5px; border: 1px solid #ddd;">
                        @if (isset($value['ratio']['old'] ) && $value['ratio']['old'] > 0)
                            <div>
                                Hệ số lương: {{ $value['ratio']['old'] }} => {{ $value['ratio']['new'] }}
                            </div>
                        @endif

                        @if (isset($value['laptop_allowance']['old'] ))
                            <div>
                                Phụ cấp nếu sử dụng Laptop cá nhân: {{ $value['laptop_allowance']['old'] }} => {{ $value['laptop_allowance']['new'] }}
                            </div>
                        @endif

                        @if (isset($value['other_tax_allowance']['old'] ))
                            <div>
                                Phụ cấp nếu không tham gia bảo hiểm: {{ $value['other_tax_allowance']['old'] }} => {{ $value['other_tax_allowance']['new'] }}
                            </div>
                        @endif

                        @if (isset($value['parking_fee_allowance']['old'] ))
                            <div>
                                Phụ cấp tiền gửi xe: {{ $value['parking_fee_allowance']['old'] }} => {{ $value['parking_fee_allowance']['new'] }}
                            </div>
                        @endif

                        @if (isset($value['management_allowance']['old'] ))
                            <div>
                                Phụ cấp trách nhiệm: {{ $value['management_allowance']['old'] }} => {{ $value['management_allowance']['new'] }}
                            </div>
                        @endif

                        @if (isset($value['phone_allowance']['old'] ))
                            <div>
                                Phụ cấp điện thoại: {{ $value['phone_allowance']['old'] }} => {{ $value['phone_allowance']['new'] }}
                            </div>
                        @endif

                        @if (isset($value['travel_allowance']['old'] ))
                            <div>
                                Phụ cấp đi lại: {{ $value['travel_allowance']['old'] }} => {{ $value['travel_allowance']['new'] }}
                            </div>
                        @endif

                        @if (isset($value['lunch_allowance']['old'] ))
                            <div>
                                Phụ cấp ăn trưa: {{ $value['lunch_allowance']['old'] }} => {{ $value['lunch_allowance']['new'] }}
                            </div>
                        @endif

                        @if (isset($value['subsidize_house']['old'] ))
                            <div>
                                Tiền trợ cấp nhà ở: {{ $value['subsidize_house']['old'] }} => {{ $value['subsidize_house']['new'] }}
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
    	</tbody>
	</table>
</div>