<?php

namespace Database\Seeders;

use App\Models\EmergencyCampaign;
use App\Models\Project;
use App\Models\Story;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        Project::query()->delete();
        Story::query()->delete();
        EmergencyCampaign::withTrashed()->forceDelete();

        $src = 'D:\ساهم للاغاثة\مجلد  ساهم للاغاثة';
        $srcAdham = 'D:\ساهم للاغاثة\ادهم';
        $disk = Storage::disk('public');

        $pickFiles = function (string $dir, string $ext, int $count) use ($disk): array {
            $files = [];
            $glob = glob("$dir/*.$ext");
            if ($glob === false) $glob = [];
            shuffle($glob);
            foreach (array_slice($glob, 0, $count) as $f) {
                $name = Str::random(20) . ".$ext";
                copy($f, $disk->path("projects/$name"));
                $files[] = "projects/$name";
            }
            return $files;
        };

        $copyOne = function (string $from, string $to) use ($disk): ?string {
            if (!file_exists($from)) return null;
            $name = Str::random(20) . '.' . pathinfo($from, PATHINFO_EXTENSION);
            copy($from, $disk->path($to . '/' . $name));
            return "$to/$name";
        };

        // ─── Projects ───────────────────────────────────────────────
        $projects = [
            [
                'slug' => 'water-wells',
                'title' => ['ar' => 'حفر آبار المياه', 'en' => 'Water Wells Drilling'],
                'description' => ['ar' => 'توفير مياه نظيفة للقرى المحتاجة في أفريقيا واليمن', 'en' => 'Providing clean water to needy villages in Africa and Yemen'],
                'content' => ['ar' => 'مشروع حفر آبار المياه يهدف إلى توفير مصدر مياه آمن ومستدام للمجتمعات التي تعاني من شح المياه. تم حفر أكثر من 50 بئراً في عدة دول.', 'en' => 'The water wells project aims to provide a safe and sustainable water source for communities suffering from water scarcity. Over 50 wells have been drilled in several countries.'],
                'goal_amount' => 500000, 'raised_amount' => 385000,
            ],
            [
                'slug' => 'food-baskets',
                'title' => ['ar' => 'سلال غذائية', 'en' => 'Food Baskets'],
                'description' => ['ar' => 'توزيع سلال غذائية للأسر المتعففة في رمضان', 'en' => 'Distributing food baskets to needy families during Ramadan'],
                'content' => ['ar' => 'مشروع السلال الغذائية الرمضانية يستهدف آلاف الأسر في غزة وسوريا واليمن والسودان.', 'en' => 'The Ramadan food baskets project targets thousands of families in Gaza, Syria, Yemen, and Sudan.'],
                'goal_amount' => 300000, 'raised_amount' => 275000,
            ],
            [
                'slug' => 'orphan-sponsorship',
                'title' => ['ar' => 'كفالة الأيتام', 'en' => 'Orphan Sponsorship'],
                'description' => ['ar' => 'كفالة آلاف الأيتام وتوفير التعليم والرعاية الصحية لهم', 'en' => 'Sponsoring thousands of orphans and providing education and healthcare'],
                'content' => ['ar' => 'برنامج كفالة الأيتام يقدم دعماً شاملاً للأيتام يشمل التعليم والرعاية الصحية والمسكن.', 'en' => 'The orphan sponsorship program provides comprehensive support including education, healthcare, and housing.'],
                'goal_amount' => 600000, 'raised_amount' => 520000,
            ],
            [
                'slug' => 'winter-aid',
                'title' => ['ar' => 'مساعدات شتوية', 'en' => 'Winter Aid'],
                'description' => ['ar' => 'توزيع بطانيات وملابس شتوية ومدافئ للنازحين', 'en' => 'Distributing blankets, winter clothes, and heaters to displaced families'],
                'content' => ['ar' => 'مشروع المساعدات الشتوية يخفف معاناة النازحين في المخيمات خلال فصل الشتاء القارس.', 'en' => 'The winter aid project alleviates the suffering of displaced people in camps during harsh winters.'],
                'goal_amount' => 250000, 'raised_amount' => 190000,
            ],
            [
                'slug' => 'medical-clinic',
                'title' => ['ar' => 'العيادات المتنقلة', 'en' => 'Mobile Clinics'],
                'description' => ['ar' => 'تشغيل عيادات طبية متنقلة في المناطق النائية', 'en' => 'Operating mobile medical clinics in remote areas'],
                'content' => ['ar' => 'العيادات المتنقلة تقدم خدمات طبية مجانية لآلاف المرضى في المناطق التي تفتقر للخدمات الصحية.', 'en' => 'Mobile clinics provide free medical services to thousands of patients in areas lacking healthcare.'],
                'goal_amount' => 400000, 'raised_amount' => 310000,
            ],
            [
                'slug' => 'school-support',
                'title' => ['ar' => 'دعم التعليم', 'en' => 'Education Support'],
                'description' => ['ar' => 'إعادة تأهيل المدارس ودعم الطلاب في المناطق المتضررة', 'en' => 'Rehabilitating schools and supporting students in affected areas'],
                'content' => ['ar' => 'مشروع دعم التعليم يعيد بناء المدارس المدمرة ويوفر القرطاسية والحقيبة المدرسية للطلاب.', 'en' => 'The education support project rebuilds destroyed schools and provides school supplies.'],
                'goal_amount' => 350000, 'raised_amount' => 280000,
            ],
            [
                'slug' => 'iftar-tables',
                'title' => ['ar' => 'موائد الإفطار', 'en' => 'Iftar Tables'],
                'description' => ['ar' => 'إفطار الصائمين في رمضان في عدة دول', 'en' => 'Iftar meals for fasting people during Ramadan in several countries'],
                'content' => ['ar' => 'مشروع موائد الإفطار يقدم وجبات ساخنة يومياً لآلاف الصائمين طوال شهر رمضان.', 'en' => 'The Iftar tables project serves hot daily meals to thousands of fasting people throughout Ramadan.'],
                'goal_amount' => 200000, 'raised_amount' => 195000,
            ],
            [
                'slug' => 'qurbani',
                'title' => ['ar' => 'الأضاحي', 'en' => 'Qurbani Meat Distribution'],
                'description' => ['ar' => 'توزيع لحوم الأضاحي على الأسر المحتاجة في عدة دول', 'en' => 'Distributing Qurbani meat to needy families in several countries'],
                'content' => ['ar' => 'مشروع الأضاحي يوزع آلاف الأضاحي على الأسر الفقيرة والنازحة في أكثر من 10 دول.', 'en' => 'The Qurbani project distributes thousands of sacrifices to poor and displaced families in over 10 countries.'],
                'goal_amount' => 450000, 'raised_amount' => 420000,
            ],
            [
                'slug' => 'shelter-rebuild',
                'title' => ['ar' => 'إعادة الإعمار', 'en' => 'Shelter Rebuilding'],
                'description' => ['ar' => 'إعادة بناء المنازل المدمرة للأسر المتضررة', 'en' => 'Rebuilding destroyed homes for affected families'],
                'content' => ['ar' => 'مشروع إعادة الإعمار يبني ويرمم المنازل المتضررة من الحروب والكوارث الطبيعية.', 'en' => 'The shelter rebuilding project builds and repairs homes damaged by wars and natural disasters.'],
                'goal_amount' => 800000, 'raised_amount' => 540000,
            ],
            [
                'slug' => 'flour-bread',
                'title' => ['ar' => 'مشروع الطحين والخبز', 'en' => 'Flour and Bread Project'],
                'description' => ['ar' => 'توفير الطحين والمخابز للأسر في غزة', 'en' => 'Providing flour and bakeries for families in Gaza'],
                'content' => ['ar' => 'مشروع الطحين والخبز يضمن وصول الخبز الطازج للأسر النازحة في غزة رغم الحصار.', 'en' => 'The flour and bread project ensures fresh bread reaches displaced families in Gaza despite the siege.'],
                'goal_amount' => 550000, 'raised_amount' => 430000,
            ],
        ];

        $projectImgs = [];
        foreach (array_slice(glob("$src/*.jpg"), 0, 10) as $i => $f) {
            $name = 'p_' . ($i + 1) . '.jpg';
            copy($f, $disk->path("projects/$name"));
            $projectImgs[] = "projects/$name";
        }
        $projectVids = [];
        foreach (array_slice(glob("$srcAdham/*.mp4"), 0, 5) as $i => $f) {
            $name = 'pv_' . ($i + 1) . '.mp4';
            copy($f, $disk->path("projects/$name"));
            $projectVids[] = "projects/$name";
        }

        foreach ($projects as $i => $data) {
            Project::create($data + [
                'image' => $projectImgs[$i] ?? null,
                'video_url' => $projectVids[$i] ?? null,
                'video_type' => 'upload',
                'is_active' => true,
                'is_featured' => $i < 3,
                'sort_order' => $i + 1,
            ]);
        }

        $this->command->info('10 projects seeded.');

        // ─── Stories ────────────────────────────────────────────────
        $stories = [
            [
                'title' => ['ar' => 'رحلة الأمل لعمر', 'en' => 'Omars Journey of Hope'],
                'content' => ['ar' => 'عمر طفل يتيم من غزة فقد والديه في الحرب. بفضل كفالتكم استطاع العودة إلى المدرسة وتحقيق حلمه في أن يصبح طبيباً.', 'en' => 'Omar is an orphan from Gaza who lost his parents in the war. Thanks to your sponsorship, he returned to school and is pursuing his dream of becoming a doctor.'],
                'person_name' => ['ar' => 'عمر', 'en' => 'Omar'],
                'age' => 12, 'location' => ['ar' => 'غزة', 'en' => 'Gaza'],
            ],
            [
                'title' => ['ar' => 'أم أحمد تنقذ أطفالها', 'en' => 'Um Ahmed Saves Her Children'],
                'content' => ['ar' => 'أم أحمد أرملة تعول خمسة أطفال. استفادت من مشروع السلال الغذائية واستطاعت توفير الطعام لأطفالها لأول مرة منذ شهور.', 'en' => 'Um Ahmed is a widow with five children. With the food baskets project, she could provide food for her children for the first time in months.'],
                'person_name' => ['ar' => 'أم أحمد', 'en' => 'Um Ahmed'],
                'age' => 38, 'location' => ['ar' => 'رفح', 'en' => 'Rafah'],
            ],
            [
                'title' => ['ar' => 'محمد يعود للمشي', 'en' => 'Mohammed Walks Again'],
                'content' => ['ar' => 'محمد أصيب بشظايا في قدمه خلال القصف. بفضل العيادة المتنقلة تلقى العلاج اللازم واستطاع المشي مجدداً.', 'en' => 'Mohammed was injured by shrapnel in his leg during the bombing. Thanks to the mobile clinic, he received treatment and can walk again.'],
                'person_name' => ['ar' => 'محمد', 'en' => 'Mohammed'],
                'age' => 45, 'location' => ['ar' => 'خان يونس', 'en' => 'Khan Younis'],
            ],
            [
                'title' => ['ar' => 'فاطمة تتعلم القراءة', 'en' => 'Fatima Learns to Read'],
                'content' => ['ar' => 'فاطمة فتاة يمنية حرمت من التعليم بسبب الحرب. التحقت بمدرسة الدعم التعليمي وأصبحت الآن تقرأ وتكتب.', 'en' => 'Fatima is a Yemeni girl deprived of education due to war. She joined the education support school and can now read and write.'],
                'person_name' => ['ar' => 'فاطمة', 'en' => 'Fatima'],
                'age' => 10, 'location' => ['ar' => 'صنعاء', 'en' => 'Sanaa'],
            ],
            [
                'title' => ['ar' => 'خالد يتحدى البرد', 'en' => 'Khaled Defies the Cold'],
                'content' => ['ar' => 'خالد نازح في مخيمات الشمال مع أسرته. استلم بطانيات وملابس شتوية من مشروع المساعدات الشتوية.', 'en' => 'Khaled is displaced in northern camps with his family. He received blankets and winter clothes from the winter aid project.'],
                'person_name' => ['ar' => 'خالد', 'en' => 'Khaled'],
                'age' => 52, 'location' => ['ar' => 'إدلب', 'en' => 'Idlib'],
            ],
            [
                'title' => ['ar' => 'سارة تحلم بمستقبل', 'en' => 'Sara Dreams of a Future'],
                'content' => ['ar' => 'سارة يتيمة من سوريا. بفضل برنامج كفالة الأيتام حصلت على منحة دراسية وتدرس الآن التمريض.', 'en' => 'Sara is an orphan from Syria. Through the orphan sponsorship program, she received a scholarship and is now studying nursing.'],
                'person_name' => ['ar' => 'سارة', 'en' => 'Sara'],
                'age' => 17, 'location' => ['ar' => 'حلب', 'en' => 'Aleppo'],
            ],
            [
                'title' => ['ar' => 'أبو يوسف والماء النقي', 'en' => 'Abu Youssef and Clean Water'],
                'content' => ['ar' => 'أبو يوسف فلاح في ريف اليمن. بئر الماء الذي حفرناه غير حياته تماماً وأصبح يستطيع ري أرضه.', 'en' => 'Abu Youssef is a farmer in rural Yemen. The water well we drilled completely changed his life, allowing him to irrigate his land.'],
                'person_name' => ['ar' => 'أبو يوسف', 'en' => 'Abu Youssef'],
                'age' => 60, 'location' => ['ar' => 'ريف اليمن', 'en' => 'Rural Yemen'],
            ],
            [
                'title' => ['ar' => 'نور تبتسم من جديد', 'en' => 'Noor Smiles Again'],
                'content' => ['ar' => 'نور طفلة فقدت أسرتها في الزلزال. بفضل الدعم النفسي الذي تلقته في مركز الإيواء استعادت ابتسامتها.', 'en' => 'Noor is a child who lost her family in the earthquake. With psychological support at the shelter center, she regained her smile.'],
                'person_name' => ['ar' => 'نور', 'en' => 'Noor'],
                'age' => 8, 'location' => ['ar' => 'لواء اسكندرون', 'en' => 'Alexandretta'],
            ],
            [
                'title' => ['ar' => 'أحمد يفتح مخبزه', 'en' => 'Ahmed Opens His Bakery'],
                'content' => ['ar' => 'أحمد خباز من غزة. بفضل مشروع الطحين والخبز استطاع إعادة فتح مخبزه وتوفير الخبز لأهالي حيه.', 'en' => 'Ahmed is a baker from Gaza. Through the flour and bread project, he reopened his bakery and provides bread to his neighborhood.'],
                'person_name' => ['ar' => 'أحمد', 'en' => 'Ahmed'],
                'age' => 42, 'location' => ['ar' => 'غزة', 'en' => 'Gaza'],
            ],
            [
                'title' => ['ar' => 'ليلى وفرحة العيد', 'en' => 'Layla and the Joy of Eid'],
                'content' => ['ar' => 'ليلى طفلة يتيمة فرحت بملابس العيد الجديدة التي قدمناها. تقول: "هذه أول مرة أرتدي فستاناً جديداً".', 'en' => 'Layla is an orphan who was overjoyed with the new Eid clothes we provided. She says: "This is the first time I wear a new dress."'],
                'person_name' => ['ar' => 'ليلى', 'en' => 'Layla'],
                'age' => 7, 'location' => ['ar' => 'قطاع غزة', 'en' => 'Gaza Strip'],
            ],
        ];

        $storyImgs = [];
        foreach (array_slice(glob("$src/*.jpg"), 10, 10) as $i => $f) {
            $name = 's_' . ($i + 1) . '.jpg';
            copy($f, $disk->path("stories/$name"));
            $storyImgs[] = "stories/$name";
        }
        $storyVids = [];
        foreach (array_slice(glob("$srcAdham/*.mp4"), 5, 5) as $i => $f) {
            $name = 'sv_' . ($i + 1) . '.mp4';
            copy($f, $disk->path("stories/$name"));
            $storyVids[] = "stories/$name";
        }

        foreach ($stories as $i => $data) {
            Story::create($data + [
                'image' => $storyImgs[$i] ?? null,
                'video_url' => $storyVids[$i] ?? null,
                'video_type' => 'upload',
                'goal_amount' => [50000, 30000, 40000, 25000, 35000, 45000, 20000, 28000, 55000, 15000][$i],
                'raised_amount' => [48000, 29000, 38000, 24000, 34000, 42000, 19000, 25000, 50000, 14000][$i],
                'is_active' => true,
                'sort_order' => $i + 1,
            ]);
        }

        $this->command->info('10 stories seeded.');

        // ─── Emergency Campaigns ────────────────────────────────────
        $campaigns = [
            [
                'slug' => 'gaza-emergency-2026',
                'title' => ['ar' => 'الإغاثة الطارئة لغزة', 'en' => 'Gaza Emergency Relief'],
                'description' => ['ar' => 'دعم عاجل لأهالي غزة في ظل الحرب والحصار', 'en' => 'Urgent support for the people of Gaza under war and siege'],
                'target_amount' => 2000000,
                'currency' => 'SAR',
                'target_country' => 'فلسطين',
                'target_country_code' => 'PS',
                'target_flag' => '🇵🇸',
                'target_location' => 'قطاع غزة',
                'target_latitude' => 31.5, 'target_longitude' => 34.4667,
                'is_featured' => true,
            ],
            [
                'slug' => 'yemen-water-crisis',
                'title' => ['ar' => 'أزمة المياه في اليمن', 'en' => 'Yemen Water Crisis'],
                'description' => ['ar' => 'توفير مياه الشرب النظيفة لليمنيين', 'en' => 'Providing clean drinking water to Yemenis'],
                'target_amount' => 800000,
                'currency' => 'SAR',
                'target_country' => 'اليمن',
                'target_country_code' => 'YE',
                'target_flag' => '🇾🇪',
                'target_location' => 'اليمن',
                'target_latitude' => 15.5527, 'target_longitude' => 48.5164,
            ],
            [
                'slug' => 'sudan-refugees',
                'title' => ['ar' => 'دعم لاجئي السودان', 'en' => 'Sudan Refugee Support'],
                'description' => ['ar' => 'إغاثة عاجلة للنازحين السودانيين', 'en' => 'Urgent relief for displaced Sudanese'],
                'target_amount' => 1500000,
                'currency' => 'SAR',
                'target_country' => 'السودان',
                'target_country_code' => 'SD',
                'target_flag' => '🇸🇩',
                'target_location' => 'السودان',
                'target_latitude' => 12.8628, 'target_longitude' => 30.2176,
                'is_featured' => true,
            ],
            [
                'slug' => 'syria-earthquake',
                'title' => ['ar' => 'إغاثة متضرري الزلزال في سوريا', 'en' => 'Syria Earthquake Relief'],
                'description' => ['ar' => 'مساعدات عاجلة للمتضررين من الزلزال', 'en' => 'Urgent aid for earthquake victims'],
                'target_amount' => 1200000,
                'currency' => 'SAR',
                'target_country' => 'سوريا',
                'target_country_code' => 'SY',
                'target_flag' => '🇸🇾',
                'target_location' => 'شمال سوريا',
                'target_latitude' => 36.2, 'target_longitude' => 37.15,
            ],
            [
                'slug' => 'rohingya-aid',
                'title' => ['ar' => 'إغاثة الروهينغا', 'en' => 'Rohingya Relief'],
                'description' => ['ar' => 'دعم إنساني للاجئي الروهينغا', 'en' => 'Humanitarian support for Rohingya refugees'],
                'target_amount' => 600000,
                'currency' => 'SAR',
                'target_country' => 'ميانمار',
                'target_country_code' => 'MM',
                'target_flag' => '🇲🇲',
                'target_location' => 'مخيمات الروهينغا',
                'target_latitude' => 21.0, 'target_longitude' => 92.0,
            ],
            [
                'slug' => 'lebanon-emergency',
                'title' => ['ar' => 'لبنان في خطر', 'en' => 'Lebanon Emergency'],
                'description' => ['ar' => 'مساعدات إنسانية عاجلة للبنان', 'en' => 'Urgent humanitarian aid for Lebanon'],
                'target_amount' => 700000,
                'currency' => 'SAR',
                'target_country' => 'لبنان',
                'target_country_code' => 'LB',
                'target_flag' => '🇱🇧',
                'target_location' => 'بيروت وجنوب لبنان',
                'target_latitude' => 33.8547, 'target_longitude' => 35.8623,
            ],
            [
                'slug' => 'pakistan-floods',
                'title' => ['ar' => 'فيضانات باكستان', 'en' => 'Pakistan Floods Relief'],
                'description' => ['ar' => 'إغاثة متضرري الفيضانات في باكستان', 'en' => 'Relief for flood victims in Pakistan'],
                'target_amount' => 900000,
                'currency' => 'SAR',
                'target_country' => 'باكستان',
                'target_country_code' => 'PK',
                'target_flag' => '🇵🇰',
                'target_location' => 'إقليم السند',
                'target_latitude' => 24.85, 'target_longitude' => 67.0,
            ],
            [
                'slug' => 'africa-hunger',
                'title' => ['ar' => 'مواجهة المجاعة في أفريقيا', 'en' => 'Fighting Hunger in Africa'],
                'description' => ['ar' => 'توزيع غذاء للمجاعات في القرن الأفريقي', 'en' => 'Food distribution for famine in the Horn of Africa'],
                'target_amount' => 1000000,
                'currency' => 'SAR',
                'target_country' => 'الصومال',
                'target_country_code' => 'SO',
                'target_flag' => '🇸🇴',
                'target_location' => 'القرن الأفريقي',
                'target_latitude' => 5.1521, 'target_longitude' => 46.1996,
            ],
            [
                'slug' => 'palestine-health',
                'title' => ['ar' => 'دعم القطاع الصحي في فلسطين', 'en' => 'Supporting Healthcare in Palestine'],
                'description' => ['ar' => 'تجهيز المستشفيات وتوفير الإمدادات الطبية', 'en' => 'Equipping hospitals and providing medical supplies'],
                'target_amount' => 1100000,
                'currency' => 'SAR',
                'target_country' => 'فلسطين',
                'target_country_code' => 'PS',
                'target_flag' => '🇵🇸',
                'target_location' => 'الضفة الغربية وغزة',
                'target_latitude' => 31.9, 'target_longitude' => 35.2,
                'is_featured' => true,
            ],
            [
                'slug' => 'winter-aid-camp',
                'title' => ['ar' => 'شتاء دافئ للمخيمات', 'en' => 'Warm Winter for Camps'],
                'description' => ['ar' => 'توزيع مساعدات شتوية للنازحين في المخيمات', 'en' => 'Distributing winter supplies to displaced people in camps'],
                'target_amount' => 500000,
                'currency' => 'SAR',
                'target_country' => 'سوريا وتركيا',
                'target_country_code' => 'TR',
                'target_flag' => '🇹🇷',
                'target_location' => 'مخيمات الحدود السورية التركية',
                'target_latitude' => 36.5, 'target_longitude' => 36.5,
            ],
        ];

        $campaignImgs = [];
        foreach (array_slice(glob("$src/*.jpg"), 20, 10) as $i => $f) {
            $name = 'c_' . ($i + 1) . '.jpg';
            copy($f, $disk->path("campaigns/$name"));
            $campaignImgs[] = "campaigns/$name";
        }
        $campaignVids = [];
        foreach (array_slice(glob("$srcAdham/*.mp4"), 10, 5) as $i => $f) {
            $name = 'cv_' . ($i + 1) . '.mp4';
            copy($f, $disk->path("campaigns/$name"));
            $campaignVids[] = "campaigns/$name";
        }

        foreach ($campaigns as $i => $data) {
            $collected = [1450000, 420000, 890000, 650000, 310000, 380000, 410000, 520000, 680000, 290000][$i];
            EmergencyCampaign::create($data + [
                'image' => $campaignImgs[$i] ?? null,
                'video' => $campaignVids[$i] ?? null,
                'collected_amount' => $collected,
                'is_active' => true,
                'starts_at' => now()->subDays(15 - $i),
                'ends_at' => now()->addDays(45 - $i * 3),
            ]);
        }

        $this->command->info('10 emergency campaigns seeded.');
    }
}
