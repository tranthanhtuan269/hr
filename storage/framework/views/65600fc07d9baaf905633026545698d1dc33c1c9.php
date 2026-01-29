<?php echo $content; ?>


<div style="overflow-x:auto;">
    <table style=" border-collapse: collapse;border-spacing: 0; width: 100%; border: 1px solid #ddd;">
        <thead>
            <tr>
                <th style="text-align: center;padding:5px; border: 1px solid #ddd;">Họ và tên</th>
                <th style="text-align: center;padding:5px; border: 1px solid #ddd;">Ngày n/l gần nhất</th>
                <th style="text-align: center;padding:5px; border: 1px solid #ddd;">H/s lương trước</th>
                <th style="text-align: center;padding:5px; border: 1px solid #ddd;">H/s lương hiện tại</th>
                <th style="text-align: center;padding:5px; border: 1px solid #ddd;">Thời gian a/d mức lương mới</th>
                <th style="text-align: center;padding:5px; border: 1px solid #ddd;">Khoảng t/g được xét TL</th>
                <th style="text-align: center;padding:5px; border: 1px solid #ddd;">Tổng hệ số t/g truy lĩnh thực tế(NCTT/NCTC)</th>
                <th style="text-align: center;padding:5px; border: 1px solid #ddd;">Số tiền TL</th>
            </tr>
        </thead>
        <tbody>
             <tr>
                <td style="text-align: left;padding:5px; border: 1px solid #ddd;"> <?php echo e($fullname); ?> </td> 
                <td style="text-align: center;padding:5px; border: 1px solid #ddd;"><?php echo e(BatvHelper::formatDate($date_hdct,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?></td>
                <td style="text-align: center;padding:5px; border: 1px solid #ddd;"><?php echo e($hsl_old); ?></td>
                <td style="text-align: center;padding:5px; border: 1px solid #ddd;"><?php echo e($hsl_ht); ?></td>
                <td style="text-align: center;padding:5px; border: 1px solid #ddd;"><?php echo e(BatvHelper::formatDate($date_nlgn,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?></td>
                <td style="text-align: center;padding:5px; border: 1px solid #ddd;">Từ <?php echo e($period["from"]); ?> đến <?php echo e($period["to"]); ?></td>
                <td style="text-align: center;padding:5px; border: 1px solid #ddd;"><?php echo e(round($param_hs,3)); ?></td>
                <td style="text-align: center;padding:5px; border: 1px solid #ddd;"><?php echo e(BatvHelper::formatPrice($value_tt)); ?></td>
            </tr>
    	</tbody>
	</table>
</div>