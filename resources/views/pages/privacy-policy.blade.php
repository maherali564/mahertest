@extends('layouts.app')
@push('head')
<style>
@media (max-width:640px) {
    .pp-grid-2 { grid-template-columns:1fr !important; }
    .pp-grid-3 { grid-template-columns:1fr !important; }
}
</style>
@endpush
@php $s = $settings ?? \App\Models\SiteSetting::current(); $isAr = app()->getLocale() === 'ar'; @endphp
@section('content')
<section style="background:linear-gradient(135deg,var(--color-primary),var(--color-primary-dark));padding:3rem 0;text-align:center;color:#fff">
    <div class="container">
        <span style="display:inline-block;padding:4px 14px;background:rgba(255,255,255,0.15);border-radius:20px;font-size:0.78rem;margin-bottom:12px"><i aria-hidden="true" class="fas fa-shield-alt"></i></span>
        <h1 style="font-size:2rem;font-weight:800;margin-bottom:8px">{{ $isAr ? 'سياسة الخصوصية' : 'Privacy Policy' }}</h1>
        <p style="color:rgba(255,255,255,0.85);font-size:1rem;max-width:600px;margin:0 auto">{{ $isAr ? 'خصوصيتك تهمنا. تعرف على كيفية جمع واستخدام وحماية بياناتك الشخصية.' : 'Your privacy matters to us. Learn how we collect, use, and protect your personal data.' }}</p>
    </div>
</section>

<section style="padding:3rem 0">
    <div class="container" style="max-width:900px">
        {{-- Table of Contents --}}
        <nav style="background:#f8fafc;border:1px solid var(--color-border);border-radius:12px;padding:1.25rem;margin-bottom:2rem">
            <h3 style="font-size:1rem;font-weight:700;margin-bottom:12px;color:var(--color-heading)"><i aria-hidden="true" class="fas fa-list" style="color:var(--color-primary);margin-inline-end:8px"></i>{{ $isAr ? 'فهرس المحتويات' : 'Table of Contents' }}</h3>
            <div class="pp-grid-2" style="display:grid;grid-template-columns:1fr 1fr;gap:6px;font-size:0.88rem">
                <a href="#intro" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">{{ $isAr ? '1. مقدمة' : '1. Introduction' }}</a>
                <a href="#collect" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">{{ $isAr ? '2. البيانات التي نجمعها' : '2. Data We Collect' }}</a>
                <a href="#use" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">{{ $isAr ? '3. كيفية استخدام البيانات' : '3. How We Use Data' }}</a>
                <a href="#share" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">{{ $isAr ? '4. مشاركة البيانات' : '4. Data Sharing' }}</a>
                <a href="#cookies" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">{{ $isAr ? '5. ملفات تعريف الارتباط' : '5. Cookies' }}</a>
                <a href="#payment" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">{{ $isAr ? '6. أمان المدفوعات' : '6. Payment Security' }}</a>
                <a href="#rights" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">{{ $isAr ? '7. حقوقك' : '7. Your Rights' }}</a>
                <a href="#retention" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">{{ $isAr ? '8. الاحتفاظ بالبيانات' : '8. Data Retention' }}</a>
                <a href="#children" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">{{ $isAr ? '9. خصوصية الأطفال' : "9. Children's Privacy" }}</a>
                <a href="#changes" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">{{ $isAr ? '10. التغييرات على السياسة' : '10. Changes to Policy' }}</a>
                <a href="#contact" style="color:var(--color-primary);text-decoration:none;padding:6px 10px;border-radius:6px;transition:background 0.2s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='transparent'">{{ $isAr ? '11. التواصل معنا' : '11. Contact Us' }}</a>
            </div>
        </nav>

        {{-- 1. Introduction --}}
        <article id="intro" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">1</span>
                {{ $isAr ? 'مقدمة' : 'Introduction' }}
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>{{ $isAr ? 'مرحباً بكم في موقع ساهم للإغاثة والإنسانية. نحن ملتزمون بحماية خصوصيتك وأمان بياناتك الشخصية. توضح سياسة الخصوصية هذه كيفية جمع واستخدام وحماية المعلومات الشخصية التي تقدمها لنا عند استخدامك لموقعنا الإلكتروني وخدماتنا.' : 'Welcome to Sahem Relief &amp; Humanitarian Aid. We are committed to protecting your privacy and the security of your personal data. This Privacy Policy explains how we collect, use, and protect the personal information you provide when using our website and services.' }}</p>
                <p>{{ $isAr ? 'باستخدامك لهذا الموقع، فإنك توافق على الممارسات الموضحة في هذه السياسة. إذا كنت لا توافق على أي جزء من هذه السياسة، يرجى عدم استخدام الموقع.' : 'By using this website, you agree to the practices described in this policy. If you do not agree with any part of this policy, please do not use the website.' }}</p>
                <p>{{ $isAr ? 'نلتزم بجميع القوانين واللوائح المعمول بها لحماية البيانات الشخصية، بما في ذلك القوانين المحلية والدولية ذات الصلة.' : 'We comply with all applicable laws and regulations for the protection of personal data, including relevant local and international laws.' }}</p>
            </div>
        </article>

        {{-- 2. Data Collection --}}
        <article id="collect" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">2</span>
                {{ $isAr ? 'البيانات التي نجمعها' : 'Data We Collect' }}
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>{{ $isAr ? 'نقوم بجمع الأنواع التالية من البيانات الشخصية:' : 'We collect the following types of personal data:' }}</p>
                <div class="pp-grid-2" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin:16px 0">
                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px">
                        <h4 style="font-weight:700;font-size:0.9rem;margin-bottom:6px;color:var(--color-heading)"><i aria-hidden="true" class="fas fa-user" style="color:var(--color-primary);margin-inline-end:6px"></i>{{ $isAr ? 'معلومات شخصية' : 'Personal Info' }}</h4>
                        <ul style="margin:0;padding-inline-start:20px;font-size:0.85rem;color:var(--color-text)">
                            <li>{{ $isAr ? 'الاسم الكامل' : 'Full Name' }}</li>
                            <li>{{ $isAr ? 'البريد الإلكتروني' : 'Email' }}</li>
                            <li>{{ $isAr ? 'رقم الهاتف' : 'Phone Number' }}</li>
                            <li>{{ $isAr ? 'العنوان البريدي' : 'Postal Address' }}</li>
                        </ul>
                    </div>
                    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:14px">
                        <h4 style="font-weight:700;font-size:0.9rem;margin-bottom:6px;color:var(--color-heading)"><i aria-hidden="true" class="fas fa-credit-card" style="color:var(--color-primary);margin-inline-end:6px"></i>{{ $isAr ? 'معلومات الدفع' : 'Payment Info' }}</h4>
                        <ul style="margin:0;padding-inline-start:20px;font-size:0.85rem;color:var(--color-text)">
                            <li>{{ $isAr ? 'نوع طريقة الدفع' : 'Payment Method Type' }}</li>
                            <li>{{ $isAr ? 'آخر 4 أرقام من البطاقة' : 'Last 4 Card Digits' }}</li>
                            <li>{{ $isAr ? 'معرّف المعاملة' : 'Transaction ID' }}</li>
                            <li>{{ $isAr ? 'لا نخزّن بيانات البطاقة الكاملة' : 'We do not store full card data' }}</li>
                        </ul>
                    </div>
                    <div style="background:#fefce8;border:1px solid #fde68a;border-radius:10px;padding:14px">
                        <h4 style="font-weight:700;font-size:0.9rem;margin-bottom:6px;color:var(--color-heading)"><i aria-hidden="true" class="fas fa-chart-bar" style="color:var(--color-primary);margin-inline-end:6px"></i>{{ $isAr ? 'بيانات الاستخدام' : 'Usage Data' }}</h4>
                        <ul style="margin:0;padding-inline-start:20px;font-size:0.85rem;color:var(--color-text)">
                            <li>{{ $isAr ? 'عنوان IP' : 'IP Address' }}</li>
                            <li>{{ $isAr ? 'نوع المتصفح' : 'Browser Type' }}</li>
                            <li>{{ $isAr ? 'نظام التشغيل' : 'Operating System' }}</li>
                            <li>{{ $isAr ? 'صفحات الزيارة ومدتها' : 'Pages Visited &amp; Duration' }}</li>
                        </ul>
                    </div>
                    <div style="background:#fdf4ff;border:1px solid #e9d5ff;border-radius:10px;padding:14px">
                        <h4 style="font-weight:700;font-size:0.9rem;margin-bottom:6px;color:var(--color-heading)"><i aria-hidden="true" class="fas fa-hands-helping" style="color:var(--color-primary);margin-inline-end:6px"></i>{{ $isAr ? 'بيانات التطوع' : 'Volunteer Data' }}</h4>
                        <ul style="margin:0;padding-inline-start:20px;font-size:0.85rem;color:var(--color-text)">
                            <li>{{ $isAr ? 'المهارات والخبرات' : 'Skills &amp; Experience' }}</li>
                            <li>{{ $isAr ? 'المنطقة الجغرافية' : 'Geographic Area' }}</li>
                            <li>{{ $isAr ? 'الأوقات المتاحة' : 'Available Times' }}</li>
                            <li>{{ $isAr ? 'صورة الملف الشخصي' : 'Profile Photo' }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </article>

        {{-- 3. Use of Data --}}
        <article id="use" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">3</span>
                {{ $isAr ? 'كيفية استخدام البيانات' : 'How We Use Your Data' }}
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>{{ $isAr ? 'نستخدم البيانات الشخصية التي نجمعها للأغراض التالية:' : 'We use the personal data we collect for the following purposes:' }}</p>
                <div style="display:flex;flex-direction:column;gap:10px;margin:16px 0">
                    <div style="display:flex;align-items:start;gap:12px;padding:12px;background:var(--color-bg);border-radius:8px">
                        <i aria-hidden="true" class="fas fa-donate" style="color:var(--color-primary);font-size:1.1rem;margin-top:3px"></i>
                        <div><strong style="display:block;font-size:0.9rem;color:var(--color-heading)">{{ $isAr ? 'معالجة التبرعات' : 'Donation Processing' }}</strong><span style="font-size:0.85rem">{{ $isAr ? 'معالجة وإدارة التبرعات المالية وإيصال إشعارات الدفع والتقارير المالية.' : 'Processing and managing financial donations, sending payment notifications and financial reports.' }}</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:12px;padding:12px;background:var(--color-bg);border-radius:8px">
                        <i aria-hidden="true" class="fas fa-envelope" style="color:var(--color-primary);font-size:1.1rem;margin-top:3px"></i>
                        <div><strong style="display:block;font-size:0.9rem;color:var(--color-heading)">{{ $isAr ? 'التواصل معك' : 'Communication' }}</strong><span style="font-size:0.85rem">{{ $isAr ? 'إرسال تحديثات حول حملاتنا الإغاثية، وتقارير الأثر، وإيصالات التبرع، والأخبار العاجلة.' : 'Sending updates about our relief campaigns, impact reports, donation receipts, and urgent news.' }}</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:12px;padding:12px;background:var(--color-bg);border-radius:8px">
                        <i aria-hidden="true" class="fas fa-user-check" style="color:var(--color-primary);font-size:1.1rem;margin-top:3px"></i>
                        <div><strong style="display:block;font-size:0.9rem;color:var(--color-heading)">{{ $isAr ? 'التحقق من الهوية' : 'Identity Verification' }}</strong><span style="font-size:0.85rem">{{ $isAr ? 'التحقق من هوية المتبرعين والمتطوعين لمنع الاحتيال وضمان أمان المنصة.' : 'Verifying donor and volunteer identities to prevent fraud and ensure platform security.' }}</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:12px;padding:12px;background:var(--color-bg);border-radius:8px">
                        <i aria-hidden="true" class="fas fa-chart-line" style="color:var(--color-primary);font-size:1.1rem;margin-top:3px"></i>
                        <div><strong style="display:block;font-size:0.9rem;color:var(--color-heading)">{{ $isAr ? 'تحسين الخدمات' : 'Service Improvement' }}</strong><span style="font-size:0.85rem">{{ $isAr ? 'تحليل بيانات الاستخدام لتحسين تجربة المستخدم وتطوير خدماتنا الإغاثية.' : 'Analyzing usage data to improve user experience and develop our relief services.' }}</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:12px;padding:12px;background:var(--color-bg);border-radius:8px">
                        <i aria-hidden="true" class="fas fa-gavel" style="color:var(--color-primary);font-size:1.1rem;margin-top:3px"></i>
                        <div><strong style="display:block;font-size:0.9rem;color:var(--color-heading)">{{ $isAr ? 'الامتثال القانوني' : 'Legal Compliance' }}</strong><span style="font-size:0.85rem">{{ $isAr ? 'الامتثال للقوانين واللوائح المعمول بها، بما في ذلك قوانين مكافحة غسل الأموال وتمويل الإرهاب.' : 'Complying with applicable laws and regulations, including anti-money laundering and counter-terrorism financing laws.' }}</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:12px;padding:12px;background:var(--color-bg);border-radius:8px">
                        <i aria-hidden="true" class="fas fa-clipboard-list" style="color:var(--color-primary);font-size:1.1rem;margin-top:3px"></i>
                        <div><strong style="display:block;font-size:0.9rem;color:var(--color-heading)">{{ $isAr ? 'التقارير والشفافية' : 'Reporting &amp; Transparency' }}</strong><span style="font-size:0.85rem">{{ $isAr ? 'إعداد تقارير مالية وإغاثية للمتبرعين والجهات الرقابية لضمان الشفافية.' : 'Preparing financial and relief reports for donors and regulatory bodies to ensure transparency.' }}</span></div>
                    </div>
                </div>
            </div>
        </article>

        {{-- 4. Data Sharing --}}
        <article id="share" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">4</span>
                {{ $isAr ? 'مشاركة البيانات مع أطراف ثالثة' : 'Data Sharing with Third Parties' }}
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p><strong style="color:var(--color-heading)">{{ $isAr ? 'نلتزم بعدم بيع بياناتك الشخصية لأي طرف ثالث.' : 'We are committed to not selling your personal data to any third party.' }}</strong> {{ $isAr ? 'قد نشارك بياناتك فقط في الحالات التالية:' : 'We may only share your data in the following cases:' }}</p>
                <div style="margin:16px 0;border:1px solid var(--color-border);border-radius:10px;overflow:hidden">
                    <table style="width:100%;border-collapse:collapse;font-size:0.88rem">
                        <thead>
                            <tr style="background:#f8fafc">
                                <th style="padding:12px;text-align:{{ $isAr ? 'right' : 'left' }};font-weight:700;color:var(--color-heading);border-bottom:1px solid var(--color-border)">{{ $isAr ? 'الطرف' : 'Party' }}</th>
                                <th style="padding:12px;text-align:{{ $isAr ? 'right' : 'left' }};font-weight:700;color:var(--color-heading);border-bottom:1px solid var(--color-border)">{{ $isAr ? 'الغرض' : 'Purpose' }}</th>
                                <th style="padding:12px;text-align:{{ $isAr ? 'right' : 'left' }};font-weight:700;color:var(--color-heading);border-bottom:1px solid var(--color-border)">{{ $isAr ? 'الضمانات' : 'Safeguards' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding:12px;border-bottom:1px solid #f1f5f9">{{ $isAr ? 'بوابات الدفع (Stripe, PayPal)' : 'Payment Gateways (Stripe, PayPal)' }}</td>
                                <td style="padding:12px;border-bottom:1px solid #f1f5f9">{{ $isAr ? 'معالجة المدفوعات' : 'Payment Processing' }}</td>
                                <td style="padding:12px;border-bottom:1px solid #f1f5f9">{{ $isAr ? 'PCI DSS، تشفير SSL' : 'PCI DSS, SSL Encryption' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px;border-bottom:1px solid #f1f5f9">{{ $isAr ? 'مزودو خدمات البريد الإلكتروني' : 'Email Service Providers' }}</td>
                                <td style="padding:12px;border-bottom:1px solid #f1f5f9">{{ $isAr ? 'إرسال الإيصالات والتحديثات' : 'Sending Receipts &amp; Updates' }}</td>
                                <td style="padding:12px;border-bottom:1px solid #f1f5f9">{{ $isAr ? 'اتفاقيات حماية البيانات' : 'Data Protection Agreements' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px;border-bottom:1px solid #f1f5f9">{{ $isAr ? 'الجهات الحكومية الرقابية' : 'Government Regulatory Bodies' }}</td>
                                <td style="padding:12px;border-bottom:1px solid #f1f5f9">{{ $isAr ? 'الامتثال القانوني' : 'Legal Compliance' }}</td>
                                <td style="padding:12px;border-bottom:1px solid #f1f5f9">{{ $isAr ? 'بموجب أمر قضائي أو قانون' : 'By Court Order or Law' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px">{{ $isAr ? 'شركاء الإغاثة الميدانية' : 'Field Relief Partners' }}</td>
                                <td style="padding:12px">{{ $isAr ? 'تنسيق وتوصيل المساعدات' : 'Coordinating Aid Delivery' }}</td>
                                <td style="padding:12px">{{ $isAr ? 'اتفاقيات سرية مشددة' : 'Strict Confidentiality Agreements' }}</td>
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
                {{ $isAr ? 'ملفات تعريف الارتباط (Cookies)' : 'Cookies' }}
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>{{ $isAr ? 'نستخدم ملفات تعريف الارتباط لتحسين تجربتك على موقعنا. تشمل أنواع الكوكيز المستخدمة:' : 'We use cookies to enhance your experience on our website. Types of cookies used include:' }}</p>
                <div style="display:flex;flex-direction:column;gap:10px;margin:16px 0">
                    <div style="display:flex;align-items:start;gap:12px;padding:12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px">
                        <i aria-hidden="true" class="fas fa-check-circle" style="color:#059669;font-size:1rem;margin-top:3px"></i>
                        <div><strong style="display:block;font-size:0.9rem;color:var(--color-heading)">{{ $isAr ? 'كوكيز ضرورية' : 'Essential Cookies' }}</strong><span style="font-size:0.85rem">{{ $isAr ? 'مطلوبة لعمل الموقع بشكل صحيح (جلسة المستخدم، سلة التبرع، تفضيلات اللغة).' : 'Required for proper website functionality (user session, donation cart, language preferences).' }}</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:12px;padding:12px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px">
                        <i aria-hidden="true" class="fas fa-chart-pie" style="color:var(--color-primary);font-size:1rem;margin-top:3px"></i>
                        <div><strong style="display:block;font-size:0.9rem;color:var(--color-heading)">{{ $isAr ? 'كوكيز تحليلية' : 'Analytics Cookies' }}</strong><span style="font-size:0.85rem">{{ $isAr ? 'تساعدنا في فهم كيفية استخدام الزوار للموقع (Google Analytics) لتحسين المحتوى.' : 'Help us understand how visitors use the website (Google Analytics) to improve content.' }}</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:12px;padding:12px;background:#fefce8;border:1px solid #fde68a;border-radius:8px">
                        <i aria-hidden="true" class="fas fa-cog" style="color:#ca8a04;font-size:1rem;margin-top:3px"></i>
                        <div><strong style="display:block;font-size:0.9rem;color:var(--color-heading)">{{ $isAr ? 'كوكيز وظيفية' : 'Functional Cookies' }}</strong><span style="font-size:0.85rem">{{ $isAr ? 'تذكر تفضيلاتك مثل الوضع المظلم أو حجم الخط لتوفير تجربة مخصصة.' : 'Remember your preferences like dark mode or font size for a personalized experience.' }}</span></div>
                    </div>
                </div>
                <p>{{ $isAr ? 'يمكنك التحكم في الكوكيز من خلال إعدادات متصفحك. لاحظ أن تعطيل بعض الكوكيز قد يؤثر على وظائف الموقع.' : 'You can control cookies through your browser settings. Note that disabling some cookies may affect website functionality.' }}</p>
            </div>
        </article>

        {{-- 6. Payment Security --}}
        <article id="payment" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">6</span>
                {{ $isAr ? 'أمان المدفوعات' : 'Payment Security' }}
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>{{ $isAr ? 'نأخذ أمان معاملاتك المالية على محمل الجد. نطبق التدابير التالية:' : 'We take the security of your financial transactions seriously. We implement the following measures:' }}</p>
                    <div class="pp-grid-3" style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin:16px 0">
                    <div style="text-align:center;padding:20px;background:var(--color-bg);border-radius:12px;border:1px solid var(--color-border)">
                        <i aria-hidden="true" class="fas fa-lock" style="font-size:2rem;color:var(--color-primary);margin-bottom:10px;display:block"></i>
                        <strong style="font-size:0.9rem;display:block;color:var(--color-heading)">{{ $isAr ? 'تشفير SSL/TLS' : 'SSL/TLS Encryption' }}</strong>
                        <span style="font-size:0.8rem;color:var(--color-text-muted)">{{ $isAr ? 'جميع البيانات مشفرة أثناء النقل' : 'All data encrypted in transit' }}</span>
                    </div>
                    <div style="text-align:center;padding:20px;background:var(--color-bg);border-radius:12px;border:1px solid var(--color-border)">
                        <i aria-hidden="true" class="fas fa-shield-alt" style="font-size:2rem;color:var(--color-primary);margin-bottom:10px;display:block"></i>
                        <strong style="font-size:0.9rem;display:block;color:var(--color-heading)">PCI DSS</strong>
                        <span style="font-size:0.8rem;color:var(--color-text-muted)">{{ $isAr ? 'متوافق مع معايير أمن بيانات بطاقات الدفع' : 'Compliant with card data security standards' }}</span>
                    </div>
                    <div style="text-align:center;padding:20px;background:var(--color-bg);border-radius:12px;border:1px solid var(--color-border)">
                        <i aria-hidden="true" class="fas fa-fingerprint" style="font-size:2rem;color:var(--color-primary);margin-bottom:10px;display:block"></i>
                        <strong style="font-size:0.9rem;display:block;color:var(--color-heading)">Tokenization</strong>
                        <span style="font-size:0.8rem;color:var(--color-text-muted)">{{ $isAr ? 'لا نخزّن بيانات البطاقة على خوادمنا' : 'We do not store card data on our servers' }}</span>
                    </div>
                </div>
                <p>{{ $isAr ? 'نستخدم بوابات دفع معتمدة وموثوقة (Stripe, PayPal) ومعالجة العملات المشفرة. جميع المعاملات تتم عبر خوادم آمنة ومعتمدة من هيئات أمن المعلومات الدولية.' : 'We use trusted, certified payment gateways (Stripe, PayPal) and cryptocurrency processing. All transactions occur over secure servers certified by international information security authorities.' }}</p>
            </div>
        </article>

        {{-- 7. Your Rights --}}
        <article id="rights" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">7</span>
                {{ $isAr ? 'حقوقك' : 'Your Rights' }}
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>{{ $isAr ? 'لديك الحقوق التالية فيما يتعلق ببياناتك الشخصية:' : 'You have the following rights regarding your personal data:' }}</p>
                <div class="pp-grid-2" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin:16px 0">
                    <div style="display:flex;align-items:start;gap:10px;padding:12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px">
                        <i aria-hidden="true" class="fas fa-eye" style="color:#059669;font-size:1rem;margin-top:3px"></i>
                        <div><strong style="font-size:0.88rem;display:block;color:var(--color-heading)">{{ $isAr ? 'الاطلاع' : 'Access' }}</strong><span style="font-size:0.82rem">{{ $isAr ? 'طلب نسخة من بياناتك الشخصية المحفوظة لدينا.' : 'Request a copy of your personal data stored with us.' }}</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:10px;padding:12px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px">
                        <i aria-hidden="true" class="fas fa-edit" style="color:var(--color-primary);font-size:1rem;margin-top:3px"></i>
                        <div><strong style="font-size:0.88rem;display:block;color:var(--color-heading)">{{ $isAr ? 'التصحيح' : 'Rectification' }}</strong><span style="font-size:0.82rem">{{ $isAr ? 'طلب تصحيح أي بيانات غير دقيقة أو غير مكتملة.' : 'Request correction of inaccurate or incomplete data.' }}</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:10px;padding:12px;background:#fef2f2;border:1px solid #fca5a5;border-radius:8px">
                        <i aria-hidden="true" class="fas fa-trash-alt" style="color:#dc2626;font-size:1rem;margin-top:3px"></i>
                        <div><strong style="font-size:0.88rem;display:block;color:var(--color-heading)">{{ $isAr ? 'الحذف' : 'Erasure' }}</strong><span style="font-size:0.82rem">{{ $isAr ? 'طلب حذف بياناتك الشخصية (مع مراعاة الالتزامات القانونية).' : 'Request deletion of your personal data (subject to legal obligations).' }}</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:10px;padding:12px;background:#fefce8;border:1px solid #fde68a;border-radius:8px">
                        <i aria-hidden="true" class="fas fa-ban" style="color:#ca8a04;font-size:1rem;margin-top:3px"></i>
                        <div><strong style="font-size:0.88rem;display:block;color:var(--color-heading)">{{ $isAr ? 'الاعتراض' : 'Objection' }}</strong><span style="font-size:0.82rem">{{ $isAr ? 'الاعتراض على معالجة بياناتك لأغراض تسويقية.' : 'Object to processing of your data for marketing purposes.' }}</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:10px;padding:12px;background:#f5f3ff;border:1px solid #c4b5fd;border-radius:8px">
                        <i aria-hidden="true" class="fas fa-download" style="color:#7c3aed;font-size:1rem;margin-top:3px"></i>
                        <div><strong style="font-size:0.88rem;display:block;color:var(--color-heading)">{{ $isAr ? 'النقل' : 'Portability' }}</strong><span style="font-size:0.82rem">{{ $isAr ? 'طلب نقل بياناتك إلى مزود خدمة آخر بصيغة مقروءة.' : 'Request transfer of your data to another service provider.' }}</span></div>
                    </div>
                    <div style="display:flex;align-items:start;gap:10px;padding:12px;background:#fff7ed;border:1px solid #fed7aa;border-radius:8px">
                        <i aria-hidden="true" class="fas fa-pause-circle" style="color:#ea580c;font-size:1rem;margin-top:3px"></i>
                        <div><strong style="font-size:0.88rem;display:block;color:var(--color-heading)">{{ $isAr ? 'التقييد' : 'Restriction' }}</strong><span style="font-size:0.82rem">{{ $isAr ? 'طلب تقييد معالجة بياناتك في ظروف معينة.' : 'Request restriction of processing your data under certain circumstances.' }}</span></div>
                    </div>
                </div>
                <p>{{ $isAr ? 'لممارسة أي من هذه الحقوق، يرجى التواصل معنا عبر البريد الإلكتروني أو نموذج الاتصال في الموقع.' : 'To exercise any of these rights, please contact us via email or the contact form on the website.' }}</p>
            </div>
        </article>

        {{-- 8. Data Retention --}}
        <article id="retention" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">8</span>
                {{ $isAr ? 'الاحتفاظ بالبيانات' : 'Data Retention' }}
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>{{ $isAr ? 'نحتفظ ببياناتك الشخصية فقط للمدة اللازمة لتحقيق الأغراض الموضحة في هذه السياسة:' : 'We retain your personal data only for as long as necessary to fulfill the purposes outlined in this policy:' }}</p>
                <ul style="margin:12px 0;padding-inline-start:20px">
                    <li style="margin-bottom:8px"><strong style="color:var(--color-heading)">{{ $isAr ? 'بيانات التبرع:' : 'Donation Data:' }}</strong> {{ $isAr ? 'نحتفظ بها لمدة 7 سنوات كحد أدنى لأغراض الامتثال الضريبي والمالي.' : 'Retained for a minimum of 7 years for tax and financial compliance.' }}</li>
                    <li style="margin-bottom:8px"><strong style="color:var(--color-heading)">{{ $isAr ? 'بيانات الحساب:' : 'Account Data:' }}</strong> {{ $isAr ? 'نحتفظ بها طوال فترة نشاط الحساب، وتحذف تلقائياً بعد 3 سنوات من عدم النشاط.' : 'Retained throughout account activity, automatically deleted after 3 years of inactivity.' }}</li>
                    <li style="margin-bottom:8px"><strong style="color:var(--color-heading)">{{ $isAr ? 'بيانات الاستخدام:' : 'Usage Data:' }}</strong> {{ $isAr ? 'نحتفظ بها لمدة سنة واحدة لأغراض التحليل وتحسين الخدمة.' : 'Retained for one year for analysis and service improvement.' }}</li>
                    <li style="margin-bottom:8px"><strong style="color:var(--color-heading)">{{ $isAr ? 'سجلات الأمان:' : 'Security Logs:' }}</strong> {{ $isAr ? 'نحتفظ بها لمدة 6 أشهر لأغراض مكافحة الاحتيال.' : 'Retained for 6 months for fraud prevention purposes.' }}</li>
                </ul>
                <p>{{ $isAr ? 'بعد انتهاء فترة الاحتفاظ، يتم حذف البيانات بشكل آمن أو إخفاء هويتها بحيث لا يمكن ربطها بك.' : 'After the retention period ends, data is securely deleted or anonymized so it can no longer be linked to you.' }}</p>
            </div>
        </article>

        {{-- 9. Children's Privacy --}}
        <article id="children" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">9</span>
                {{ $isAr ? 'خصوصية الأطفال' : "Children's Privacy" }}
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>{{ $isAr ? 'موقعنا مصمم للأشخاص الذين تبلغ أعمارهم 18 عاماً أو أكثر. لا نجمع عن علم بيانات شخصية من الأطفال دون سن 18 عاماً.' : 'Our website is designed for individuals aged 18 years or older. We do not knowingly collect personal data from children under 18.' }}</p>
                <p>{{ $isAr ? 'إذا علمنا أننا جمعنا بيانات شخصية من طفل دون سن 18 عاماً دون موافقة الوالدين، سنحذف هذه البيانات فوراً. إذا كنت تعتقد أن طفلك قد قدم لنا بيانات شخصية، يرجى التواصل معنا فوراً.' : 'If we learn that we have collected personal data from a child under 18 without parental consent, we will delete that data immediately. If you believe your child has provided us with personal data, please contact us immediately.' }}</p>
            </div>
        </article>

        {{-- 10. Changes to Policy --}}
        <article id="changes" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">10</span>
                {{ $isAr ? 'التغييرات على سياسة الخصوصية' : 'Changes to This Policy' }}
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>{{ $isAr ? 'نحتفظ بالحق في تحديث سياسة الخصوصية هذه في أي وقت. سيتم نشر أي تغييرات على هذه الصفحة مع تحديث تاريخ "آخر تعديل" في أعلى الصفحة.' : 'We reserve the right to update this Privacy Policy at any time. Any changes will be posted on this page with the "Last Updated" date at the top.' }}</p>
                <p>{{ $isAr ? 'في حالة التغييرات الجوهرية، سنقوم بإخطارك عبر البريد الإلكتروني أو من خلال إشعار بارز على موقعنا قبل أن تصبح التغييرات سارية المفعول.' : 'In the case of material changes, we will notify you via email or through a prominent notice on our website before the changes take effect.' }}</p>
                <p>{{ $isAr ? 'ننصحك بمراجعة هذه الصفحة دورياً للبقاء على اطلاع بأحدث ممارساتنا في حماية الخصوصية.' : 'We encourage you to review this page periodically to stay informed about our latest privacy practices.' }}</p>
            </div>
        </article>

        {{-- 11. Contact --}}
        <article id="contact" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1.5rem;margin-bottom:1.25rem">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--color-heading);margin-bottom:12px;display:flex;align-items:center;gap:10px">
                <span style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0">11</span>
                {{ $isAr ? 'التواصل معنا' : 'Contact Us' }}
            </h2>
            <div style="color:var(--color-text);line-height:1.8;font-size:0.95rem">
                <p>{{ $isAr ? 'إذا كانت لديك أي أسئلة أو استفسارات حول سياسة الخصوصية هذه أو ممارساتنا في مجال حماية البيانات، يرجى التواصل معنا:' : 'If you have any questions or concerns about this Privacy Policy or our data protection practices, please contact us:' }}</p>
                <div class="pp-grid-3" style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin:16px 0">
                    @if($s->email)
                    <div style="text-align:center;padding:20px;background:var(--color-bg);border-radius:12px;border:1px solid var(--color-border)">
                        <i aria-hidden="true" class="fas fa-envelope" style="font-size:1.5rem;color:var(--color-primary);margin-bottom:8px;display:block"></i>
                        <strong style="font-size:0.85rem;display:block;color:var(--color-heading)">{{ $isAr ? 'البريد الإلكتروني' : 'Email' }}</strong>
                        <a href="mailto:{{ $s->email }}" style="font-size:0.82rem;color:var(--color-primary);text-decoration:none">{{ $s->email }}</a>
                    </div>
                    @endif
                    @if($s->phone)
                    <div style="text-align:center;padding:20px;background:var(--color-bg);border-radius:12px;border:1px solid var(--color-border)">
                        <i aria-hidden="true" class="fas fa-phone" style="font-size:1.5rem;color:var(--color-primary);margin-bottom:8px;display:block"></i>
                        <strong style="font-size:0.85rem;display:block;color:var(--color-heading)">{{ $isAr ? 'الهاتف' : 'Phone' }}</strong>
                        <a href="tel:{{ preg_replace('/\s+/', '', $s->phone) }}" style="font-size:0.82rem;color:var(--color-primary);text-decoration:none">{{ $s->phone }}</a>
                    </div>
                    @endif
                    @if($s->whatsapp)
                    <div style="text-align:center;padding:20px;background:var(--color-bg);border-radius:12px;border:1px solid var(--color-border)">
                        <i aria-hidden="true" class="fab fa-whatsapp" style="font-size:1.5rem;color:var(--color-primary);margin-bottom:8px;display:block"></i>
                        <strong style="font-size:0.85rem;display:block;color:var(--color-heading)">{{ $isAr ? 'واتساب' : 'WhatsApp' }}</strong>
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $s->whatsapp) }}" target="_blank" rel="noopener" style="font-size:0.82rem;color:var(--color-primary);text-decoration:none">{{ $s->whatsapp }}</a>
                    </div>
                    @endif
                </div>
            </div>
        </article>
        
        <p style="text-align:center;color:var(--color-text-muted);font-size:0.82rem;margin-top:2rem">{{ $isAr ? 'آخر تحديث: ' : 'Last Updated: ' }}{{ date('Y-m-d') }}</p>
    </div>
</section>
@endsection
