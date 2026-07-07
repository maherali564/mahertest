<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) return;

        // ─── Categories ───────────────────────────────────
        $categories = [
            [
                'slug' => 'humanitarian',
                'name' => ['ar' => 'عمل إنساني', 'en' => 'Humanitarian Work'],
                'description' => ['ar' => 'أخبار وتقارير عن العمل الإنساني', 'en' => 'News and reports about humanitarian work'],
                'color' => '#0d6b4f',
            ],
            [
                'slug' => 'emergency',
                'name' => ['ar' => 'حملات عاجلة', 'en' => 'Emergency Campaigns'],
                'description' => ['ar' => 'حملات الإغاثة العاجلة حول العالم', 'en' => 'Urgent relief campaigns around the world'],
                'color' => '#c62828',
            ],
            [
                'slug' => 'stories',
                'name' => ['ar' => 'قصص أمل', 'en' => 'Stories of Hope'],
                'description' => ['ar' => 'قصص ملهمة من المستفيدين', 'en' => 'Inspiring stories from beneficiaries'],
                'color' => '#f59e0b',
            ],
            [
                'slug' => 'projects',
                'name' => ['ar' => 'مشاريع', 'en' => 'Projects'],
                'description' => ['ar' => 'تفاصيل مشاريعنا التنموية', 'en' => 'Details of our development projects'],
                'color' => '#2563eb',
            ],
            [
                'slug' => 'reports',
                'name' => ['ar' => 'تقارير', 'en' => 'Reports'],
                'description' => ['ar' => 'تقارير سنوية ومالية', 'en' => 'Annual and financial reports'],
                'color' => '#7c3aed',
            ],
            [
                'slug' => 'volunteering',
                'name' => ['ar' => 'تطوع', 'en' => 'Volunteering'],
                'description' => ['ar' => 'فرص تطوعية وأخبار المتطوعين', 'en' => 'Volunteer opportunities and news'],
                'color' => '#0891b2',
            ],
        ];

        foreach ($categories as $data) {
            Category::create($data);
        }
        $this->command->info(count($categories) . ' categories seeded.');

        // ─── Tags ─────────────────────────────────────────
        $tags = [
            ['slug' => 'غزة', 'name' => ['ar' => 'غزة', 'en' => 'Gaza']],
            ['slug' => 'سوريا', 'name' => ['ar' => 'سوريا', 'en' => 'Syria']],
            ['slug' => 'اليمن', 'name' => ['ar' => 'اليمن', 'en' => 'Yemen']],
            ['slug' => 'السودان', 'name' => ['ar' => 'السودان', 'en' => 'Sudan']],
            ['slug' => 'فلسطين', 'name' => ['ar' => 'فلسطين', 'en' => 'Palestine']],
            ['slug' => 'زلزال', 'name' => ['ar' => 'زلزال', 'en' => 'Earthquake']],
            ['slug' => 'فيضانات', 'name' => ['ar' => 'فيضانات', 'en' => 'Floods']],
            ['slug' => 'مياه', 'name' => ['ar' => 'مياه', 'en' => 'Water']],
            ['slug' => 'تعليم', 'name' => ['ar' => 'تعليم', 'en' => 'Education']],
            ['slug' => 'صحة', 'name' => ['ar' => 'صحة', 'en' => 'Health']],
            ['slug' => 'رمضان', 'name' => ['ar' => 'رمضان', 'en' => 'Ramadan']],
            ['slug' => 'أيتام', 'name' => ['ar' => 'أيتام', 'en' => 'Orphans']],
        ];

        foreach ($tags as $data) {
            Tag::create($data);
        }
        $this->command->info(count($tags) . ' tags seeded.');

        // ─── Posts ─────────────────────────────────────────
        $posts = [
            [
                'slug' => 'gaza-humanitarian-crisis-2026',
                'title' => ['ar' => 'الأزمة الإنسانية في غزة تتفاقم: نداء عاجل لدعم آلاف العائلات', 'en' => 'Gaza Humanitarian Crisis Worsens: Urgent Appeal to Support Thousands of Families'],
                'excerpt' => ['ar' => 'مع استمرار الحرب والحصار، يعاني أكثر من 2 مليون فلسطيني في غزة من نقص حاد في الغذاء والماء والدواء.', 'en' => 'With the ongoing war and siege, over 2 million Palestinians in Gaza suffer from severe shortages of food, water, and medicine.'],
                'content' => ['ar' => 'تفاقمت الأزمة الإنسانية في قطاع غزة بشكل غير مسبوق، حيث يعيش أكثر من 2.3 مليون شخص في ظروف كارثية. تواصل فرق ساهم جهودها الإغاثية رغم التحديات الكبيرة، حيث وزعت آلاف السلال الغذائية والطرود الصحية على العائلات النازحة.\n\n\nتعمل فرقنا الميدانية على مدار الساعة لتوفير الاحتياجات الأساسية للأسر المتضررة، بما في ذلك توزيع مياه الشرب النظيفة والخبز الطازج والأدوية المنقذة للحياة. نناشد جميع المحسنين بدعم حملتنا العاجلة لإنقاذ الأرواح.', 'en' => 'The humanitarian crisis in the Gaza Strip has worsened unprecedentedly, with over 2.3 million people living in catastrophic conditions. Sahem teams continue their relief efforts despite major challenges, distributing thousands of food parcels and health kits to displaced families.\n\n\nOur field teams work around the clock to provide basic necessities for affected families, including clean drinking water, fresh bread, and life-saving medicines. We appeal to all donors to support our urgent campaign to save lives.'],
                'status' => 'published',
                'is_featured' => true,
                'category_slug' => 'emergency',
                'tag_slugs' => ['غزة', 'فلسطين'],
            ],
            [
                'slug' => 'clean-water-yemen',
                'title' => ['ar' => 'آبار المياه تنقذ حياة آلاف اليمنيين', 'en' => 'Water Wells Save Thousands of Yemeni Lives'],
                'excerpt' => ['ar' => 'مشروع حفر آبار المياه في اليمن يوفر مياهاً نظيفة لأكثر من 50,000 شخص.', 'en' => 'The water well drilling project in Yemen provides clean water to over 50,000 people.'],
                'content' => ['ar' => 'في إطار جهود ساهم المتواصلة للتخفيف من معاناة الشعب اليمني، تم حفر 15 بئراً جديداً في المناطق الريفية التي تعاني من أشد درجات العطش. يستفيد من هذه الآبار أكثر من 50 ألف شخص، معظمهم من النساء والأطفال.\n\n\nتعد أزمة المياه في اليمن واحدة من أسوأ الأزمات الإنسانية في العالم، حيث يفتقر أكثر من 18 مليون شخص إلى مياه الشرب الآمنة. تساهم هذه الآبار في تقليل الأمراض المنقولة بالمياه وتحسين الظروف المعيشية للمجتمعات المحلية.', 'en' => 'As part of Sahems ongoing efforts to alleviate the suffering of the Yemeni people, 15 new wells have been drilled in rural areas suffering from extreme thirst. These wells benefit over 50,000 people, mostly women and children.\n\n\nThe water crisis in Yemen is one of the worst humanitarian crises in the world, with over 18 million people lacking safe drinking water. These wells help reduce water-borne diseases and improve living conditions for local communities.'],
                'status' => 'published',
                'is_featured' => true,
                'category_slug' => 'projects',
                'tag_slugs' => ['اليمن', 'مياه'],
            ],
            [
                'slug' => 'ramadan-food-baskets',
                'title' => ['ar' => 'مشروع السلال الغذائية الرمضانية يصل إلى 10 آلاف أسرة', 'en' => 'Ramadan Food Baskets Project Reaches 10,000 Families'],
                'excerpt' => ['ar' => 'في شهر رمضان المبارك، وزعت ساهم آلاف السلال الغذائية على الأسر المحتاجة في 5 دول.', 'en' => 'During the holy month of Ramadan, Sahem distributed thousands of food baskets to needy families in 5 countries.'],
                'content' => ['ar' => 'مع حلول شهر رمضان المبارك، أطلقت ساهم مشروع السلال الغذائية الذي استهدف الأسر الأكثر احتياجاً في غزة وسوريا واليمن والسودان ولبنان. تضمنت السلال المواد الغذائية الأساسية التي تكفي الأسرة لمدة شهر كامل.\n\n\nبلغ عدد المستفيدين من المشروع أكثر من 10 آلاف أسرة، بتكلفة إجمالية تجاوزت 3 ملايين ريال سعودي. نشكر جميع المتبرعين الذين ساهموا في إدخال الفرحة إلى قلوب المحتاجين في هذا الشهر الفضيل.', 'en' => 'With the arrival of the holy month of Ramadan, Sahem launched the food baskets project targeting the neediest families in Gaza, Syria, Yemen, Sudan, and Lebanon. The baskets contained basic food items sufficient for a family for an entire month.\n\n\nThe number of beneficiaries exceeded 10,000 families, with a total cost exceeding 3 million Saudi Riyals. We thank all donors who contributed to bringing joy to the hearts of those in need during this blessed month.'],
                'status' => 'published',
                'is_featured' => false,
                'category_slug' => 'humanitarian',
                'tag_slugs' => ['رمضان', 'السودان', 'سوريا'],
            ],
            [
                'slug' => 'syria-earthquake-anniversary',
                'title' => ['ar' => 'عام على زلزال سوريا: ماذا قدمنا للمتضررين؟', 'en' => 'One Year Since the Syria Earthquake: What We Provided for the Victims?'],
                'excerpt' => ['ar' => 'تقرير شامل عن جهود ساهم في إغاثة متضرري زلزال سوريا خلال عام.', 'en' => 'A comprehensive report on Sahems efforts in relieving Syria earthquake victims over one year.'],
                'content' => ['ar' => 'بعد مرور عام على الزلزال المدمر الذي ضرب شمال سوريا وتركيا، تستعرض ساهم حصيلة جهودها الإغاثية. منذ اللحظات الأولى للكارثة، كانت فرقنا على الأرض لتقديم المساعدات العاجلة.\n\n\nشملت جهودنا: توزيع أكثر من 50 ألف سلة غذائية، توفير مأوى لـ 15 ألف نازح، تجهيز 3 عيادات متنقلة، وتوزيع آلاف البطانيات والمساعدات الشتوية. لا تزال جهود الإغاثة مستمرة دعماً للمتضررين.', 'en' => 'One year after the devastating earthquake that struck northern Syria and Turkey, Sahem reviews its relief efforts. From the first moments of the disaster, our teams were on the ground providing emergency aid.\n\n\nOur efforts included: distributing over 50,000 food baskets, providing shelter for 15,000 displaced people, equipping 3 mobile clinics, and distributing thousands of blankets and winter supplies. Relief efforts continue to support the affected.'],
                'status' => 'published',
                'is_featured' => true,
                'category_slug' => 'reports',
                'tag_slugs' => ['سوريا', 'زلزال'],
            ],
            [
                'slug' => 'orphan-sponsorship-impact',
                'title' => ['ar' => 'كفالة الأيتام: قصص نجاح وتحديات', 'en' => 'Orphan Sponsorship: Success Stories and Challenges'],
                'excerpt' => ['ar' => 'تعرف على قصص أيتام تغيرت حياتهم بفضل كفالتكم.', 'en' => 'Discover stories of orphans whose lives changed thanks to your sponsorship.'],
                'content' => ['ar' => 'برنامج كفالة الأيتام في ساهم هو أحد أهم برامجنا، حيث نكفل حالياً أكثر من 5 آلاف يتيم في 8 دول. يقدم البرنامج دعماً شاملاً يشمل التعليم والرعاية الصحية والمسكن.\n\n\nنستعرض في هذا التقرير قصصاً ملهمة لأيتام تفوقوا في دراستهم وحققوا أحلامهم بفضل دعمكم المستمر. محمد الذي أصبح طبيباً، وسارة التي تدرس التمريض، وأحمد المهندس المعماري، كلهم كانوا يوماً أيتاماً في برنامجنا.', 'en' => 'The orphan sponsorship program at Sahem is one of our most important programs, currently sponsoring over 5,000 orphans in 8 countries. The program provides comprehensive support including education, healthcare, and housing.\n\n\nIn this report, we present inspiring stories of orphans who excelled in their studies and achieved their dreams thanks to your continued support. Mohammed who became a doctor, Sara studying nursing, and Ahmed the architect - all were once orphans in our program.'],
                'status' => 'published',
                'is_featured' => false,
                'category_slug' => 'stories',
                'tag_slugs' => ['أيتام', 'تعليم'],
            ],
            [
                'slug' => 'sudan-refugees-update',
                'title' => ['ar' => 'أزمة اللاجئين السودانيين: جهود الإغاثة تتسارع', 'en' => 'Sudan Refugee Crisis: Relief Efforts Accelerate'],
                'excerpt' => ['ar' => 'ساهم تطلق حملة إغاثة موسعة للاجئين السودانيين في تشاد وجنوب السودان.', 'en' => 'Sahem launches an expanded relief campaign for Sudanese refugees in Chad and South Sudan.'],
                'content' => ['ar' => 'مع استمرار النزاع في السودان، يواجه ملايين اللاجئين والنازحين أوضاعاً إنسانية صعبة. أطلقت ساهم حملة إغاثة موسعة تستهدف توفير الغذاء والمأوى والرعاية الصحية لأكثر من 100 ألف لاجئ سوداني.\n\n\nتعمل فرقنا في مخيمات اللاجئين على الحدود التشادية والسودانية، حيث تقدم المساعدات الطارئة للأسر التي فرت من القتال. تشمل المساعدات السلال الغذائية، الفرشات، البطانيات، والأدوية الأساسية.', 'en' => 'As the conflict in Sudan continues, millions of refugees and displaced people face difficult humanitarian conditions. Sahem launched an expanded relief campaign targeting food, shelter, and healthcare for over 100,000 Sudanese refugees.\n\n\nOur teams work in refugee camps on the Chadian-Sudanese border, providing emergency aid to families who fled the fighting. Aid includes food baskets, mattresses, blankets, and essential medicines.'],
                'status' => 'published',
                'is_featured' => true,
                'category_slug' => 'emergency',
                'tag_slugs' => ['السودان'],
            ],
            [
                'slug' => 'mobile-clinic-success',
                'title' => ['ar' => 'العيادات المتنقلة تعالج 100 ألف مريض في عام', 'en' => 'Mobile Clinics Treat 100,000 Patients in One Year'],
                'excerpt' => ['ar' => 'إنجاز جديد للعيادات المتنقلة في المناطق النائية.', 'en' => 'A new achievement for mobile clinics in remote areas.'],
                'content' => ['ar' => 'حققت العيادات الطبية المتنقلة التابعة لساهم إنجازاً كبيراً بعلاج أكثر من 100 ألف مريض خلال عام واحد. تعمل هذه العيادات في المناطق النائية التي تفتقر إلى الخدمات الصحية الأساسية.\n\n\nتنتشر العيادات في 5 دول: غزة وسوريا واليمن والسودان والصومال. تضم كل عيادة فريقاً طبياً متكاملاً يقدم خدمات العلاج العام والأطفال والنساء الحوامل، بالإضافة إلى التوعية الصحية.', 'en' => 'Sahems mobile medical clinics achieved a major milestone by treating over 100,000 patients in one year. These clinics operate in remote areas lacking basic health services.\n\n\nThe clinics are spread across 5 countries: Gaza, Syria, Yemen, Sudan, and Somalia. Each clinic has a comprehensive medical team providing general treatment, pediatric care, prenatal services, and health awareness.'],
                'status' => 'published',
                'is_featured' => false,
                'category_slug' => 'projects',
                'tag_slugs' => ['صحة', 'السودان', 'سوريا'],
            ],
            [
                'slug' => 'winter-aid-displaced',
                'title' => ['ar' => 'حملة الشتاء الدافئ تصل إلى 20 ألف نازح', 'en' => 'Warm Winter Campaign Reaches 20,000 Displaced People'],
                'excerpt' => ['ar' => 'توزيع مساعدات شتوية للنازحين في المخيمات.', 'en' => 'Winter aid distribution for displaced people in camps.'],
                'content' => ['ar' => 'مع حلول فصل الشتاء وانخفاض درجات الحرارة إلى ما دون الصفر في بعض المناطق، أطلقت ساهم حملة الشتاء الدافئ لتوزيع المساعدات الشتوية على النازحين في المخيمات.\n\n\nشملت المساعدات بطانيات ثقيلة، ملابس شتوية، مدافئ، ووقود للتدفئة. استفاد من الحملة أكثر من 20 ألف نازح في مخيمات شمال سوريا وغزة والسودان.', 'en' => 'With winter arriving and temperatures dropping below zero in some areas, Sahem launched the Warm Winter campaign to distribute winter aid to displaced people in camps.\n\n\nAid included heavy blankets, winter clothes, heaters, and heating fuel. The campaign benefited over 20,000 displaced people in camps in northern Syria, Gaza, and Sudan.'],
                'status' => 'published',
                'is_featured' => false,
                'category_slug' => 'humanitarian',
                'tag_slugs' => ['سوريا', 'غزة', 'السودان'],
            ],
            [
                'slug' => 'education-right-gaza',
                'title' => ['ar' => 'الحق في التعليم: قصص أطفال غزة يعودون للمدارس', 'en' => 'Right to Education: Stories of Gaza Children Returning to School'],
                'excerpt' => ['ar' => 'برنامج دعم التعليم يعيد آلاف الأطفال إلى مقاعد الدراسة.', 'en' => 'The education support program returns thousands of children to school.'],
                'content' => ['ar' => 'رغم الدمار الهائل الذي لحق بالقطاع التعليمي في غزة، تواصل ساهم جهودها لإعادة الأطفال إلى المدارس. من خلال برنامج دعم التعليم، تم إعادة تأهيل 15 مدرسة وتوزيع آلاف الحقيبة المدرسية.\n\n\nنستعرض في هذا التقرير قصصاً لأطفال استعادوا حلمهم في التعليم رغم الظروف الصعبة. قصص تثبت أن الأمل لا يموت وأن التعليم هو الطريق الوحيد لمستقبل أفضل.', 'en' => 'Despite the massive destruction of the education sector in Gaza, Sahem continues its efforts to return children to school. Through the education support program, 15 schools were rehabilitated and thousands of school bags were distributed.\n\n\nIn this report, we share stories of children who regained their dream of education despite difficult circumstances. Stories proving that hope never dies and that education is the only path to a better future.'],
                'status' => 'published',
                'is_featured' => false,
                'category_slug' => 'stories',
                'tag_slugs' => ['غزة', 'فلسطين', 'تعليم'],
            ],
            [
                'slug' => 'flood-relief-pakistan',
                'title' => ['ar' => 'إغاثة متضرري الفيضانات في باكستان', 'en' => 'Flood Relief in Pakistan'],
                'excerpt' => ['ar' => 'حملة إغاثة عاجلة لمتضرري الفيضانات في إقليم السند.', 'en' => 'Emergency relief campaign for flood victims in Sindh province.'],
                'content' => ['ar' => 'تتعرض باكستان لفيضانات موسمية عنيفة تخلف وراءها دماراً هائلاً وخسائر بشرية. أطلقت ساهم حملة إغاثة عاجلة بالتعاون مع شركاء محليين في إقليم السند.\n\n\nتتضمن المساعدات توزيع الخيام والمواد الغذائية والمياه النظيفة والأدوية. تعمل فرقنا الميدانية في المناطق الأكثر تضرراً لتقييم الاحتياجات وتقديم الدعم اللازم للأسر المنكوبة.', 'en' => 'Pakistan experiences severe seasonal floods leaving massive destruction and human losses. Sahem launched an urgent relief campaign in cooperation with local partners in Sindh province.\n\n\nAid includes distributing tents, food supplies, clean water, and medicines. Our field teams work in the most affected areas to assess needs and provide necessary support to affected families.'],
                'status' => 'published',
                'is_featured' => false,
                'category_slug' => 'emergency',
                'tag_slugs' => ['فيضانات'],
            ],
        ];

        $categories = Category::all()->keyBy('slug');
        $tags = Tag::all()->keyBy('slug');

        foreach ($posts as $i => $data) {
            $tagSlugs = $data['tag_slugs'];
            $categorySlug = $data['category_slug'];
            unset($data['tag_slugs'], $data['category_slug']);

            $post = Post::create($data + [
                'user_id' => $user->id,
                'category_id' => $categories[$categorySlug]?->id,
                'published_at' => now()->subDays(10 - $i),
                'views' => rand(100, 5000),
            ]);

            $tagIds = [];
            foreach ($tagSlugs as $slug) {
                if (isset($tags[$slug])) {
                    $tagIds[] = $tags[$slug]->id;
                }
            }
            if ($tagIds) {
                $post->tags()->attach($tagIds);
            }
        }

        $this->command->info(count($posts) . ' posts seeded.');
    }
}
