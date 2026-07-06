<?php

namespace App\Support;

class NetMeteringPageData
{
    public static function forLocale(?string $locale = null): array
    {
        $locale = strtolower((string) ($locale ?: app()->getLocale()));

        return str_starts_with($locale, 'en')
            ? self::englishContent()
            : self::dutchContent();
    }

    private static function dutchContent(): array
    {
        return [
            'meta' => [
                'title' => 'Net metering 2027 voor huisbatterijen | 净计量 2027 家用电池',
                'description' => 'Net metering en salderingsregeling in Nederland veranderen op 1 januari 2027. Deze pagina legt helder uit wat dat betekent voor zonnepanelen, terugleververgoeding en de rol van een thuisbatterij.',
                'keywords' => 'net metering, salderingsregeling 2027, thuisbatterij, huisbatterij, terugleververgoeding, dynamische tarieven, zonnepanelen, 净计量, 家用电池',
            ],
            'hero' => [
                'badge' => 'Net metering · bijgewerkt 6 juli 2026',
                'title' => 'Net metering in 2027: wat verandert er voor zonnepanelen en een thuisbatterij?',
                'description' => 'Per 1 januari 2027 stopt de Nederlandse salderingsregeling. Vanaf dat moment kun je teruggeleverde zonnestroom niet meer wegstrepen tegen je jaarlijkse verbruik. Daardoor wordt direct eigen verbruik belangrijker en groeit de strategische rol van een thuisbatterij.',
                'primary_label' => 'Bekijk relevante producten',
                'primary_href' => '#producten',
                'secondary_label' => 'Lees de kernpunten',
                'secondary_href' => '#introductie',
                'stats' => [
                    ['value' => '1 jan 2027', 'label' => 'einde salderingsregeling'],
                    ['value' => 'min. 50%', 'label' => 'terugleververgoeding t/m 2030'],
                    ['value' => 'meer eigen verbruik', 'label' => 'belangrijkste stuurfactor'],
                ],
                'badges' => [
                    'Exacte beleidsdatum opgenomen',
                    'Gebouwd voor SEO + GEO',
                    'Praktisch vertaald naar thuisbatterijen',
                ],
            ],
            'quick_answer' => [
                'title' => 'Kort antwoord',
                'summary' => 'Vanaf 1 januari 2027 stopt de Nederlandse salderingsregeling volledig: teruggeleverde zonnestroom mag dan niet meer worden weggestreept tegen stroom die je later van het net afneemt. In plaats daarvan ontvang je een terugleververgoeding, die tot 1 januari 2030 minimaal 50% van het kale leveringstarief moet zijn, terwijl terugleverkosten wel kunnen blijven bestaan. Daardoor wordt direct eigen verbruik financieel belangrijker dan terugleveren, omdat je over zelf opgewekte en direct gebruikte stroom geen energiebelasting, btw of leverancierskosten betaalt. Een thuisbatterij wordt na 2027 dus niet automatisch rendabel voor ieder huishouden, maar wel duidelijk relevanter voor woningen met zonnestroomoverschot, hoog avondverbruik, terugleverkosten of een slim dynamisch energiecontract.',
                'items' => [
                    'Tot en met 31 december 2026 kun je nog salderen op je jaarafrekening.',
                    'Vanaf 1 januari 2027 krijg je een terugleververgoeding in plaats van volledige verrekening.',
                    'Een thuisbatterij helpt vooral als je meer zonnestroom zelf wilt gebruiken in de avond.',
                ],
            ],
            'anchor_nav' => [
                ['href' => '#introductie', 'label' => 'Introductie'],
                ['href' => '#verandert', 'label' => 'Wat verandert in 2027'],
                ['href' => '#huisbatterij', 'label' => 'Rol van huisbatterij'],
                ['href' => '#producten', 'label' => 'Gerelateerde producten'],
                ['href' => '#artikelen', 'label' => 'Gerelateerde artikelen'],
                ['href' => '#faq', 'label' => 'Veelgestelde vragen'],
                ['href' => '#bronnen', 'label' => 'Officiele bronnen'],
            ],
            'introduction' => [
                'eyebrow' => 'Net metering uitgelegd',
                'title' => 'Wat is net metering of salderen precies?',
                'body' => 'Met salderen verrekent je energieleverancier de stroom die je teruglevert met de stroom die je later van het net afneemt. Voor huishoudens met zonnepanelen was dat jarenlang een sterke businesscase, omdat teruggeleverde zonnestroom op de jaarafrekening bijna dezelfde waarde had als direct verbruik. Vanaf 1 januari 2027 stopt dat systeem in Nederland. Daarna verschuift de logica van zoveel mogelijk terugleveren naar zoveel mogelijk zelf gebruiken.',
                'cards' => [
                    [
                        'icon' => 'solar_power',
                        'title' => 'Tot 2026',
                        'text' => 'Teruglevering en afname worden nog met elkaar verrekend op de jaarafrekening.',
                    ],
                    [
                        'icon' => 'event_upcoming',
                        'title' => 'Vanaf 1 januari 2027',
                        'text' => 'Salderen stopt en teruglevering krijgt een aparte vergoeding.',
                    ],
                    [
                        'icon' => 'battery_charging_full',
                        'title' => 'Nieuwe prioriteit',
                        'text' => 'Meer direct eigen verbruik, slim laden en minder ongunstige teruglevering.',
                    ],
                    [
                        'icon' => 'monitoring',
                        'title' => 'Voor huishoudens',
                        'text' => 'Vooral relevant als je overdag een overschot hebt en in de avond veel stroom gebruikt.',
                    ],
                ],
            ],
            'changes' => [
                'eyebrow' => 'Beleidsimpact',
                'title' => 'Wat verandert er exact op 1 januari 2027?',
                'body' => 'De kernverandering is dat teruggeleverde zonnestroom niet langer dezelfde fiscale en financiële behandeling krijgt als stroom die je later van het net afneemt. Daardoor wordt timing belangrijker: wanneer wek je op, wanneer gebruik je stroom, en hoeveel kun je zelf opslaan?',
                'before_title' => 'Tot en met 31 december 2026',
                'before_items' => [
                    'Salderen op jaarbasis blijft mogelijk.',
                    'Je bespaart ook energiebelasting en btw over gesaldeerde stroom.',
                    'Teruglevering voelt financieel nog relatief sterk aan.',
                ],
                'after_title' => 'Vanaf 1 januari 2027',
                'after_items' => [
                    'Geen saldering meer op de jaarafrekening.',
                    'Voor alle teruggeleverde stroom krijg je een vergoeding van je leverancier.',
                    'Tot 1 januari 2030 moet die vergoeding minimaal 50% van het kale leveringstarief zijn.',
                ],
                'notes' => [
                    'Terugleverkosten kunnen ook na 1 januari 2027 blijven bestaan; vergelijk contracten dus goed.',
                    'Voor direct zelf verbruikte zonnestroom betaal je geen energiebelasting en btw.',
                    'Het financiële verschil tussen direct gebruiken en terugleveren wordt daardoor groter.',
                ],
            ],
            'geo_facts' => [
                'eyebrow' => 'Direct antwoord',
                'title' => 'Kernvragen voor SEO, GEO en AI-samenvattingen',
                'items' => [
                    [
                        'question' => 'Wanneer stopt de salderingsregeling in Nederland?',
                        'answer' => 'Op 1 januari 2027 stopt de salderingsregeling voor kleinverbruikers met zonnepanelen.',
                    ],
                    [
                        'question' => 'Krijg je na 2027 nog geld voor teruggeleverde stroom?',
                        'answer' => 'Ja. Je krijgt een terugleververgoeding van je energieleverancier. Tot 1 januari 2030 moet die minimaal 50% van het kale leveringstarief zijn.',
                    ],
                    [
                        'question' => 'Wordt een thuisbatterij belangrijker na 2027?',
                        'answer' => 'Voor veel huishoudens wel, omdat direct eigen verbruik en slim opslaan waardevoller worden dan terugleveren.',
                    ],
                    [
                        'question' => 'Is een thuisbatterij na 2027 altijd rendabel?',
                        'answer' => 'Nee. Dat hangt af van je zonnestroomoverschot, avondverbruik, terugleverkosten, batterijprijs en energiecontract.',
                    ],
                ],
            ],
            'battery' => [
                'eyebrow' => 'Huisbatterij en netto-metering',
                'title' => 'Waarom een huisbatterij juist na 2027 interessanter wordt',
                'body' => 'Een thuisbatterij verandert overdag opgewekte stroom in avondverbruik. Dat is precies waar het nieuwe beleid meer waarde aan geeft. Je verschuift elektriciteit van lage marktwaarde bij teruglevering naar directe besparing op je eigen afname. Zeker bij terugleverkosten of dynamische tarieven kan dat verschil oplopen.',
                'cards' => [
                    [
                        'icon' => 'west',
                        'title' => 'Minder terugleveren',
                        'text' => 'Je houdt meer zonnestroom achter de meter en verlaagt de afhankelijkheid van een lage terugleververgoeding.',
                    ],
                    [
                        'icon' => 'bedtime',
                        'title' => 'Meer avonddekking',
                        'text' => 'Overdag laden, in de avond ontladen: vooral relevant voor gezinnen met piekverbruik na werktijd.',
                    ],
                    [
                        'icon' => 'query_stats',
                        'title' => 'Beter met dynamische tarieven',
                        'text' => 'Slimme sturing kan laden op goedkope uren en ontladen op dure uren aantrekkelijker maken.',
                    ],
                    [
                        'icon' => 'rule',
                        'title' => 'Sterkere businesscase',
                        'text' => 'De businesscase wordt beter als je ook terugleverkosten wilt beperken of zero-export wilt sturen.',
                    ],
                ],
                'checklist_title' => 'Voor wie is dit onderwerp extra relevant?',
                'checklist' => [
                    'Huishoudens met een groot zonnedak en duidelijk middagoverschot.',
                    'Gezinnen die vooral in de avond koken, wassen, laden of koelen.',
                    'Woningen met terugleverkosten of twijfel over toekomstige contractvormen.',
                    'Gebruikers die dynamische tarieven of slim energiemanagement overwegen.',
                ],
            ],
            'products' => [
                'eyebrow' => 'Gerelateerde producten',
                'title' => 'Producten die passen bij meer eigen verbruik na 2027',
                'cta_label' => 'Alle producten bekijken',
                'empty' => 'Er zijn nog geen producten gekoppeld. Bekijk het volledige productoverzicht voor geschikte thuisbatterijen.',
            ],
            'articles' => [
                'eyebrow' => 'Gerelateerde informatie',
                'title' => 'Verder lezen over tarieven, opslag en terugverdientijd',
                'cta_label' => 'Meer artikelen',
                'empty' => 'Er zijn nog geen artikelen gevonden voor dit onderwerp. Bekijk alle artikelen voor meer verdieping.',
            ],
            'faq' => [
                'eyebrow' => 'Veelgestelde vragen',
                'title' => 'FAQ over net metering 2027 en huisbatterijen',
                'items' => [
                    [
                        'question' => 'Wat is het verschil tussen salderen en terugleververgoeding?',
                        'answer' => 'Bij salderen wordt teruggeleverde stroom verrekend met stroom die je later afneemt. Bij een terugleververgoeding krijg je een losse betaling voor de stroom die je invoedt, terwijl je afgenomen stroom gewoon tegen je contracttarief wordt afgerekend.',
                    ],
                    [
                        'question' => 'Waarom wordt een thuisbatterij na 2027 relevanter?',
                        'answer' => 'Omdat het financiële voordeel verschuift van terugleveren naar direct zelf gebruiken. Een thuisbatterij maakt het mogelijk om middagoverschot te bewaren voor de avond.',
                    ],
                    [
                        'question' => 'Krijg ik na 2027 nog steeds een vergoeding voor zonnestroom?',
                        'answer' => 'Ja. Volgens de Nederlandse regels ontvang je een redelijke terugleververgoeding. Tot 1 januari 2030 is dat minimaal 50% van het kale leveringstarief.',
                    ],
                    [
                        'question' => 'Verdient elke woning met zonnepanelen een thuisbatterij terug?',
                        'answer' => 'Nee. De uitkomst hangt af van batterijprijs, verbruiksprofiel, hoeveelheid overschot, contracttype en eventuele terugleverkosten. Daarom blijft vergelijken en rekenen belangrijk.',
                    ],
                    [
                        'question' => 'Wat moet ik als consument nu al doen richting 2027?',
                        'answer' => 'Breng je middagoverschot en avondverbruik in kaart, vergelijk terugleverkosten en check of een slimme thuisbatterij, P1-meter of dynamisch contract in jouw situatie past.',
                    ],
                ],
            ],
            'official_sources' => [
                'eyebrow' => 'Officiele bronnen',
                'title' => 'Bronnen waarop deze pagina is gebaseerd',
                'items' => [
                    [
                        'label' => 'Rijksoverheid',
                        'title' => 'Salderingsregeling stopt in 2027',
                        'description' => 'Bevestigt dat de regeling stopt op 1 januari 2027 en dat de terugleververgoeding tot 2030 minimaal 50% van het kale leveringstarief moet zijn.',
                        'url' => 'https://www.rijksoverheid.nl/themas/klimaat-milieu-en-natuur/energie-thuis/salderingsregeling',
                    ],
                    [
                        'label' => 'ACM ConsuWijzer',
                        'title' => 'Salderen en terugleveren',
                        'description' => 'Legt uit wat er voor consumenten verandert, inclusief terugleverkosten en de minimale vergoeding na 2027.',
                        'url' => 'https://consument.acm.nl/elektriciteit-en-gas/duurzame-energie/wat-is-salderen',
                    ],
                    [
                        'label' => 'Business.gov.nl',
                        'title' => 'Netting scheme ends per 2027',
                        'description' => 'Beschrijft de wetswijziging in heldere samenvatting en benadrukt de verschuiving naar meer zelfgebruik van zonne-energie.',
                        'url' => 'https://business.gov.nl/amendments/netting-scheme-solar-panels-ends/',
                    ],
                ],
            ],
            'cta' => [
                'title' => 'Klaar om jouw situatie voor 2027 concreet te maken?',
                'body' => 'Vergelijk thuisbatterijen, lees verder over dynamische tarieven of gebruik de calculator om je eerste businesscase te schetsen.',
                'primary_label' => 'Naar calculator',
                'primary_route' => 'calculator',
                'secondary_label' => 'Bekijk producten',
                'secondary_route' => 'products.index',
            ],
        ];
    }

    private static function englishContent(): array
    {
        return [
            'meta' => [
                'title' => 'Net metering 2027 for home batteries | 净计量 2027 家用电池',
                'description' => 'Dutch net metering changes on January 1, 2027. This page explains what that means for solar owners, export compensation, and the growing role of a home battery.',
                'keywords' => 'net metering Netherlands 2027, home battery, export compensation, solar self-consumption, dynamic tariff, 净计量, 家用电池',
            ],
            'hero' => [
                'badge' => 'Net metering · updated July 6, 2026',
                'title' => 'Net metering in 2027: what changes for solar homes and a home battery?',
                'description' => 'On January 1, 2027, the Dutch netting scheme ends. From that date, exported solar power can no longer be offset against annual consumption. That makes self-consumption and battery storage more important.',
                'primary_label' => 'Browse related products',
                'primary_href' => '#products',
                'secondary_label' => 'Read key facts',
                'secondary_href' => '#introduction',
                'stats' => [
                    ['value' => 'Jan 1, 2027', 'label' => 'end of netting scheme'],
                    ['value' => 'min. 50%', 'label' => 'export compensation until 2030'],
                    ['value' => 'higher self-use', 'label' => 'main strategy after 2027'],
                ],
                'badges' => [
                    'Exact policy date included',
                    'Structured for SEO + GEO',
                    'Translated into battery decisions',
                ],
            ],
            'quick_answer' => [
                'title' => 'Short answer',
                'summary' => 'From January 1, 2027, the Dutch netting scheme ends completely, which means exported solar electricity can no longer be offset against electricity later taken from the grid. Instead, households receive an export compensation that must remain at least 50% of the bare supply tariff until January 1, 2030, while feed-in costs may still continue. That makes direct self-consumption more valuable than exporting, because electricity you generate and use immediately is not charged with energy tax, VAT, or supplier costs. A home battery therefore does not become automatically profitable for every household after 2027, but it does become much more relevant for homes with midday solar surplus, strong evening demand, feed-in costs, or a smart dynamic tariff contract.',
                'items' => [
                    'You can still net annual production and consumption until December 31, 2026.',
                    'From January 1, 2027 you receive export compensation instead of full offsetting.',
                    'A home battery helps most when you want to shift solar power into the evening.',
                ],
            ],
            'anchor_nav' => [
                ['href' => '#introduction', 'label' => 'Introduction'],
                ['href' => '#changes', 'label' => 'What changes in 2027'],
                ['href' => '#battery', 'label' => 'Home battery impact'],
                ['href' => '#products', 'label' => 'Related products'],
                ['href' => '#articles', 'label' => 'Related articles'],
                ['href' => '#faq', 'label' => 'FAQ'],
                ['href' => '#sources', 'label' => 'Official sources'],
            ],
            'introduction' => [
                'eyebrow' => 'Net metering explained',
                'title' => 'What is Dutch net metering or salderen?',
                'body' => 'Under the current Dutch scheme, the electricity you export can be offset against electricity you later consume from the grid. That made solar export financially strong for many households. From January 1, 2027 this system ends. After that, the logic shifts from exporting as much as possible toward using as much solar energy as possible yourself.',
                'cards' => [
                    ['icon' => 'solar_power', 'title' => 'Until 2026', 'text' => 'Annual offsetting between export and grid consumption still applies.'],
                    ['icon' => 'event_upcoming', 'title' => 'From January 1, 2027', 'text' => 'Annual netting ends and exported electricity gets a separate compensation.'],
                    ['icon' => 'battery_charging_full', 'title' => 'New priority', 'text' => 'Higher self-consumption, smarter charging and less low-value export.'],
                    ['icon' => 'monitoring', 'title' => 'Why it matters', 'text' => 'Especially relevant for homes with daytime surplus and evening demand.'],
                ],
            ],
            'changes' => [
                'eyebrow' => 'Policy impact',
                'title' => 'What exactly changes on January 1, 2027?',
                'body' => 'The main shift is that exported solar electricity no longer receives the same annual tax and bill treatment as electricity you later buy from the grid. Timing matters more: when you generate, when you consume, and how much you can store yourself.',
                'before_title' => 'Until December 31, 2026',
                'before_items' => [
                    'Annual netting is still possible.',
                    'You also avoid energy tax and VAT on netted electricity.',
                    'Export still feels relatively valuable on the annual bill.',
                ],
                'after_title' => 'From January 1, 2027',
                'after_items' => [
                    'No more annual netting on the bill.',
                    'You receive supplier compensation for all exported electricity.',
                    'Until January 1, 2030 that compensation must be at least 50% of the bare supply tariff.',
                ],
                'notes' => [
                    'Feed-in costs can still remain after 2027, so contract comparison stays important.',
                    'You still pay no energy tax or VAT on solar electricity you consume directly yourself.',
                    'That increases the value gap between self-use and export.',
                ],
            ],
            'geo_facts' => [
                'eyebrow' => 'Direct answers',
                'title' => 'Key answers for SEO, GEO and AI summaries',
                'items' => [
                    ['question' => 'When does the Dutch netting scheme end?', 'answer' => 'It ends on January 1, 2027 for small-volume users with solar panels.'],
                    ['question' => 'Do you still get paid for exported solar power after 2027?', 'answer' => 'Yes. You receive export compensation from your energy supplier. Until January 1, 2030 it must be at least 50% of the bare supply tariff.'],
                    ['question' => 'Does a home battery become more relevant after 2027?', 'answer' => 'For many households yes, because self-consumption and storage become more valuable than exporting power.'],
                    ['question' => 'Is a home battery always profitable after 2027?', 'answer' => 'No. Profitability still depends on surplus solar, evening demand, battery cost, tariff type and feed-in costs.'],
                ],
            ],
            'battery' => [
                'eyebrow' => 'Home battery and net metering',
                'title' => 'Why a home battery becomes more interesting after 2027',
                'body' => 'A home battery turns midday generation into evening consumption. That is exactly where the post-2027 policy creates more value. You shift electricity away from low-value export toward avoided grid purchases. The case improves further when feed-in costs or dynamic tariffs are part of the equation.',
                'cards' => [
                    ['icon' => 'west', 'title' => 'Less export', 'text' => 'Keep more solar energy behind the meter instead of depending on lower export value.'],
                    ['icon' => 'bedtime', 'title' => 'More evening coverage', 'text' => 'Charge by day, discharge at night, especially useful for family demand peaks after work.'],
                    ['icon' => 'query_stats', 'title' => 'Better with dynamic tariffs', 'text' => 'Smart control can charge on cheap hours and discharge on expensive hours.'],
                    ['icon' => 'rule', 'title' => 'Stronger business case', 'text' => 'The case improves when you also want to reduce feed-in costs or manage zero-export.'],
                ],
                'checklist_title' => 'Who should pay extra attention to this topic?',
                'checklist' => [
                    'Homes with a large solar roof and clear midday surplus.',
                    'Families that use most power in the evening.',
                    'Households facing feed-in costs or contract uncertainty.',
                    'Users considering dynamic tariffs or smart energy management.',
                ],
            ],
            'products' => [
                'eyebrow' => 'Related products',
                'title' => 'Products that support higher self-consumption after 2027',
                'cta_label' => 'Browse all products',
                'empty' => 'No products are linked yet. Open the full product catalog to compare suitable home batteries.',
            ],
            'articles' => [
                'eyebrow' => 'Related insights',
                'title' => 'Read more about tariffs, storage and payback',
                'cta_label' => 'More articles',
                'empty' => 'No related articles were found yet. Browse the full article section for more context.',
            ],
            'faq' => [
                'eyebrow' => 'Frequently asked questions',
                'title' => 'FAQ about net metering 2027 and home batteries',
                'items' => [
                    ['question' => 'What is the difference between netting and export compensation?', 'answer' => 'Netting offsets exported electricity against later grid consumption. Export compensation is a separate payment for fed-in electricity while your imported power is billed at your contract tariff.'],
                    ['question' => 'Why does a home battery become more relevant after 2027?', 'answer' => 'Because the financial logic shifts from exporting toward consuming your own solar power directly. A battery helps move midday surplus into evening use.'],
                    ['question' => 'Do I still receive money for solar export after 2027?', 'answer' => 'Yes. Dutch rules require a reasonable export compensation. Until January 1, 2030 it must be at least 50% of the bare supply tariff.'],
                    ['question' => 'Will every solar home earn back a battery?', 'answer' => 'No. The result still depends on battery price, usage profile, surplus generation, tariff structure and any feed-in costs.'],
                    ['question' => 'What should I do now before 2027?', 'answer' => 'Map your midday surplus and evening demand, compare feed-in costs, and evaluate whether a smart home battery, P1 meter or dynamic tariff fits your situation.'],
                ],
            ],
            'official_sources' => [
                'eyebrow' => 'Official sources',
                'title' => 'Primary sources behind this page',
                'items' => [
                    ['label' => 'Dutch Government', 'title' => 'Netting scheme ends in 2027', 'description' => 'Confirms the January 1, 2027 end date and the minimum 50% export compensation until 2030.', 'url' => 'https://www.rijksoverheid.nl/themas/klimaat-milieu-en-natuur/energie-thuis/salderingsregeling'],
                    ['label' => 'ACM ConsuWijzer', 'title' => 'Netting and feed-in explained', 'description' => 'Explains what changes for consumers, including export compensation and feed-in costs after 2027.', 'url' => 'https://consument.acm.nl/elektriciteit-en-gas/duurzame-energie/wat-is-salderen'],
                    ['label' => 'Business.gov.nl', 'title' => 'Netting scheme ends per 2027', 'description' => 'Summarizes the legal change and the policy goal of stimulating self-consumption.', 'url' => 'https://business.gov.nl/amendments/netting-scheme-solar-panels-ends/'],
                ],
            ],
            'cta' => [
                'title' => 'Ready to turn 2027 into a concrete home battery plan?',
                'body' => 'Compare products, continue reading about tariffs, or use the calculator to build a first business case.',
                'primary_label' => 'Open calculator',
                'primary_route' => 'calculator',
                'secondary_label' => 'Browse products',
                'secondary_route' => 'products.index',
            ],
        ];
    }
}
