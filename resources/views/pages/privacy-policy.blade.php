@extends('layouts.app')
@push('head')
<style>
@media (max-width:640px) {
    .pp-grid-2 { grid-template-columns:1fr !important; }
    .pp-grid-3 { grid-template-columns:1fr !important; }
}
</style>
@endpush
@php $s = $settings ?? \App\Models\SiteSetting::current(); @endphp
@section('content')
<section style="background:linear-gradient(135deg,var(--color-primary),var(--color-primary-dark));padding:3rem 0;text-align:center;color:#fff">
    <div class="container">
        <span style="display:inline-block;padding:4px 14px;background:rgba(255,255,255,0.15);border-radius:20px;font-size:0.78rem;margin-bottom:12px"><i aria-hidden="true" class="fas fa-shield-alt"></i></span>
        <h1 style="font-size:2rem;font-weight:800;margin-bottom:8px">سياسة الخصوصية</h1>
        <p style="color:rgba(255,255,255,0.85);font-size:1rem;max-width:600px;margin:0 auto">خصوصيتك تهمنا. تعرف على كيفية جمع واستخدام وحماية بياناتك الشخصية.</p>
    </div>
</section>

<section style="padding:3rem 0">
    <div class="container" style="max-width:900px">
        {{-- Table of Contents --}}
        <nav style="background:#f8fafc;border:1px solid var(--color-border);border-radius:12px;padding:1.25rem;margin-bottom:2rem">
            <h3 style="font-size:1rem;font-weight:700;margin-bottom:12px;color:var(--color-heading)"><i aria-hidden="true" class="fas fa-list" style="color:var(--color-primary);margin-inline-end:8px"></i>فهرس المحتويات</h3>
            <div class="pp-grid-2" style="display:grid;grid-template-columns:1fr 1fr;gap:6px;font-size:0.88rem">
                <a href="#intro" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">1. مقدمة</a>
                <a href="#collect" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">2. البيانات التي نجمعها</a>
                <a href="#use" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">3. كيفية استخدام البيانات</a>
                <a href="#share" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">4. مشاركة البيانات</a>
                <a href="#cookies" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">5. ملفات تعريف الارتباط</a>
                <a href="#payment" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">6. أمان المدفوعات</a>
                <a href="#rights" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">7. حقوقك</a>
                <a href="#retention" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">8. الاحتفاظ بالبيانات</a>
                <a href="#children" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">9. خصوصية الأطفال</a>
                <a href="#changes" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">10. التغييرات على السياسة</a>
                <a href="#contact" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">11. التواصل معنا</a>
            </div>
        </nav>

        {{-- 1. Introduction --}}
        <article id="intro" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">1</span>
                مقدمة
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>مرحباً بكم في موقع ساهم للإغاثة والإنسانية. نحن ملتزمون بحماية خصوصيتك وأمان بياناتك الشخصية. توضح سياسة الخصوصية هذه كيفية جمع واستخدام وحماية المعلومات الشخصية التي تقدمها لنا عند استخدامك لموقعنا الإلكتروني وخدماتنا.</p>
                <p>باستخدامك لهذا الموقع، فإنك توافق على الممارسات الموضحة في هذه السياسة. إذا كنت لا توافق على أي جزء من هذه السياسة، يرجى عدم استخدام الموقع.</p>
                <p>نلتزم بجميع القوانين واللوائح المعمول بها لحماية البيانات الشخصية، بما في ذلك القوانين المحلية والدولية ذات الصلة.</p>
            </div>
        </article>

        {{-- 2. Data Collection --}}
        <article id="collect" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">2</span>
                البيانات التي نجمعها
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>نقوم بجمع الأنواع التالية من البيانات الشخصية:</p>
                <div class="pp-grid-2" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin:16px 0">
                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px">
                        <h4 style="font-weight:700;font-size:0.9rem;margin-bottom:6px;color:var(--color-heading)"><i aria-hidden="true" class="fas fa-user" style="color:var(--color-primary);margin-inline-end:6px"></i>معلومات شخصية</h4>
                        <ul style="margin:0;padding-inline-start:20px;font-size:0.85rem;color:var(--color-text)">
                            <li>الاسم الكامل</li>
                            <li>البريد الإلكتروني</li>
                            <li>رقم الهاتف</li>
                            <li>العنوان البريدي</li>
                        </ul>
                    </div>
                    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:14px">
                        <h4 style="font-weight:700;font-size:0.9rem;margin-bottom:6px;color:var(--color-heading)"><i aria-hidden="true" class="fas fa-credit-card" style="color:var(--color-primary);margin-inline-end:6px"></i>معلومات الدفع</h4>
                        <ul style="margin:0;padding-inline-start:20px;font-size:0.85rem;color:var(--color-text)">
                            <li>نوع طريقة الدفع</li>
                            <li>آخر 4 أرقام من البطاقة</li>
                            <li>معرّف المعاملة</li>
                            <li>لا نخزّن بيانات البطاقة الكاملة</li>
                        </ul>
                    </div>
                    <div style="background:#fefce8;border:1px solid #fde68a;border-radius:10px;padding:14px">
                        <h4 style="font-weight:700;font-size:0.9rem;margin-bottom:6px;color:var(--color-heading)"><i aria-hidden="true" class="fas fa-chart-bar" style="color:var(--color-primary);margin-inline-end:6px"></i>بيانات الاستخدام</h4>
                        <ul style="margin:0;padding-inline-start:20px;font-size:0.85rem;color:var(--color-text)">
                            <li>عنوان IP</li>
                            <li>نوع المتصفح</li>
                            <li>نظام التشغيل</li>
                            <li>صفحات الزيارة وמשךها</li>
                        </ul>
                    </div>
                    <div style="background:#fdf4ff;border:1px solid #e9d5ff;border-radius:10px;padding:14px">
                        <h4 style="font-weight:700;font-size:0.9rem;margin-bottom:6px;color:var(--color-heading)"><i aria-hidden="true" class="fas fa-hands-helping" style="color:var(--color-primary);margin-inline-end:6px"></i>بيانات التطوع</h4>
                        <ul style="margin:0;padding-inline-start:20px;font-size:0.85rem;color:var(--color-text)">
                            <li>المهارات والخبرات</li>
                            <li>المنطقة الجغرافية</li>
                            <li>الأوقات المتاحة</li>
                            <li>صورة الملف الشخصي</li>
                        </ul>
                    </div>
                </div>
            </div>
        </article>

        {{-- 3. Use of Data --}}
        <article id="use" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">3</span>
                كيفية استخدام البيانات
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>نستخدم البيانات الشخصية التي نجمعها للأغراض التالية:</p>
                <div style="display:flex;flex-direction:column;gap:10px;margin:16px 0">
                    <div style="display:flex;align-items:start;gap:12px;padding:12px;background:var(--color-bg);border-radius:8px">
                        <i aria-hidden="true" class="fas fa-donate" style="color:var(--color-primary);font-size:1.1rem;margin-top:3px"></i>
                        <div><strong style="display:block;font-size:0.9rem;color:var(--color-heading)">معالجة التبرعات</strong><span style="font-size:0.85rem">معالجة وإدارة التبرعات المالية وإيصال إشعارات الدفع والتقارير المالية.</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:12px;padding:12px;background:var(--color-bg);border-radius:8px">
                        <i aria-hidden="true" class="fas fa-envelope" style="color:var(--color-primary);font-size:1.1rem;margin-top:3px"></i>
                        <div><strong style="display:block;font-size:0.9rem;color:var(--color-heading)">التواصل معك</strong><span style="font-size:0.85rem">إرسال تحديثات حول حملاتنا الإغاثية، وتقارير الأثر، وإيصالات التبرع، والأخبار العاجلة.</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:12px;padding:12px;background:var(--color-bg);border-radius:8px">
                        <i aria-hidden="true" class="fas fa-user-check" style="color:var(--color-primary);font-size:1.1rem;margin-top:3px"></i>
                        <div><strong style="display:block;font-size:0.9rem;color:var(--color-heading)">التحقق من الهوية</strong><span style="font-size:0.85rem">التحقق من هوية المتبرعين والمتطوعين لمنع الاحتيال وضمان أمان المنصة.</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:12px;padding:12px;background:var(--color-bg);border-radius:8px">
                        <i aria-hidden="true" class="fas fa-chart-line" style="color:var(--color-primary);font-size:1.1rem;margin-top:3px"></i>
                        <div><strong style="display:block;font-size:0.9rem;color:var(--color-heading)">تحسين الخدمات</strong><span style="font-size:0.85rem">تحليل بيانات الاستخدام لتحسين تجربة المستخدم وتطوير خدماتنا الإغاثية.</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:12px;padding:12px;background:var(--color-bg);border-radius:8px">
                        <i aria-hidden="true" class="fas fa-gavel" style="color:var(--color-primary);font-size:1.1rem;margin-top:3px"></i>
                        <div><strong style="display:block;font-size:0.9rem;color:var(--color-heading)">الامتثال القانوني</strong><span style="font-size:0.85rem">الامتثال للقوانين واللوائح المعمول بها، بما في ذلك قوانين مكافحة غسل الأموال وتمويل الإرهاب.</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:12px;padding:12px;background:var(--color-bg);border-radius:8px">
                        <i aria-hidden="true" class="fas fa-clipboard-list" style="color:var(--color-primary);font-size:1.1rem;margin-top:3px"></i>
                        <div><strong style="display:block;font-size:0.9rem;color:var(--color-heading)">التقارير والشفافية</strong><span style="font-size:0.85rem">إعداد تقارير مالية وإغاثية للمتبرعين والجهات الرقابية لضمان الشفافية.</span></div>
                    </div>
                </div>
            </div>
        </article>

        {{-- 4. Data Sharing --}}
        <article id="share" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">4</span>
                مشاركة البيانات مع أطراف ثالثة
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p><strong style="color:var(--color-heading)">نلتزم بعدم بيع بياناتك الشخصية لأي طرف ثالث.</strong> قد نشارك بياناتك فقط في الحالات التالية:</p>
                <div style="margin:16px 0;border:1px solid var(--color-border);border-radius:10px;overflow:hidden">
                    <table style="width:100%;border-collapse:collapse;font-size:0.88rem">
                        <thead>
                            <tr style="background:#f8fafc">
                                <th style="padding:12px;text-align:right;font-weight:700;color:var(--color-heading);border-bottom:1px solid var(--color-border)">الطرف</th>
                                <th style="padding:12px;text-align:right;font-weight:700;color:var(--color-heading);border-bottom:1px solid var(--color-border)">الغرض</th>
                                <th style="padding:12px;text-align:right;font-weight:700;color:var(--color-heading);border-bottom:1px solid var(--color-border)">الضمانات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding:12px;border-bottom:1px solid #f1f5f9">بوابات الدفع (Stripe, PayPal)</td>
                                <td style="padding:12px;border-bottom:1px solid #f1f5f9">معالجة المدفوعات</td>
                                <td style="padding:12px;border-bottom:1px solid #f1f5f9">PCI DSS، تشفير SSL</td>
                            </tr>
                            <tr>
                                <td style="padding:12px;border-bottom:1px solid #f1f5f9">مزودو خدمات البريد الإلكتروني</td>
                                <td style="padding:12px;border-bottom:1px solid #f1f5f9">إرسال الإيصالات والتحديثات</td>
                                <td style="padding:12px;border-bottom:1px solid #f1f5f9">اتفاقيات حماية البيانات</td>
                            </tr>
                            <tr>
                                <td style="padding:12px;border-bottom:1px solid #f1f5f9">الجهات الحكومية الرقابية</td>
                                <td style="padding:12px;border-bottom:1px solid #f1f5f9">الامتثال القانوني</td>
                                <td style="padding:12px;border-bottom:1px solid #f1f5f9">بموجب أمر قضائي أو قانون</td>
                            </tr>
                            <tr>
                                <td style="padding:12px">شركاء الإغاثة الميدانية</td>
                                <td style="padding:12px">تنسيق وتوصيل المساعدات</td>
                                <td style="padding:12px">اتفاقيات سرية مشددة</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </article>

        {{-- 5. Cookies --}}
        <article id="cookies" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">5</span>
                ملفات تعريف الارتباط (Cookies)
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>نستخدم ملفات تعريف الارتباط لتحسين تجربتك على موقعنا. تشمل أنواع الكوكيز المستخدمة:</p>
                <div style="display:flex;flex-direction:column;gap:10px;margin:16px 0">
                    <div style="display:flex;align-items:start;gap:12px;padding:12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px">
                        <i aria-hidden="true" class="fas fa-check-circle" style="color:#059669;font-size:1rem;margin-top:3px"></i>
                        <div><strong style="display:block;font-size:0.9rem;color:var(--color-heading)">كوكيز ضرورية</strong><span style="font-size:0.85rem">مطلوبة لعمل الموقع بشكل صحيح (جلسة المستخدم، سلة التبرع، تفضيلات اللغة).</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:12px;padding:12px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px">
                        <i aria-hidden="true" class="fas fa-chart-pie" style="color:var(--color-primary);font-size:1rem;margin-top:3px"></i>
                        <div><strong style="display:block;font-size:0.9rem;color:var(--color-heading)">كوكيز تحليلية</strong><span style="font-size:0.85rem">تساعدنا في فهم كيفية استخدام الزوار للموقع (Google Analytics) لتحسين المحتوى.</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:12px;padding:12px;background:#fefce8;border:1px solid #fde68a;border-radius:8px">
                        <i aria-hidden="true" class="fas fa-cog" style="color:#ca8a04;font-size:1rem;margin-top:3px"></i>
                        <div><strong style="display:block;font-size:0.9rem;color:var(--color-heading)">كوكيز وظيفية</strong><span style="font-size:0.85rem">تذكر تفضيلاتك مثل الوضع المظلم أو حجم الخط لتوفير تجربة مخصصة.</span></div>
                    </div>
                </div>
                <p>يمكنك التحكم في الكوكيز من خلال إعدادات متصفحك. لاحظ أن تعطيل بعض الكوكيز قد يؤثر على وظائف الموقع.</p>
            </div>
        </article>

        {{-- 6. Payment Security --}}
        <article id="payment" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">6</span>
                أمان المدفوعات
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>نأخذ أمان معاملاتك المالية على محمل الجد. نطبق التدابير التالية:</p>
                    <div class="pp-grid-3" style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin:16px 0">
                    <div style="text-align:center;padding:20px;background:var(--color-bg);border-radius:12px;border:1px solid var(--color-border)">
                        <i aria-hidden="true" class="fas fa-lock" style="font-size:2rem;color:var(--color-primary);margin-bottom:10px;display:block"></i>
                        <strong style="font-size:0.9rem;display:block;color:var(--color-heading)">تشفير SSL/TLS</strong>
                        <span style="font-size:0.8rem;color:var(--color-text-muted)">جميع البيانات مشفرة أثناء النقل</span>
                    </div>
                    <div style="text-align:center;padding:20px;background:var(--color-bg);border-radius:12px;border:1px solid var(--color-border)">
                        <i aria-hidden="true" class="fas fa-shield-alt" style="font-size:2rem;color:var(--color-primary);margin-bottom:10px;display:block"></i>
                        <strong style="font-size:0.9rem;display:block;color:var(--color-heading)">PCI DSS</strong>
                        <span style="font-size:0.8rem;color:var(--color-text-muted)">متوافق مع معايير أمن بيانات بطاقات الدفع</span>
                    </div>
                    <div style="text-align:center;padding:20px;background:var(--color-bg);border-radius:12px;border:1px solid var(--color-border)">
                        <i aria-hidden="true" class="fas fa-fingerprint" style="font-size:2rem;color:var(--color-primary);margin-bottom:10px;display:block"></i>
                        <strong style="font-size:0.9rem;display:block;color:var(--color-heading)">Tokenization</strong>
                        <span style="font-size:0.8rem;color:var(--color-text-muted)">لا نخزّن بيانات البطاقة على خوادمنا</span>
                    </div>
                </div>
                <p>نستخدم بوابات دفع معتمدة وموثوقة (Stripe, PayPal) ومعالجة العملات المشفرة. جميع المعاملات تتم عبر خوادم آمنة ومعتمدة من هيئات أمن المعلومات الدولية.</p>
            </div>
        </article>

        {{-- 7. Your Rights --}}
        <article id="rights" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">7</span>
                حقوقك
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>لديك الحقوق التالية فيما يتعلق ببياناتك الشخصية:</p>
                <div class="pp-grid-2" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin:16px 0">
                    <div style="display:flex;align-items:start;gap:10px;padding:12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px">
                        <i aria-hidden="true" class="fas fa-eye" style="color:#059669;font-size:1rem;margin-top:3px"></i>
                        <div><strong style="font-size:0.88rem;display:block;color:var(--color-heading)">الاطلاع</strong><span style="font-size:0.82rem">طلب نسخة من بياناتك الشخصية المحفوظة لدينا.</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:10px;padding:12px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px">
                        <i aria-hidden="true" class="fas fa-edit" style="color:var(--color-primary);font-size:1rem;margin-top:3px"></i>
                        <div><strong style="font-size:0.88rem;display:block;color:var(--color-heading)">التصحيح</strong><span style="font-size:0.82rem">طلب تصحيح أي بيانات غير دقيقة أو غير مكتملة.</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:10px;padding:12px;background:#fef2f2;border:1px solid #fca5a5;border-radius:8px">
                        <i aria-hidden="true" class="fas fa-trash-alt" style="color:#dc2626;font-size:1rem;margin-top:3px"></i>
                        <div><strong style="font-size:0.88rem;display:block;color:var(--color-heading)">الحذف</strong><span style="font-size:0.82rem">طلب حذف بياناتك الشخصية (مع مراعاة الالتزامات القانونية).</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:10px;padding:12px;background:#fefce8;border:1px solid #fde68a;border-radius:8px">
                        <i aria-hidden="true" class="fas fa-ban" style="color:#ca8a04;font-size:1rem;margin-top:3px"></i>
                        <div><strong style="font-size:0.88rem;display:block;color:var(--color-heading)">الاعتراض</strong><span style="font-size:0.82rem">الاعتراض على معالجة بياناتك لأغراض تسويقية.</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:10px;padding:12px;background:#f5f3ff;border:1px solid #c4b5fd;border-radius:8px">
                        <i aria-hidden="true" class="fas fa-download" style="color:#7c3aed;font-size:1rem;margin-top:3px"></i>
                        <div><strong style="font-size:0.88rem;display:block;color:var(--color-heading)">النقل</strong><span style="font-size:0.82rem">طلب نقل بياناتك إلى مزود خدمة آخر بصيغة مقروءة.</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:10px;padding:12px;background:#fff7ed;border:1px solid #fed7aa;border-radius:8px">
                        <i aria-hidden="true" class="fas fa-pause-circle" style="color:#ea580c;font-size:1rem;margin-top:3px"></i>
                        <div><strong style="font-size:0.88rem;display:block;color:var(--color-heading)">التقييد</strong><span style="font-size:0.82rem">طلب تقييد معالجة بياناتك في ظروف معينة.</span></div>
                    </div>
                </div>
                <p>لممارسة أي من هذه الحقوق، يرجى التواصل معنا عبر البريد الإلكتروني أو نموذج الاتصال في الموقع.</p>
            </div>
        </article>

        {{-- 8. Data Retention --}}
        <article id="retention" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">8</span>
                الاحتفاظ بالبيانات
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>نحتفظ ببياناتك الشخصية فقط للمدة اللازمة لتحقيق الأغراض الموضحة في هذه السياسة:</p>
                <ul style="margin:12px 0;padding-inline-start:20px">
                    <li style="margin-bottom:8px"><strong style="color:var(--color-heading)">بيانات التبرع:</strong> نحتفظ بها لمدة 7 سنوات كحد أدنى لأغراض الامتثال الضريبي والمالي.</li>
                    <li style="margin-bottom:8px"><strong style="color:var(--color-heading)">بيانات الحساب:</strong> نحتفظ بها طوال فترة نشاط الحساب، وتحذف تلقائياً بعد 3 سنوات من عدم النشاط.</li>
                    <li style="margin-bottom:8px"><strong style="color:var(--color-heading)">بيانات الاستخدام:</strong> نحتفظ بها لمدة سنة واحدة لأغراض التحليل وتحسين الخدمة.</li>
                    <li style="margin-bottom:8px"><strong style="color:var(--color-heading)">سجلات الأمان:</strong> نحتفظ بها لمدة 6 أشهر لأغراض مكافحة الاحتيال.</li>
                </ul>
                <p>بعد انتهاء فترة الاحتفاظ، يتم حذف البيانات بشكل آمن أو إخفاء هويتها بحيث لا يمكن ربطها بك.</p>
            </div>
        </article>

        {{-- 9. Children's Privacy --}}
        <article id="children" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">9</span>
                خصوصية الأطفال
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>موقعنا مصمم للأشخاص الذين تبلغ أعمارهم 18 عاماً أو أكثر. لا نجمع عن علم بيانات شخصية من الأطفال دون سن 18 عاماً.</p>
                <p>إذا علمنا أننا جمعنا بيانات شخصية من طفل دون سن 18 عاماً دون موافقة الوالدين، سنحذف هذه البيانات فوراً. إذا كنت تعتقد أن طفلك قد قدم لنا بيانات شخصية، يرجى التواصل معنا immediately.</p>
            </div>
        </article>

        {{-- 10. Changes to Policy --}}
        <article id="changes" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">10</span>
                التغييرات على سياسة الخصوصية
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>نحتفظ بالحق في تحديث سياسة الخصوصية هذه في أي وقت. سيتم نشر أي تغييرات على هذه الصفحة مع تحديث تاريخ "آخر تعديل" في أعلى الصفحة.</p>
                <p>في حالة التغييرات الجوهرية، سنقوم بإخطارك عبر البريد الإلكتروني أو من خلال إشعار بارز على موقعنا قبل أن تصبح التغييرات سارية المفعول.</p>
                <p>ننصحك بمراجعة هذه الصفحة دورياً للبقاء على اطلاع بأحدث ممارساتنا في حماية الخصوصية.</p>
            </div>
        </article>

        {{-- 11. Contact --}}
        <article id="contact" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">11</span>
                التواصل معنا
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>إذا كانت لديك أي أسئلة أو استفسارات حول سياسة الخصوصية هذه أو ممارساتنا في مجال حماية البيانات، يرجى التواصل معنا:</p>
                <div class="pp-grid-3" style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin:16px 0">
                    @if($s->email)
                    <div style="text-align:center;padding:20px;background:var(--color-bg);border-radius:12px;border:1px solid var(--color-border)">
                        <i aria-hidden="true" class="fas fa-envelope" style="font-size:1.5rem;color:var(--color-primary);margin-bottom:8px;display:block"></i>
                        <strong style="font-size:0.85rem;display:block;color:var(--color-heading)">البريد الإلكتروني</strong>
                        <a href="mailto:{{ $s->email }}" style="font-size:0.82rem;color:var(--color-primary);text-decoration:none">{{ $s->email }}</a>
                    </div>
                    @endif
                    @if($s->phone)
                    <div style="text-align:center;padding:20px;background:var(--color-bg);border-radius:12px;border:1px solid var(--color-border)">
                        <i aria-hidden="true" class="fas fa-phone" style="font-size:1.5rem;color:var(--color-primary);margin-bottom:8px;display:block"></i>
                        <strong style="font-size:0.85rem;display:block;color:var(--color-heading)">الهاتف</strong>
                        <a href="tel:{{ preg_replace('/\s+/', '', $s->phone) }}" style="font-size:0.82rem;color:var(--color-primary);text-decoration:none">{{ $s->phone }}</a>
                    </div>
                    @endif
                    @if($s->whatsapp)
                    <div style="text-align:center;padding:20px;background:var(--color-bg);border-radius:12px;border:1px solid var(--color-border)">
                        <i aria-hidden="true" class="fab fa-whatsapp" style="font-size:1.5rem;color:var(--color-primary);margin-bottom:8px;display:block"></i>
                        <strong style="font-size:0.85rem;display:block;color:var(--color-heading)">واتساب</strong>
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $s->whatsapp) }}" target="_blank" rel="noopener" style="font-size:0.82rem;color:var(--color-primary);text-decoration:none">{{ $s->whatsapp }}</a>
                    </div>
                    @endif
                </div>
            </div>
        </article>

        <p style="text-align:center;color:var(--color-text-muted);font-size:0.82rem;margin-top:2rem">آخر تحديث: {{ date('Y-m-d') }}</p>
    </div>
</section>
@endsection
