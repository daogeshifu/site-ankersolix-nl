<?php

/*
| 老 Shopify collection → 现有分类 的 301 映射
| 老站(benebomo.com)的 /collections/{slug} 里有大量子集合(尺寸/瓦数/用途)|
| key   = 老 collection slug
| value = 目标分类 slug(必须是本站真实存在的 product_categories.slug);
|         特殊值 'all' 表示跳到 /collections/all 全部商品页。
*/

$toCampingTent = array_fill_keys([
    '1-man-tent', '2-man-tent', '3-man-tent', '4-man-tent', '5-man-tent',
    '6-man-tent', '8-man-tent', '10-man-tent',
    'air-tent', 'beach-tent', 'blackout-tent', 'creative-tent',
    'fishing-tent', 'glamping-tent', 'instant-tent',
], 'camping-tent');

$toSolarGenerator = array_fill_keys([
    '1000-watt-solar-generator', '1500-watt-solar-generator', '2000-watt-solar-generator',
    '3000-watt-solar-generator', '4000-watt-solar-generators', '5000-watt-solar-generator',
    '10000-watt-solar-generator', 'portable-solar-generator', 'solar-backup-generator',
    'solar-generator-for-camping', 'solar-generator-for-house', 'solar-generator-for-rv',
    'solar-generator-kit', 'small-solar-generator',
], 'solar-generator');

$toSolarPanel = array_fill_keys([
    '100-watt-solar-panel', '200-watt-solar-panel', '400-watt-solar-panel',
    'bifacial-solar-panels', 'camping-solar-panels', 'flexible-solar-panel',
    'foldable-solar-panel', 'ground-mounted-solar-panels', 'mini-solar-panels',
    'mobile-solar-panel', 'residential-solar-panels', 'rigid-solar-panel',
    'roof-solar-panels', 'rv-solar-panels', 'small-solar-panels',
    'solar-panel-for-shed', 'solar-panel-kits', 'solar-panel-waterproof',
    'solar-panel-with-outlet', 'solar-panels-for-home',
], 'solar-panel');

$toPowerStation = array_fill_keys([
    '1000-watt-portable-power-station', '1500-watt-portable-power-station',
    '2000-watt-portable-power-station', '500-watt-portable-power-station',
    'battery-power-station', 'portable-power-station-for-camping',
], 'portable-power-station');

$toSunShade = array_fill_keys([
    'camping-sun-shade', 'beach-sun-shade', 'boat-sun-shades',
], 'sun-shade');

$toAccessories = array_fill_keys([
    'beach-bags',
], 'camping-accessories');

$toAll = array_fill_keys([
    'activities', 'fishing-gear', 'camping-gear', 'travel-gear', 'beach-gear',
], 'all');

return array_merge(
    $toCampingTent,
    $toSolarGenerator,
    $toSolarPanel,
    $toPowerStation,
    $toSunShade,
    $toAccessories,
    $toAll
);
