<?php

namespace App\Support;

class CalculatorPageData
{
    public static function forLocale(?string $locale = null): array
    {
        $locale = strtolower((string) ($locale ?: app()->getLocale()));

        $content = str_starts_with($locale, 'en')
            ? self::englishContent()
            : self::dutchContent();

        return array_merge($content, [
            'calculator' => self::calculatorConfig(),
        ]);
    }

    private static function calculatorConfig(): array
    {
        return [
            'battery_options' => [
                ['label' => 'Geen accu', 'capacity' => 0.0, 'price' => 0.0],
                ['label' => '1.92 kWh', 'capacity' => 1.92, 'price' => 1248.0],
                ['label' => '3.84 kWh', 'capacity' => 3.84, 'price' => 1903.0],
                ['label' => '5.76 kWh', 'capacity' => 5.76, 'price' => 2597.0],
                ['label' => '7.68 kWh', 'capacity' => 7.68, 'price' => 2733.0],
            ],
            'home_profiles' => [
                ['label' => 'Appartement', 'value' => 75],
                ['label' => 'Gezinswoning', 'value' => 95],
                ['label' => 'Smart Home', 'value' => 150],
            ],
            'panel_presets' => [800, 1200, 1600, 2000, 2400],
            'assumptions' => [
                'max_output_kw' => 0.8,
                'effective_sun_hours' => 3.2,
                'system_efficiency' => 0.9,
                'trading_margin_per_kwh' => 0.18,
                'p1_meter_price' => 29.9,
                'base_system_price' => 350.0,
                'panel_increment_price' => 150.0,
                'panel_increment_watt' => 400,
                'baseline_panel_watt' => 800,
                'daylight_hours' => 12,
                'year_days' => 365,
            ],
        ];
    }

    private static function dutchContent(): array
    {
        return [
            'meta' => [
                'title' => 'Thuisbatterij calculator: bereken besparing, investering en terugverdientijd',
                'description' => 'Bereken direct wat een thuisbatterij, P1-meter en extra zonnepanelen in 2026 kunnen opleveren. Vergelijk investering, jaarlijkse besparing, terugverdientijd en winst na 10 jaar.',
                'keywords' => 'thuisbatterij calculator, terugverdientijd thuisbatterij, besparing thuisbatterij, batterij opslag berekenen, p1 meter, dynamisch energiecontract',
            ],
            'hero' => [
                'badge' => 'Calculator · bijgewerkt 1 juli 2026',
                'title' => 'Thuisbatterij calculator: bereken investering, besparing en terugverdientijd',
                'description' => 'Deze thuisbatterij calculator berekent voor Nederlandse huishoudens de indicatieve investering, jaarlijkse besparing, terugverdientijd en winst na 10 jaar. De uitkomst gebruikt zes invoervariabelen: stroomprijs, continu sluipverbruik, totaal paneelvermogen, batterijcapaciteit, P1-meter en een dynamisch energiecontract.',
                'stats' => [
                    ['value' => '6', 'label' => 'invoerknoppen die het resultaat sturen'],
                    ['value' => '10 jaar', 'label' => 'projectie voor totale winst'],
                    ['value' => '0,8 kW', 'label' => 'max. directe output naar huis'],
                ],
                'badges' => [
                    'Onafhankelijke rekentool',
                    'Duidelijke aannames',
                    'SEO + GEO opgebouwd',
                ],
            ],
            'direct_answer' => [
                'title' => 'Kort antwoord',
                'summary' => 'Een thuisbatterij is vooral interessant als je overdag meer zonnestroom opwekt dan je direct verbruikt. Met een P1-meter voorkom je onnodige teruglevering, en met een dynamisch contract kan extra handelswinst ontstaan. Deze calculator laat zien hoe groot dat effect in jouw situatie ongeveer is.',
                'items' => [
                    'Rekent op basis van actuele stroomprijs in euro per kWh.',
                    'Laat direct zien hoe batterijcapaciteit de terugverdientijd verschuift.',
                    'Maakt P1-meter en dynamisch contract apart zichtbaar in het eindresultaat.',
                ],
            ],
            'sections' => [
                'calculator' => [
                    'eyebrow' => 'Rekentool',
                    'title' => 'Stel je systeem samen',
                    'description' => 'Pas de invoer aan en vergelijk meteen wat extra opslag, een P1-meter en meer paneelvermogen doen met je businesscase.',
                ],
                'how_it_works' => [
                    'eyebrow' => 'Zo werkt de berekening',
                    'title' => 'Welke aannames gebruikt deze calculator?',
                    'description' => 'De pagina is bewust transparant opgebouwd zodat zowel gebruikers als AI-systemen de logica kunnen volgen en citeren.',
                    'steps' => [
                        [
                            'title' => 'Direct eigen verbruik overdag',
                            'body' => 'We schatten eerst hoeveel zonnestroom je huishouden overdag meteen kan gebruiken. Dat wordt begrensd door je continue verbruik en door een maximale directe output van 0,8 kW richting woning.',
                        ],
                        [
                            'title' => 'Overschot naar de batterij',
                            'body' => 'Daarna berekenen we het dagelijkse overschot. Dat overschot kan alleen worden opgeslagen tot aan de gekozen batterijcapaciteit.',
                        ],
                        [
                            'title' => 'Extra eigen verbruik in de avond',
                            'body' => 'De batterij levert opgeslagen stroom later terug aan het huis. Ook dat deel begrenzen we op het niveau van je continue verbruik, zodat de uitkomst realistisch en conservatief blijft.',
                        ],
                        [
                            'title' => 'Totale businesscase',
                            'body' => 'Tot slot tellen we hardwarekosten, optionele P1-meter, eventuele handelswinst uit een dynamisch contract en de besparing per jaar op tot een terugverdientijd en winst na 10 jaar.',
                        ],
                    ],
                ],
                'assumptions' => [
                    'eyebrow' => 'Aannames in tabelvorm',
                    'title' => 'De vaste waarden achter de uitkomst',
                    'rows' => [
                        ['label' => 'Effectieve zonuren', 'value' => '3,2 uur per dag', 'note' => 'Gebruikte factor voor dagelijkse opbrengst van de panelen.'],
                        ['label' => 'Systeemefficiëntie', 'value' => '90%', 'note' => 'Verliescorrectie voor omzetting en praktijkomstandigheden.'],
                        ['label' => 'Daglichtvenster', 'value' => '12 uur per dag', 'note' => 'Periode waarin direct verbruik en sluipverbruik worden afgezet tegen de opwek.'],
                        ['label' => 'Maximale directe output', 'value' => '0,8 kW', 'note' => 'Veilige softwarematige begrenzing van directe levering richting woning.'],
                        ['label' => 'Basis setprijs', 'value' => '€350', 'note' => 'Indicatieve startprijs voor een 800Wp set zonder accu.'],
                        ['label' => 'Meerprijs per +400Wp', 'value' => '€150', 'note' => 'Wordt opgeteld boven de 800Wp basisconfiguratie.'],
                        ['label' => 'P1-meter', 'value' => '€29,90', 'note' => 'Optionele toeslag voor zero-export aansturing.'],
                        ['label' => 'Handelsmarge dynamisch contract', 'value' => '€0,18 per kWh opslag per dag', 'note' => 'Indicatieve extra opbrengst bij slim laden en ontladen.'],
                    ],
                ],
                'comparison' => [
                    'eyebrow' => 'Wat beïnvloedt welk resultaat',
                    'title' => 'Snel overzicht van de belangrijkste knoppen',
                    'rows' => [
                        ['input' => 'Stroomprijs', 'effect' => 'Hogere prijs verhoogt de jaarlijkse besparing per gebruikte kWh.', 'best_for' => 'Huishoudens met hoge leveringstarieven of dure avondstroom.'],
                        ['input' => 'Sluipverbruik', 'effect' => 'Meer continu verbruik betekent meer directe opname van zonnestroom.', 'best_for' => 'Huizen met koelkast, NAS, ventilatie of altijd-aan apparatuur.'],
                        ['input' => 'Paneelvermogen', 'effect' => 'Meer vermogen verhoogt de opwek, maar de directe output naar huis blijft gemaximeerd.', 'best_for' => 'Wie meer overschot wil opwekken om de accu te laden.'],
                        ['input' => 'Batterijcapaciteit', 'effect' => 'Meer opslag verschuift meer overschot naar de avond, maar verhoogt ook de investering.', 'best_for' => 'Huishoudens met duidelijke avondpiek of groot zonnedak.'],
                        ['input' => 'P1-meter', 'effect' => 'Voorkomt onnodige teruglevering en maakt zero-export nauwkeuriger.', 'best_for' => 'Iedereen die terugleverkosten wil beperken.'],
                        ['input' => 'Dynamisch contract', 'effect' => 'Voegt een extra handelscomponent toe bovenop normaal eigen verbruik.', 'best_for' => 'Gebruikers die actief of automatisch op uurprijzen sturen.'],
                    ],
                ],
                'faq' => [
                    'eyebrow' => 'Veelgestelde vragen',
                    'title' => 'FAQ over deze thuisbatterij calculator',
                    'items' => [
                        [
                            'question' => 'Wat berekent deze thuisbatterij calculator precies?',
                            'answer' => 'De tool berekent vier kernuitkomsten: totale investering, besparing per jaar, terugverdientijd en winst na 10 jaar. Daarvoor combineren we stroomprijs, sluipverbruik, paneelvermogen, batterijcapaciteit, P1-meter en dynamisch contract in één transparant model.',
                        ],
                        [
                            'question' => 'Waarom is sluipverbruik zo belangrijk?',
                            'answer' => 'Hoe hoger je continue verbruik overdag, hoe meer zonnestroom je direct zelf gebruikt. Daardoor stijgt je jaarlijkse besparing en wordt minder stroom onnodig teruggeleverd aan het net.',
                        ],
                        [
                            'question' => 'Wanneer helpt een P1-meter echt?',
                            'answer' => 'Een P1-meter helpt zodra je teruglevering wilt beperken of je batterij slimmer wilt laten reageren op het actuele huisverbruik. In de calculator is de P1-meter een losse schakel zodat je het effect op investering en businesscase direct ziet.',
                        ],
                        [
                            'question' => 'Maakt een dynamisch contract de terugverdientijd altijd korter?',
                            'answer' => 'Niet altijd, maar vaak wel wanneer je systeem slim kan laden bij lage uurprijzen en ontladen tijdens pieken. Daarom voegen we een aparte indicatieve handelsmarge toe zodra je die optie inschakelt.',
                        ],
                        [
                            'question' => 'Zijn de uitkomsten exact of indicatief?',
                            'answer' => 'De uitkomsten zijn indicatief. De calculator is bedoeld als snelle businesscase voor 2026 en gebruikt vaste aannames voor zonuren, efficiëntie, hardwareprijzen en dagelijkse gebruiksprofielen.',
                        ],
                    ],
                ],
                'related' => [
                    'eyebrow' => 'Verder lezen',
                    'title' => 'Verdiep je na de berekening',
                    'cards' => [
                        [
                            'title' => 'Thuisbatterij koopgids',
                            'description' => 'Lees welke capaciteit, chemie en garantie bij jouw woning passen.',
                            'route' => 'buying-guide',
                            'cta' => 'Naar koopgids',
                        ],
                        [
                            'title' => 'Dynamische tarieven & slim laden',
                            'description' => 'Begrijp wanneer slim laden met een batterij financieel interessant wordt.',
                            'route' => 'dynamische-energietarieven',
                            'cta' => 'Lees over tarieven',
                        ],
                        [
                            'title' => 'Installatie & zelf aansluiten',
                            'description' => 'Bekijk waar een plug-and-play of stekkerbatterij technisch op moet letten.',
                            'route' => 'installation',
                            'cta' => 'Bekijk installatie',
                        ],
                    ],
                ],
            ],
            'tool' => [
                'title' => 'System Configurator',
                'subtitle' => 'Pas de invoer aan en zie direct wat jouw thuisbatterijscenario doet.',
                'labels' => [
                    'price' => 'Huidige stroomprijs',
                    'baseload' => 'Geschat sluipverbruik',
                    'panels' => 'Totaal vermogen panelen',
                    'battery' => 'Thuisbatterij capaciteit',
                    'p1' => 'Inclusief P1-meter voor zero-export (+ €29,90)',
                    'dynamic' => 'Dynamisch energiecontract',
                    'investment' => 'Totale investering',
                    'yearly_savings' => 'Besparing per jaar',
                    'payback' => 'Terugverdientijd',
                    'profit' => 'Winst na 10 jaar',
                    'trading_note' => 'Inclusief geschatte handelswinst via dynamisch contract',
                    'profile_unit' => 'W continu',
                    'price_unit' => '€ / kWh',
                    'panel_unit' => 'Wp',
                ],
                'messages' => [
                    'oversizing' => 'Softwarematig begrensd op 800W directe output naar huis. Extra opwek gaat eerst naar opslag of wordt afgeknepen.',
                    'p1_help' => 'De P1-meter helpt om teruglevering te beperken en je systeem nauwkeuriger op het actuele huisverbruik af te stemmen.',
                    'dynamic_help' => 'Met een dynamisch contract rekenen we extra opbrengst mee uit slim laden en ontladen op uurprijzen.',
                    'dynamic_requires_battery' => 'Selecteer eerst een thuisbatterij om handelswinst mee te rekenen.',
                    'battery_highlight' => 'Met opslag vergroot je het deel van je zonnestroom dat je zelf gebruikt in de avond en nacht.',
                    'disclaimer' => 'Indicatieve rekentool op basis van vaste aannames voor 2026. Gebruik dit als eerste businesscase, niet als definitieve offerte.',
                ],
                'cta' => [
                    'primary' => ['label' => 'Bekijk passende batterijen', 'route' => 'products.index'],
                    'secondary' => ['label' => 'Lees eerst de koopgids', 'route' => 'buying-guide'],
                ],
            ],
        ];
    }

    private static function englishContent(): array
    {
        return [
            'meta' => [
                'title' => 'Home battery calculator: estimate savings, investment and payback',
                'description' => 'Estimate what a home battery, P1 meter and extra solar panels can deliver in 2026. Compare investment, yearly savings, payback period and 10-year profit.',
                'keywords' => 'home battery calculator, battery payback calculator, solar battery savings, p1 meter, dynamic tariff, battery ROI',
            ],
            'hero' => [
                'badge' => 'Calculator · updated July 1, 2026',
                'title' => 'Home battery calculator: estimate investment, savings and payback',
                'description' => 'This calculator estimates the indicative investment, yearly savings, payback period and 10-year profit for Dutch households. It uses six core inputs: electricity price, always-on baseload, total panel wattage, battery capacity, P1 meter and a dynamic energy contract.',
                'stats' => [
                    ['value' => '6', 'label' => 'inputs driving the result'],
                    ['value' => '10 years', 'label' => 'profit projection window'],
                    ['value' => '0.8 kW', 'label' => 'max direct home output'],
                ],
                'badges' => [
                    'Independent calculator',
                    'Transparent assumptions',
                    'SEO + GEO structured',
                ],
            ],
            'direct_answer' => [
                'title' => 'Short answer',
                'summary' => 'A home battery becomes more attractive when you produce more solar power during the day than you can use immediately. A P1 meter helps reduce unnecessary export, and a dynamic tariff can add trading upside. This calculator shows how large that effect may be in your own situation.',
                'items' => [
                    'Uses your current electricity price in euro per kWh.',
                    'Shows how battery capacity shifts payback in real time.',
                    'Keeps the P1 meter and dynamic tariff impact visible as separate levers.',
                ],
            ],
            'sections' => [
                'calculator' => [
                    'eyebrow' => 'Calculator',
                    'title' => 'Configure your scenario',
                    'description' => 'Adjust the inputs and immediately compare what extra storage, a P1 meter and more solar wattage do to your business case.',
                ],
                'how_it_works' => [
                    'eyebrow' => 'Method',
                    'title' => 'Which assumptions power this calculator?',
                    'description' => 'The page is intentionally transparent so both users and AI systems can follow and cite the logic.',
                    'steps' => [
                        [
                            'title' => 'Direct daytime self-consumption',
                            'body' => 'We first estimate how much solar power your household can consume instantly during the day. That is capped by your continuous load and by a maximum direct output of 0.8 kW to the home.',
                        ],
                        [
                            'title' => 'Excess power into the battery',
                            'body' => 'Next we calculate the daily surplus. That surplus can only be stored up to the chosen battery capacity.',
                        ],
                        [
                            'title' => 'Extra evening self-consumption',
                            'body' => 'The battery later feeds stored energy back into the home. We cap that part at your continuous load as well to keep the estimate conservative.',
                        ],
                        [
                            'title' => 'Full business case',
                            'body' => 'Finally we combine hardware cost, optional P1 meter, dynamic-tariff trading upside and yearly savings into a payback period and 10-year profit view.',
                        ],
                    ],
                ],
                'assumptions' => [
                    'eyebrow' => 'Assumptions table',
                    'title' => 'Fixed values behind the estimate',
                    'rows' => [
                        ['label' => 'Effective sun hours', 'value' => '3.2 hours per day', 'note' => 'Multiplier used for daily panel generation.'],
                        ['label' => 'System efficiency', 'value' => '90%', 'note' => 'Loss correction for conversion and real-world use.'],
                        ['label' => 'Daylight window', 'value' => '12 hours per day', 'note' => 'Period used to compare baseload against generation.'],
                        ['label' => 'Maximum direct output', 'value' => '0.8 kW', 'note' => 'Software-limited direct delivery to the home.'],
                        ['label' => 'Base system price', 'value' => '€350', 'note' => 'Indicative starting price for an 800Wp setup without a battery.'],
                        ['label' => 'Extra cost per +400Wp', 'value' => '€150', 'note' => 'Added on top of the 800Wp baseline configuration.'],
                        ['label' => 'P1 meter', 'value' => '€29.90', 'note' => 'Optional add-on for better zero-export control.'],
                        ['label' => 'Dynamic tariff trading margin', 'value' => '€0.18 per kWh of storage per day', 'note' => 'Indicative extra upside from smart charge and discharge.'],
                    ],
                ],
                'comparison' => [
                    'eyebrow' => 'What changes what',
                    'title' => 'Quick map of the main levers',
                    'rows' => [
                        ['input' => 'Electricity price', 'effect' => 'A higher tariff increases the yearly value of every self-consumed kWh.', 'best_for' => 'Homes with expensive evening electricity or high retail tariffs.'],
                        ['input' => 'Baseload', 'effect' => 'More continuous usage means more direct solar self-consumption during the day.', 'best_for' => 'Homes with routers, ventilation, NAS devices or always-on appliances.'],
                        ['input' => 'Panel wattage', 'effect' => 'More panel power lifts generation, but direct output to the home stays capped.', 'best_for' => 'Users who want more surplus available for charging the battery.'],
                        ['input' => 'Battery capacity', 'effect' => 'More storage shifts more excess solar into the evening, but also raises the investment.', 'best_for' => 'Homes with strong evening demand or larger roofs.'],
                        ['input' => 'P1 meter', 'effect' => 'Reduces unnecessary export and improves zero-export accuracy.', 'best_for' => 'Anyone trying to limit export-related costs.'],
                        ['input' => 'Dynamic tariff', 'effect' => 'Adds a trading component on top of normal self-consumption savings.', 'best_for' => 'Users who automate charging and discharging around hourly price spreads.'],
                    ],
                ],
                'faq' => [
                    'eyebrow' => 'Frequently asked questions',
                    'title' => 'FAQ about this home battery calculator',
                    'items' => [
                        [
                            'question' => 'What does this home battery calculator estimate?',
                            'answer' => 'It estimates four main outcomes: total investment, yearly savings, payback period and 10-year profit. To do that, it combines electricity price, baseload, panel wattage, battery capacity, a P1 meter and a dynamic tariff into one transparent model.',
                        ],
                        [
                            'question' => 'Why does baseload matter so much?',
                            'answer' => 'The higher your continuous daytime demand, the more solar power you can use instantly. That raises yearly savings and reduces how much energy is unnecessarily exported to the grid.',
                        ],
                        [
                            'question' => 'When does a P1 meter really help?',
                            'answer' => 'A P1 meter helps when you want to reduce export or make your battery respond more accurately to real-time household demand. In this calculator it remains a separate toggle so you can see its impact clearly.',
                        ],
                        [
                            'question' => 'Does a dynamic tariff always shorten payback?',
                            'answer' => 'Not always, but it often helps when the system can charge during low-price hours and discharge during peaks. That is why the calculator adds a separate indicative trading margin when the option is enabled.',
                        ],
                        [
                            'question' => 'Are the results exact or directional?',
                            'answer' => 'They are directional. The calculator is built as a fast 2026 business-case tool using fixed assumptions for sun hours, efficiency, hardware costs and daily consumption patterns.',
                        ],
                    ],
                ],
                'related' => [
                    'eyebrow' => 'Keep reading',
                    'title' => 'Useful follow-up guides',
                    'cards' => [
                        [
                            'title' => 'Home battery buying guide',
                            'description' => 'See which capacity, chemistry and warranty level fit your home.',
                            'route' => 'buying-guide',
                            'cta' => 'Open buying guide',
                        ],
                        [
                            'title' => 'Dynamic tariffs & smart charging',
                            'description' => 'Understand when smart charging makes the financial case stronger.',
                            'route' => 'dynamische-energietarieven',
                            'cta' => 'Read tariff guide',
                        ],
                        [
                            'title' => 'Installation & self-setup',
                            'description' => 'Review what a plug-and-play or socket battery setup should account for.',
                            'route' => 'installation',
                            'cta' => 'View installation guide',
                        ],
                    ],
                ],
            ],
            'tool' => [
                'title' => 'System Configurator',
                'subtitle' => 'Adjust the inputs and see what your battery scenario does immediately.',
                'labels' => [
                    'price' => 'Current electricity price',
                    'baseload' => 'Estimated baseload',
                    'panels' => 'Total panel wattage',
                    'battery' => 'Home battery capacity',
                    'p1' => 'Include P1 meter for zero-export (+ €29.90)',
                    'dynamic' => 'Dynamic energy contract',
                    'investment' => 'Total investment',
                    'yearly_savings' => 'Savings per year',
                    'payback' => 'Payback period',
                    'profit' => 'Profit after 10 years',
                    'trading_note' => 'Includes estimated trading upside from a dynamic tariff',
                    'profile_unit' => 'W continuous',
                    'price_unit' => '€ / kWh',
                    'panel_unit' => 'Wp',
                ],
                'messages' => [
                    'oversizing' => 'Direct output to the home remains software-limited to 800W. Extra generation first charges storage or is curtailed.',
                    'p1_help' => 'The P1 meter helps reduce export and makes system control more precise against real-time household demand.',
                    'dynamic_help' => 'With a dynamic tariff we add extra upside from charging low and discharging high.',
                    'dynamic_requires_battery' => 'Select a battery first before enabling tariff trading gains.',
                    'battery_highlight' => 'Storage increases the share of solar energy you can use during the evening and night.',
                    'disclaimer' => 'Directional calculator based on fixed 2026 assumptions. Use it as a first business case, not as a final quote.',
                ],
                'cta' => [
                    'primary' => ['label' => 'Browse matching batteries', 'route' => 'products.index'],
                    'secondary' => ['label' => 'Read the buying guide first', 'route' => 'buying-guide'],
                ],
            ],
        ];
    }
}
