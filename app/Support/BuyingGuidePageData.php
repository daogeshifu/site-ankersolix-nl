<?php

namespace App\Support;

class BuyingGuidePageData
{
    public static function defaults(): array
    {
        return [
            'hero_badge' => 'Aankoopgids · bijgewerkt juni 2026',
            'hero_title' => 'Thuisbatterij kopen: de complete gids voor 2026',
            'hero_description' => "Alles wat je nodig hebt om de juiste thuisbatterij te kiezen — van capaciteit en accuchemie tot kosten, subsidie en terugverdientijd. Onafhankelijk, in begrijpelijk Nederlands.",
            'hero_primary_label' => 'Bekijk aanbevolen batterijen',
            'hero_primary_href' => '#producten',
            'hero_secondary_label' => 'Welke capaciteit heb ik nodig?',
            'hero_secondary_href' => '#capaciteit',
            'hero_stats' => [
                ['value' => '€700–900', 'label' => 'per kWh, incl. installatie'],
                ['value' => '7–12 jr', 'label' => 'terugverdientijd 2026'],
                ['value' => '10–15 jr', 'label' => 'levensduur (LFP)'],
            ],
            'quick_answer' => [
                'title' => 'Korte antwoord',
                'summary' => 'De beste thuisbatterij voor een gemiddeld Nederlands huishouden heeft 5–10 kWh bruikbare capaciteit, gebruikt veilige LFP-accucellen en ondersteunt dynamische tarieven. Reken op €700–900 per kWh inclusief installatie.',
                'items' => [
                    'Gemiddeld gezin: 5–10 kWh met zonnepanelen',
                    'Warmtepomp of EV: kies 10–15 kWh',
                    'Let op: garantie >= 10 jaar en 6.000+ cycli',
                ],
            ],
            'explanation' => [
                'eyebrow' => 'Wat & waarom',
                'title' => 'Wat is een thuisbatterij?',
                'body' => "Een thuisbatterij slaat elektriciteit op die je zonnepanelen overdag opwekken, zodat je die 's avonds en 's nachts kunt gebruiken in plaats van terug te leveren aan het net. In combinatie met dynamische energietarieven kun je de batterij ook laden wanneer stroom goedkoop is en gebruiken wanneer die duur is. Zo verhoog je je zelfverbruik en word je minder afhankelijk van de salderingsregeling, die vanaf 2027 wordt afgebouwd.",
                'cards' => [
                    ['icon' => 'solar_power', 'title' => 'Meer zelfverbruik', 'text' => 'Verhoog je eigen zonnestroomgebruik van ±30% naar 60–80%.'],
                    ['icon' => 'euro', 'title' => 'Slim met de prijs', 'text' => 'Laad goedkoop bij dynamische tarieven, ontlaad tijdens de piek.'],
                    ['icon' => 'bolt', 'title' => 'Minder netafhankelijk', 'text' => 'Vang de avondpiek op en, met een geschikt model, stroomuitval.'],
                    ['icon' => 'shield', 'title' => 'Klaar voor 2027', 'text' => 'Blijf profiteren van je zonnepanelen na de afbouw van saldering.'],
                ],
            ],
            'capacity' => [
                'eyebrow' => 'Capaciteitshulp',
                'title' => 'Hoeveel kWh heb jij nodig?',
                'body' => 'Kies je huishoudprofiel voor een indicatie van de aanbevolen bruikbare capaciteit.',
                'default_profile' => 'gemiddeld',
                'profiles' => [
                    [
                        'key' => 'klein',
                        'icon' => 'person',
                        'label' => 'Klein',
                        'sub' => '1–2 personen',
                        'cap' => '± 5 kWh',
                        'usage' => 'Verbruik tot 2.500 kWh/jaar',
                        'advice' => 'Bij een laag jaarverbruik en zonnepanelen tot circa 3 kWp is 5 kWh meestal genoeg om de avondpiek op te vangen en je zelfverbruik flink te verhogen.',
                        'product_hint' => 'HomeWizard Plug-In · Marstek Venus',
                    ],
                    [
                        'key' => 'gemiddeld',
                        'icon' => 'family_restroom',
                        'label' => 'Gemiddeld',
                        'sub' => '3–4 personen',
                        'cap' => '5 – 10 kWh',
                        'usage' => 'Verbruik 2.500 – 4.500 kWh/jaar',
                        'advice' => "Voor een gemiddeld gezin met 8–12 zonnepanelen is 5 tot 10 kWh de sweet spot: voldoende om 's avonds op zonnestroom te draaien en slim te handelen op dynamische tarieven.",
                        'product_hint' => 'Sessy · Zonneplan Thuisbatterij',
                    ],
                    [
                        'key' => 'groot',
                        'icon' => 'home',
                        'label' => 'Groot + EV',
                        'sub' => '5+ / warmtepomp',
                        'cap' => '10 – 15+ kWh',
                        'usage' => 'Verbruik 4.500+ kWh/jaar',
                        'advice' => 'Heb je een warmtepomp, elektrische auto of een groot huishouden, kies dan 10 tot 15 kWh (modulair uitbreidbaar) met voldoende vermogen om pieken op te vangen.',
                        'product_hint' => 'Anker SOLIX X1 (modulair)',
                    ],
                ],
            ],
            'criteria' => [
                'eyebrow' => 'Waar moet je op letten',
                'title' => 'De 6 belangrijkste koopcriteria',
                'items' => [
                    ['icon' => 'battery_charging_full', 'title' => 'Bruikbare capaciteit', 'text' => 'Let op de bruikbare kWh (DoD), niet de nominale. 90–95% bruikbaar is goed. Stem af op je verbruik en zonopbrengst.'],
                    ['icon' => 'flash_on', 'title' => 'Laad-/ontlaadvermogen', 'text' => 'Het vermogen (kW) bepaalt hoeveel apparaten je tegelijk kunt voeden en hoe snel je laadt. 3–5 kW volstaat meestal.'],
                    ['icon' => 'cycle', 'title' => 'Cycli & levensduur', 'text' => "Kies 6.000+ laadcycli. LFP-accu's gaan het langst mee en behouden na 10 jaar ruim 70% capaciteit."],
                    ['icon' => 'verified', 'title' => 'Garantie', 'text' => 'Minimaal 10 jaar garantie op behoud van capaciteit (>=70%). Check ook de garantie op de omvormer.'],
                    ['icon' => 'hub', 'title' => 'Slim & uitbreidbaar', 'text' => 'Werkt het systeem met dynamische tarieven en een slimme app? Modulaire batterijen breid je later uit.'],
                    ['icon' => 'health_and_safety', 'title' => 'Veiligheid', 'text' => 'Kies LFP-cellen met een goed BMS, CE-keurmerk en installatie door een erkend installateur.'],
                ],
            ],
            'steps' => [
                'eyebrow' => 'Stappenplan',
                'title' => 'Zo kies je in 4 stappen de juiste batterij',
                'items' => [
                    ['number' => '01', 'title' => 'Bepaal je verbruik', 'text' => 'Kijk naar je jaarverbruik en de opbrengst van je zonnepanelen om de juiste capaciteit te schatten.'],
                    ['number' => '02', 'title' => 'Kies een energiecontract', 'text' => 'Dynamische tarieven halen het meeste uit een batterij. Vergelijk leveranciers en slimme sturing.'],
                    ['number' => '03', 'title' => 'Vergelijk modellen', 'text' => 'Zet capaciteit, vermogen, cycli, garantie en prijs naast elkaar in onze vergelijkingstabel.'],
                    ['number' => '04', 'title' => 'Laat installeren', 'text' => 'Vraag offertes bij erkende installateurs en check de terugverdientijd voor jouw situatie.'],
                ],
            ],
            'products_section' => [
                'eyebrow' => 'Aanbevolen',
                'title' => 'Onze topkeuzes voor 2026',
                'cta_label' => 'Alle producten',
                'cta_href' => '/products',
            ],
            'product_cards' => [
                ['icon' => 'battery_charging_full', 'cap' => '5–36 kWh', 'badge' => 'Topkeuze', 'badge_bg' => '#135bec', 'badge_color' => '#ffffff', 'type' => 'Modulair', 'stock' => 'Op voorraad', 'title' => 'Anker SOLIX X1', 'desc' => 'Modulair systeem tot 36 kWh met 12 kW vermogen. Schaalbaar en back-up bij stroomuitval.', 'brand' => 'Anker SOLIX', 'price' => 'vanaf €4.999', 'href' => '#'],
                ['icon' => 'bolt', 'cap' => '5 kWh', 'badge' => 'Slim laden', 'badge_bg' => '#ecfdf3', 'badge_color' => '#047857', 'type' => 'All-in-one', 'stock' => 'Op voorraad', 'title' => 'Sessy Thuisbatterij', 'desc' => 'Nederlandse batterij die automatisch handelt op dynamische tarieven. Eenvoudig te koppelen.', 'brand' => 'Sessy', 'price' => '±€3.799', 'href' => '#'],
                ['icon' => 'solar_power', 'cap' => '8 kWh', 'badge' => 'Beste app', 'badge_bg' => '#eef3fe', 'badge_color' => '#135bec', 'type' => 'Hybride', 'stock' => 'Op voorraad', 'title' => 'Zonneplan Thuisbatterij', 'desc' => 'Optimaliseert zelfverbruik en handelt op de energiemarkt via een slimme app.', 'brand' => 'Zonneplan', 'price' => '±€5.495', 'href' => '#'],
                ['icon' => 'power', 'cap' => '2,7 kWh', 'badge' => 'Instapper', 'badge_bg' => '#fef3c7', 'badge_color' => '#b45309', 'type' => 'Plug & play', 'stock' => 'Op voorraad', 'title' => 'HomeWizard Plug-In Battery', 'desc' => 'Plug-in batterij zonder installateur. Ideaal om mee te starten met opslag.', 'brand' => 'HomeWizard', 'price' => '±€1.395', 'href' => '#'],
                ['icon' => 'battery_full', 'cap' => '5,12 kWh', 'badge' => 'Beste prijs', 'badge_bg' => '#ecfdf3', 'badge_color' => '#047857', 'type' => 'All-in-one', 'stock' => 'Op voorraad', 'title' => 'Marstek Venus', 'desc' => 'Scherp geprijsde balkonbatterij met goede prijs per kWh en eenvoudige montage.', 'brand' => 'Marstek', 'price' => '±€1.699', 'href' => '#'],
                ['icon' => 'ev_station', 'cap' => '10 kWh', 'badge' => 'Voor EV', 'badge_bg' => '#eef3fe', 'badge_color' => '#135bec', 'type' => 'Hybride', 'stock' => 'Op aanvraag', 'title' => 'EcoFlow PowerOcean', 'desc' => 'Krachtige hybride omvormer met back-up, geschikt voor warmtepomp en laadpaal.', 'brand' => 'EcoFlow', 'price' => 'vanaf €5.899', 'href' => '#'],
            ],
            'comparison' => [
                'eyebrow' => 'Vergelijken',
                'title' => 'Thuisbatterijen vergelijken',
                'note' => 'Prijzen zijn indicatief en gebaseerd op marktdata van juni 2026, inclusief btw, exclusief installatie tenzij anders vermeld.',
                'rows' => [
                    ['model' => 'Anker SOLIX X1', 'capacity' => '5–36 kWh', 'power' => '12 kW', 'cycle_warranty' => '10.000 / 10 jr', 'battery_type' => 'LFP', 'price' => 'vanaf €4.999', 'best_for' => 'Grote huishoudens, EV'],
                    ['model' => 'Sessy', 'capacity' => '5 kWh', 'power' => '2,2 kW', 'cycle_warranty' => '6.000 / 10 jr', 'battery_type' => 'LFP', 'price' => '±€3.799', 'best_for' => 'Dynamische tarieven'],
                    ['model' => 'Zonneplan Thuisbatterij', 'capacity' => '8 kWh', 'power' => '3,7 kW', 'cycle_warranty' => '6.000 / 10 jr', 'battery_type' => 'LFP', 'price' => '±€5.495', 'best_for' => 'Slim handelen op de prijs'],
                    ['model' => 'HomeWizard Plug-In', 'capacity' => '2,7 kWh', 'power' => '0,8 kW', 'cycle_warranty' => '6.000 / 5 jr', 'battery_type' => 'LFP', 'price' => '±€1.395', 'best_for' => 'Instappers, plug & play'],
                    ['model' => 'Marstek Venus', 'capacity' => '5,12 kWh', 'power' => '2,5 kW', 'cycle_warranty' => '6.000 / 10 jr', 'battery_type' => 'LFP', 'price' => '±€1.699', 'best_for' => 'Beste prijs/kWh'],
                ],
            ],
            'costs' => [
                'eyebrow' => 'Kosten & subsidie',
                'title' => 'Wat kost het en wat levert het op?',
                'body' => 'De totale prijs hangt af van capaciteit, omvormer en installatie. Een complete set van 5–10 kWh kost doorgaans tussen €4.000 en €9.000. De terugverdientijd hangt sterk af van je energiecontract en zelfverbruik.',
                'items' => [
                    ['icon' => 'savings', 'text' => 'Geen landelijke subsidie meer specifiek voor thuisbatterijen in 2026 — let op lokale regelingen van je gemeente.'],
                    ['icon' => 'trending_down', 'text' => 'Saldering bouwt af vanaf 2027, waardoor zelf opslaan financieel aantrekkelijker wordt.'],
                    ['icon' => 'bolt', 'text' => 'Dynamisch contract kan de terugverdientijd met enkele jaren verkorten.'],
                ],
                'table_title' => 'Indicatie totale kosten',
                'table_rows' => [
                    ['label' => 'Instap (±2,7 kWh)', 'value' => '€1.300 – €2.000'],
                    ['label' => 'Gemiddeld (5–8 kWh)', 'value' => '€3.500 – €6.000'],
                    ['label' => 'Groot (10–15 kWh)', 'value' => '€6.500 – €11.000'],
                    ['label' => 'Installatie', 'value' => '€500 – €1.500'],
                ],
                'cta_label' => 'Bereken jouw terugverdientijd',
                'cta_href' => '#kosten',
            ],
            'articles_section' => [
                'eyebrow' => 'Verdieping',
                'title' => 'Gerelateerde artikelen',
                'cta_label' => 'Alle artikelen',
                'cta_href' => '#artikelen',
            ],
            'article_cards' => [
                ['icon' => 'trending_down', 'bg' => 'linear-gradient(135deg,#135bec,#0e3fa8)', 'tag' => 'Regelgeving', 'title' => 'Salderingsregeling 2027: wat verandert er precies?', 'excerpt' => 'De afbouw van saldering uitgelegd en wat het betekent voor je terugverdientijd.', 'meta' => '12 juni 2026 · 6 min', 'href' => '#'],
                ['icon' => 'show_chart', 'bg' => 'linear-gradient(135deg,#047857,#065f46)', 'tag' => 'Besparen', 'title' => 'Dynamische energietarieven uitgelegd', 'excerpt' => 'Hoe je met een dynamisch contract en een batterij je stroomkosten verlaagt.', 'meta' => '4 juni 2026 · 8 min', 'href' => '#'],
                ['icon' => 'calculate', 'bg' => 'linear-gradient(135deg,#b45309,#92400e)', 'tag' => 'Rekenen', 'title' => 'Terugverdientijd van een thuisbatterij berekenen', 'excerpt' => 'Een praktische rekenmethode met voorbeelden voor verschillende huishoudens.', 'meta' => '28 mei 2026 · 7 min', 'href' => '#'],
                ['icon' => 'science', 'bg' => 'linear-gradient(135deg,#6d28d9,#4c1d95)', 'tag' => 'Techniek', 'title' => 'LFP vs NMC: welke accuchemie is beter?', 'excerpt' => 'Veiligheid, levensduur en kosten van de twee meest gebruikte accutypes vergeleken.', 'meta' => '20 mei 2026 · 5 min', 'href' => '#'],
            ],
            'faq' => [
                'eyebrow' => 'Veelgestelde vragen',
                'title' => 'FAQ over thuisbatterijen',
                'items' => [
                    ['question' => 'Wat kost een thuisbatterij in 2026?', 'answer' => 'Een thuisbatterij kost in Nederland gemiddeld €4.000 tot €9.000 inclusief installatie, afhankelijk van de capaciteit (5–15 kWh) en de omvormer. Reken op grofweg €700–900 per kWh bruikbare capaciteit. Instapmodellen (plug-in, ±2,7 kWh) zijn er al vanaf ongeveer €1.400.'],
                    ['question' => 'Wanneer verdient een thuisbatterij zich terug?', 'answer' => 'Met de afbouw van de salderingsregeling vanaf 2027 en dynamische tarieven ligt de terugverdientijd doorgaans tussen 7 en 12 jaar. Wie de batterij slim laadt en ontlaadt op basis van de stroomprijs kan dit verkorten.'],
                    ['question' => 'Welke capaciteit heb ik nodig?', 'answer' => 'Een gemiddeld huishouden met zonnepanelen heeft genoeg aan 5 tot 10 kWh. Heb je een warmtepomp of elektrische auto, dan is 10 tot 15 kWh aan te raden. Gebruik onze capaciteitshulp bovenaan deze pagina voor een snelle indicatie.'],
                    ['question' => 'Is een thuisbatterij veilig?', 'answer' => 'Moderne thuisbatterijen gebruiken LiFePO4 (LFP) accucellen, die zeer brandveilig en thermisch stabiel zijn. Let op een CE-markering, een goed batterijmanagementsysteem (BMS) en installatie door een erkend installateur.'],
                    ['question' => 'Hoe lang gaat een thuisbatterij mee?', 'answer' => 'Een kwaliteits-thuisbatterij gaat 10 tot 15 jaar mee, oftewel 6.000 tot 10.000 laadcycli. Fabrikanten geven meestal 10 jaar garantie op behoud van minimaal 70% van de oorspronkelijke capaciteit.'],
                ],
            ],
            'cta' => [
                'title' => 'Klaar om de juiste batterij te kiezen?',
                'body' => 'Vergelijk de topmodellen van 2026 of bereken direct jouw terugverdientijd.',
                'primary_label' => 'Bekijk producten',
                'primary_href' => '#producten',
                'secondary_label' => 'Bereken kosten',
                'secondary_href' => '#kosten',
            ],
        ];
    }

    public static function merge(?array $overrides = null): array
    {
        return self::mergeRecursive(self::defaults(), is_array($overrides) ? $overrides : []);
    }

    private static function mergeRecursive(array $defaults, array $overrides): array
    {
        foreach ($overrides as $key => $value) {
            if (!array_key_exists($key, $defaults)) {
                $defaults[$key] = $value;
                continue;
            }

            if (is_array($value) && is_array($defaults[$key]) && !array_is_list($value) && !array_is_list($defaults[$key])) {
                $defaults[$key] = self::mergeRecursive($defaults[$key], $value);
                continue;
            }

            if ($value !== null && $value !== '') {
                $defaults[$key] = $value;
            }
        }

        return $defaults;
    }
}
