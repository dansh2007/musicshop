<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Instrument;
use App\Models\InstrumentImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Очистка базових таблиць для повторного наповнення демо-даними
        InstrumentImage::query()->delete();
        Instrument::query()->delete();
        Brand::query()->delete();
        Category::query()->delete();

        // Базовий набір користувачів (адмін + демо), якщо їх ще немає
        $admin = User::firstOrCreate(
            ['email' => 'admin@jam-store.test'],
            ['name' => 'Admin', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        User::firstOrCreate(
            ['email' => 'user@jam-store.test'],
            ['name' => 'Demo User', 'password' => bcrypt('password'), 'role' => 'user']
        );

        $categories = collect([
            ['name' => 'Електрогітари', 'slug' => 'electric-guitars'],
            ['name' => 'Акустичні гітари', 'slug' => 'acoustic-guitars'],
            ['name' => 'Клавішні', 'slug' => 'keyboards'],
            ['name' => 'Ударні', 'slug' => 'drums'],
            ['name' => 'Студійне', 'slug' => 'studio'],
            ['name' => 'Струнні', 'slug' => 'strings'],
        ])->mapWithKeys(fn ($cat) => [
            $cat['slug'] => Category::create($cat),
        ]);

        $brands = collect([
            ['name' => 'Fender', 'slug' => 'fender', 'country' => 'USA'],
            ['name' => 'Gibson', 'slug' => 'gibson', 'country' => 'USA'],
            ['name' => 'Yamaha', 'slug' => 'yamaha', 'country' => 'Japan'],
            ['name' => 'Roland', 'slug' => 'roland', 'country' => 'Japan'],
            ['name' => 'Pioneer', 'slug' => 'pioneer', 'country' => 'Japan'],
            ['name' => 'Shure', 'slug' => 'shure', 'country' => 'USA'],
            ['name' => 'Ibanez', 'slug' => 'ibanez', 'country' => 'Japan'],
            ['name' => 'Casio', 'slug' => 'casio', 'country' => 'Japan'],
            ['name' => 'Korg', 'slug' => 'korg', 'country' => 'Japan'],
            ['name' => 'Pearl', 'slug' => 'pearl', 'country' => 'USA'],
            ['name' => 'Arturia', 'slug' => 'arturia', 'country' => 'France'],
            ['name' => 'AKG', 'slug' => 'akg', 'country' => 'Austria'],
        ])->mapWithKeys(fn ($brand) => [
            $brand['slug'] => Brand::create($brand),
        ]);

        // Сторінка каталогу отримає реалістичні дані для перевірки дизайну
        $instruments = [
            // Електрогітари
            [
                'title' => 'Fender Player Stratocaster HSS Maple Tidepool',
                'slug' => 'fender-player-stratocaster-hss-tidepool',
                'category' => 'electric-guitars',
                'brand' => 'fender',
                'price' => 32999,
                'short_description' => 'Класична Strat-конфігурація з хамбакером у бриджі.',
                'full_description' => 'Серія Player від Fender — це сучасна зручність та впізнаваний саунд. Корпус alder, гриф maple, хамбакер + два single-coil дають універсальний тон.',
                'specs' => ['Корпус: Alder', 'Гриф: Maple, Modern C', 'Мензула: 25.5"', 'Конфіг: HSS'],
                'main_image' => 'https://i.imgur.com/7SGtA3Z.jpg',
                'gallery' => [
                    'https://i.imgur.com/Lzp1K6k.jpg',
                    'https://i.imgur.com/9RZUXeP.jpg',
                ],
            ],
            [
                'title' => 'Ibanez RG421 EX Iron Pewter',
                'slug' => 'ibanez-rg421ex-iron-pewter',
                'category' => 'electric-guitars',
                'brand' => 'ibanez',
                'price' => 18999,
                'short_description' => 'RG-серія з двома хамбакерами Quantum для важкої музики.',
                'full_description' => 'Махагонієвий корпус, швидкий гриф Wizard III, фіксований міст і хамбакери Quantum забезпечують щільний, агресивний звук для модерн-року та металу.',
                'specs' => ['Корпус: Mahogany', 'Гриф: Maple Wizard III', 'Міст: Fixed bridge', 'Звукознімачі: Quantum Humbuckers'],
                'main_image' => 'https://i.imgur.com/e6tv2Ow.jpg',
                'gallery' => [
                    'https://i.imgur.com/5MY2uc4.jpg',
                ],
            ],
            // Акустичні
            [
                'title' => 'Yamaha FG820 Natural',
                'slug' => 'yamaha-fg820-natural',
                'category' => 'acoustic-guitars',
                'brand' => 'yamaha',
                'price' => 12999,
                'short_description' => 'Перевірена акустика FG з масивною ялинкою.',
                'full_description' => 'FG820 — популярна dreadnought-модель з масивним топом зі ситхінської ялинки та боковинами з махагоні. Теплий, збалансований тон для студії й сцени.',
                'specs' => ['Топ: Масив ситхінської ялинки', 'Обичайки/задня: Mahogany', 'Форма: Dreadnought', 'Мензура: 650 мм'],
                'main_image' => 'https://i.imgur.com/TTp1gdD.jpg',
                'gallery' => [
                    'https://i.imgur.com/BR2u79R.jpg',
                ],
            ],
            [
                'title' => 'Fender CD-60SCE All Mahogany',
                'slug' => 'fender-cd60sce-all-mahogany',
                'category' => 'acoustic-guitars',
                'brand' => 'fender',
                'price' => 14999,
                'short_description' => 'Електроакустика з вирізом та передпідсилювачем Fishman.',
                'full_description' => 'Повністю махагонієва конструкція, комфортний виріз для верхніх ладів і передпідсилювач Fishman з тюнером — готовий інструмент для живих виступів.',
                'specs' => ['Корпус: All Mahogany', 'Преамп: Fishman CD-1 з тюнером', 'Форма: Dreadnought Cutaway', 'Верх: Масив махагоні'],
                'main_image' => 'https://i.imgur.com/aOnnwg4.jpg',
                'gallery' => [
                    'https://i.imgur.com/s45N4ot.jpg',
                ],
            ],
            // Клавішні
            [
                'title' => 'Yamaha P-125a WH',
                'slug' => 'yamaha-p125a-wh',
                'category' => 'keyboards',
                'brand' => 'yamaha',
                'price' => 31999,
                'short_description' => 'Компактне цифрове піаніно з Natural Weighted Action.',
                'full_description' => 'P-125a — популярне портативне піаніно з 88 зваженими клавішами, стерео семплами CFIIIS і вбудованими акустичними системами для домашніх занять і малих виступів.',
                'specs' => ['Клавіші: 88 GHS', 'Поліфонія: 192', 'Акустика: 2x7W', 'USB to Host: Так'],
                'main_image' => 'https://i.imgur.com/1VCX8Wx.jpg',
                'gallery' => [
                    'https://i.imgur.com/ziUWywc.jpg',
                ],
            ],
            [
                'title' => 'Casio Privia PX-S1100 Black',
                'slug' => 'casio-px-s1100-black',
                'category' => 'keyboards',
                'brand' => 'casio',
                'price' => 27999,
                'short_description' => 'Надтонке піаніно з Bluetooth-аудіо та легким корпусом.',
                'full_description' => 'PX-S1100 пропонує 88 зважених клавіш Smart Scaled Hammer Action, Bluetooth Audio/MIDI та акустику 8W+8W у компактному корпусі.',
                'specs' => ['Клавіші: 88 Smart Scaled', 'Bluetooth Audio/MIDI', 'Поліфонія: 192', 'Вага: 11.2 кг'],
                'main_image' => 'https://i.imgur.com/vuTIEAh.jpg',
                'gallery' => [
                    'https://i.imgur.com/yBD8EUu.jpg',
                ],
            ],
            // Ударні
            [
                'title' => 'Pearl Export EXX 22" Jet Black',
                'slug' => 'pearl-export-exx-jet-black',
                'category' => 'drums',
                'brand' => 'pearl',
                'price' => 38999,
                'short_description' => 'Набір барабанів з подвійними томами 10/12/16 і бас-бочкою 22".',
                'full_description' => 'Серія Export — хіт для репетицій та сцени: 6-шарова тополя/махагоні, кріплення Opti-Loc, надійна фурнітура. Конфіг: 22"x18", 10"x7", 12"x8", 16"x16", 14"x5.5" snare.',
                'specs' => ['Матеріал: Поплар/Махагоні 6 шарів', 'Комплект: 22,10,12,16,14sn', 'Кріплення: Opti-Loc', 'Фініш: Jet Black'],
                'main_image' => 'https://i.imgur.com/BdB7U2X.jpg',
                'gallery' => [
                    'https://i.imgur.com/1wWQNuc.jpg',
                ],
            ],
            [
                'title' => 'Roland TD-07KV',
                'slug' => 'roland-td07kv',
                'category' => 'drums',
                'brand' => 'roland',
                'price' => 49999,
                'short_description' => 'Електронний сет на базі TD-07 з двошаровими сітками.',
                'full_description' => 'TD-07KV пропонує сітчасті педи на малому і томах, тихі тарілки, Bluetooth для супроводу та USB-аудіо/MIDI для студійної роботи.',
                'specs' => ['Модуль: TD-07', 'Сітка: PDX-8 snare, PDX-6A toms', 'Bluetooth Audio/MIDI', 'USB multi-channel'],
                'main_image' => 'https://i.imgur.com/1DY0qln.jpg',
                'gallery' => [
                    'https://i.imgur.com/aeUt7Bx.jpg',
                ],
            ],
            // Студійне / DJ
            [
                'title' => 'Pioneer DJ DDJ-FLX6-GT',
                'slug' => 'pioneer-dj-ddj-flx6-gt',
                'category' => 'studio',
                'brand' => 'pioneer',
                'price' => 36999,
                'short_description' => '4-канальний контролер з Merge FX для Rekordbox/Serato.',
                'full_description' => 'Оновлена версія FLX6-GT з поліпшеними джогами, підтримкою Rekordbox, Serato, Virtual DJ і новими сірими акцентами.',
                'specs' => ['Канали: 4', 'Підтримка: Rekordbox/Serato/VDJ', 'Jog: Full-size', 'FX: Merge FX/ Sample Scratch'],
                'main_image' => 'https://i.imgur.com/vpeNCrM.jpg',
                'gallery' => [
                    'https://i.imgur.com/8CDbWke.jpg',
                ],
            ],
            [
                'title' => 'Arturia MiniFuse 2 Black',
                'slug' => 'arturia-minifuse-2-black',
                'category' => 'studio',
                'brand' => 'arturia',
                'price' => 8999,
                'short_description' => 'USB-C інтерфейс 2x2 з MIDI та віртуальним пакетом.',
                'full_description' => 'MiniFuse 2 має два комбіновані преампи, дірект-моніторинг, MIDI In/Out, USB-хаб і в комплекті Analog Lab Intro, Ableton Live Lite, FX.',
                'specs' => ['Входи: 2x XLR/TRS', 'Вихід: 2x TRS + Phones', 'MIDI In/Out: Так', 'USB Hub: Так'],
                'main_image' => 'https://i.imgur.com/9JPpXvU.jpg',
                'gallery' => [
                    'https://i.imgur.com/3tyy3pI.jpg',
                ],
            ],
            // Струнні
            [
                'title' => 'Yamaha V5SA Скрипка 4/4',
                'slug' => 'yamaha-v5sa-violin',
                'category' => 'strings',
                'brand' => 'yamaha',
                'price' => 25999,
                'short_description' => 'Учнівська скрипка з ялиновим верхом та кленом.',
                'full_description' => 'V5SA виготовляється з відбірних порід, комплектується смичком та футляром, готова для навчання та виступів.',
                'specs' => ['Верх: Ялина', 'Задня/обичайки: Клен', 'Розмір: 4/4', 'Комплект: футляр + смичок'],
                'main_image' => 'https://i.imgur.com/2S9lq6J.jpg',
                'gallery' => [
                    'https://i.imgur.com/8BCz3Qr.jpg',
                ],
            ],
            [
                'title' => 'AKG C414 XLII Vocal Set',
                'slug' => 'akg-c414-xlii-vocal-set',
                'category' => 'studio',
                'brand' => 'akg',
                'price' => 47999,
                'short_description' => 'Студійний конденсаторний мікрофон з 9 діаграмами.',
                'full_description' => 'C414 XLII — класика для вокалу й інструментів: 9 полярних характеристик, пади, фільтри, кейс та аксесуари у комплекті.',
                'specs' => ['Діаграми: 9', 'Фільтри: 40/80/160 Гц', 'Пади: -6/-12/-18 дБ', 'Комплект: кріплення, поп-фільтр, кейс'],
                'main_image' => 'https://i.imgur.com/fkSZWvP.jpg',
                'gallery' => [
                    'https://i.imgur.com/bu8sV5g.jpg',
                ],
            ],
        ];

        foreach ($instruments as $item) {
            $instrument = Instrument::create([
                'title' => $item['title'],
                'slug' => $item['slug'],
                'category_id' => $categories[$item['category']]->id,
                'brand_id' => $brands[$item['brand']]->id,
                'short_description' => $item['short_description'],
                'full_description' => $item['full_description'],
                'specs' => $item['specs'],
                'price' => $item['price'],
                'main_image' => $item['main_image'],
            ]);

            foreach ($item['gallery'] as $image) {
                $instrument->images()->create(['image_path' => $image]);
            }
        }
    }
}
