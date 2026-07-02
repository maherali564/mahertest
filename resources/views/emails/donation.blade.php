<!DOCTYPE html>
<html dir="rtl">
<head><meta charset="UTF-8"><title>إشعار تبرع</title></head>
<body style="font-family: 'Cairo', sans-serif; margin:0; padding:24px; background:#f8fafc">
    <div style="max-width:560px; margin:0 auto; background:#fff; border-radius:12px; padding:32px; box-shadow:0 2px 12px rgba(0,0,0,0.06)">
        <h1 style="font-size:1.25rem; color:#0d6b4f; margin-bottom:16px">
            @switch($type ?? 'completed')
                @case('completed') تم تأكيد تبرعك @break
                @case('under_review') تم استلام طلب التبرع @break
                @case('failed') تعذر إتمام التبرع @break
                @default شكراً لتبرعك
            @endswitch
        </h1>
        <p style="color:#475569; line-height:1.8">{{ $donation->donor_name }}، شكراً لك على كرمك.</p>
        <table style="width:100%; border-collapse:collapse; margin:16px 0">
            <tr><td style="padding:8px; color:#64748b">المبلغ</td><td style="padding:8px; font-weight:700">{{ number_format($donation->amount, 2) }} {{ $donation->currency }}</td></tr>
            <tr><td style="padding:8px; color:#64748b">رقم العملية</td><td style="padding:8px; font-weight:700">{{ $donation->transaction_id }}</td></tr>
            <tr><td style="padding:8px; color:#64748b">التاريخ</td><td style="padding:8px; font-weight:700">{{ $donation->created_at->format('Y-m-d H:i') }}</td></tr>
        </table>
    </div>
</body>
</html>
