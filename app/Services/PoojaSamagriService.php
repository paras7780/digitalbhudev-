<?php

namespace App\Services;

class PoojaSamagriService
{
    /**
     * Retrieve all available pooja samagri checklists.
     */
    public static function all(): array
    {
        return [
            'satyanarayan-katha' => [
                'name' => 'Shree Satyanarayan Bhagwan Katha',
                'name_gu' => 'શ્રી સત્યનારાયણ ભગવાન કથા',
                'description' => 'The complete authentic samagri checklist for conducting Shree Satyanarayan Puja and Katha at home.',
                'duration' => '2 to 2.5 hours',
                'deity' => 'Lord Vishnu / Satyanarayan',
                'sections' => [
                    [
                        'title' => 'Puja Dravya & Sacred Articles (પૂજા સામગ્રી)',
                        'items' => [
                            'Haldi (Turmeric powder) - 50g',
                            'Kumkum (Kanku) - 50g',
                            'Abil & Gulal - 50g each',
                            'Sindoor - 20g',
                            'Nadachhadi (Kalava / Raksha Sutra) - 2 rolls',
                            'Supari (Betel nuts) - 15 pieces',
                            'Janeu (Sacred thread) - 3 pieces',
                            'Akshat (Whole unbroken rice) - 500g',
                            'Gomutra & Gangajal - 1 small bottle each',
                            'Kapoor (Camphor) - 1 small packet',
                            'Agarbatti (Incense sticks) & Dhoop - 1 packet',
                            'Pure Cow Ghee - 250g',
                            'Cotton wicks (Rui Batti) - round and long',
                            'Dry fruits (Kaju, Badam, Kismis) - 100g each',
                            'Clove (Lavang) & Cardamom (Elaichi) - 20g each',
                        ]
                    ],
                    [
                        'title' => 'Fresh Items to be Arranged on Day (તાજી સામગ્રી)',
                        'items' => [
                            'Banana tree stems (Keli na stambh) - 4 pieces for Mandap',
                            'Mango leaves (Aamra patra) / Asopalav leaves - 21 leaves',
                            'Betel leaves (Nagarvel na paan) - 15 fresh leaves',
                            'Coconuts with water (Shriphal) - 2 whole with coir',
                            'Tulsi leaves (Dal) - 21 fresh leaves',
                            'Seasonal Fresh Flowers & 2 Garlands (Har)',
                            '5 varieties of Fresh Fruits (Panch Phal)',
                            'Sheera Prasad (Suji, Wheat flour, Pure Ghee, Sugar, Banana pieces) - 500g',
                            'Panchamrit (Cow Milk, Curd, Ghee, Honey, Sugar / Bura)',
                        ]
                    ],
                    [
                        'title' => 'Utensils & Household Items (પાત્રો અને આસન)',
                        'items' => [
                            'Wooden Bajot (Chowki) - 2 pieces',
                            'Red cloth & Yellow cloth - 1.25 meters each',
                            'Copper or Brass Kalash (Lota) - 1 piece',
                            'Thali (Brass or Copper plates) - 3 to 4 pieces',
                            'Small copper loti with spoon (Panchpatra Achmani) - 1 set',
                            'Aarti plate with Bell (Ghanti) & Shankh',
                            'Clean seating mats (Aasan) for Yajman & Panditji',
                        ]
                    ]
                ]
            ],
            'griha-pravesh' => [
                'name' => 'Griha Pravesh & Vastu Shanti Pooja',
                'name_gu' => 'ગૃહ પ્રવેશ અને વાસ્તુ શાંતિ પૂજા',
                'description' => 'Comprehensive samagri list for entering and sanctifying your new home with auspicious divine energy.',
                'duration' => '3 to 4 hours',
                'deity' => 'Vastu Purush, Lord Ganesha, Navgraha',
                'sections' => [
                    [
                        'title' => 'Vastu & Havan Samagri (હવન તથા વાસ્તુ સામગ્રી)',
                        'items' => [
                            'Havan Kund (Metal or fresh bricks) - 1 set',
                            'Dry Mango Wood (Amba na lakda) - 2 to 3 kg',
                            'Special Havan Samagri packet - 500g',
                            'Pure Cow Ghee - 1 kg',
                            'Black Til (Sesame seeds) - 250g',
                            'Jau (Barley) - 250g',
                            'Navgraha Samidha wood sticks - 1 packet',
                            'Guggal, Loban & Camphor (Kapoor) - 50g each',
                            'Navratna / Pancharatna kit for Vastu kalash',
                            'Silver or Copper Vastu Purush idol & Nag-Nagin pair',
                        ]
                    ],
                    [
                        'title' => 'Sacred Puja Dravya (પૂજા દ્રવ્યો)',
                        'items' => [
                            'Haldi, Kumkum, Abil, Gulal, Sindoor - 100g each',
                            'Akshat (Unbroken rice) - 1 kg',
                            'Nadachhadi (Kalava) - 4 rolls',
                            'Supari (Pooja betel nuts) - 31 pieces',
                            'Janeu - 5 pieces',
                            'Gangajal, Gomutra, Rose water (Gulab jal) - 1 bottle each',
                            'Cow Dung cake (Gobar na kanda) - 4 pieces for milk boiling',
                        ]
                    ],
                    [
                        'title' => 'Fresh Items & Kitchen Ritual (તાજી સામગ્રી અને દૂધ ઉભરો)',
                        'items' => [
                            'New stainless steel or brass pot for boiling fresh milk (Doodh ubhro)',
                            'Fresh organic Cow milk - 1 liter',
                            'Mango leaves / Asopalav toran for main threshold (Umbaro)',
                            'Nagarvel leaves - 25 pieces',
                            'Coconuts with water (Shriphal) - 4 whole',
                            'Fresh flower garlands - 4 pieces',
                            'Assorted loose flowers & Durva grass - 250g',
                            '5 types of fresh fruits (Panch Phal)',
                            'Sweets for Naivedya (Pedha, Ladoo, or Kheer)',
                        ]
                    ]
                ]
            ],
            'rudrabhishek' => [
                'name' => 'Mahadev Shiva Rudrabhishek & Pooja',
                'name_gu' => 'મહાદેવ શિવ રુદ્રાભિષેક પૂજા',
                'description' => 'Sacred Abhishek and Archana items for worshipping Lord Shiva with Vedic Rudram hymns.',
                'duration' => '1.5 to 2 hours',
                'deity' => 'Lord Shiva / Mahadev',
                'sections' => [
                    [
                        'title' => 'Sacred Abhishek Dravya (અભિષેક દ્રવ્યો)',
                        'items' => [
                            'Pure Cow Milk (Kachhu doodh) - 1 liter',
                            'Fresh Cow Curd (Dahi) - 250g',
                            'Pure Cow Ghee - 100g',
                            'Pure Honey (Madh) - 100g',
                            'Sugar or Bura Shakkar - 150g',
                            'Sugarcane juice (Sherdi no ras) - 500ml (if seasonal)',
                            'Coconut water (Nariyal paani) - from 2 coconuts',
                            'Gangajal - 1 bottle',
                            'Gulab Jal (Rose water) & Attar (Natural perfume)',
                            'Bhasma / Vibhuti - 1 packet',
                        ]
                    ],
                    [
                        'title' => 'Flora & Sacred Leaves (પૂજા પુષ્પ અને પત્ર)',
                        'items' => [
                            'Fresh Bilva Patra (Bel patra, unbroken 3-leaf clusters) - 108 leaves',
                            'Dhatura flower and fruit - 2 to 3 pieces',
                            'White flowers (Aakda na phool / Champo / Mogra) - 250g',
                            'White Chandan (Sandalwood paste) - 1 stick or pure powder',
                            'Akshat (Clean, unbroken rice) - 250g',
                            'Janeu - 2 pieces',
                            'Bhang powder or leaves (if available)',
                        ]
                    ],
                    [
                        'title' => 'Aarti & Naivedya (આરતી અને નૈવેદ્ય)',
                        'items' => [
                            'Kapur (Camphor) for Maha Aarti',
                            'Pure Ghee diya with cotton wicks',
                            'Dry fruits (Kaju, Badam, Makhana) - 100g each',
                            'Shriphal (Coconut) - 1 whole',
                            'Panchamrit vessel with spoon',
                            'Brass Abhishek patra with stand or Shivalinga tray',
                        ]
                    ]
                ]
            ],
            'vivah-sanskar' => [
                'name' => 'Traditional Vedic Vivah (Lagna) Sanskar',
                'name_gu' => 'વૈદિક લગ્ન સંસ્કાર (વિવાહ વિધિ)',
                'description' => 'Complete traditional checklist for Mandap Muhurat, Hastamelap, Saptapadi, and Kanyadaan rituals.',
                'duration' => '3 to 5 hours',
                'deity' => 'Agni Dev, Lord Vishnu, Gauri-Ganesh',
                'sections' => [
                    [
                        'title' => 'Mandap & Havan Dravya (મંડપ અને અગ્નિ પ્રદક્ષિણા)',
                        'items' => [
                            'Havan Kund & Dry Mango Wood (Amba na lakda) - 5 kg',
                            'Pure Cow Ghee - 1.5 kg',
                            'Puffed Rice (Dhaani / Laaja) - 1 kg for Laja Homa',
                            'Special Havan Samagri - 1 kg',
                            'Black Til & Jau - 250g each',
                            'Navgraha Samidha sticks - 2 packets',
                            'Paan leaves (Nagarvel) - 50 leaves',
                            'Supari - 51 pieces',
                            'Nadachhadi (Kalava) - 6 rolls',
                        ]
                    ],
                    [
                        'title' => 'Hastamelap & Kanyadaan Dravya (હસ્તમેળાપ અને કન્યાદાન)',
                        'items' => [
                            'Hastamelap cloth (Var-Kanya Chhedachhedi) - 1 piece',
                            'Antarpat (Traditional white or silk curtain) - 1 piece',
                            'Jaimala / Varmala (Two fresh bridal rose garlands)',
                            'Kansar (Sweet wheat preparation with Ghee and sugar for feeding ritual)',
                            'Silver or Copper coins - 5 pieces for Kanyadaan sankalpa',
                            'Kanyadaan Thali with water conch (Shankh)',
                            'Coconuts (Shriphal) - 5 whole with coir',
                        ]
                    ],
                    [
                        'title' => 'Puja & Shringar Samagri (પૂજા અને શ્રૃંગાર)',
                        'items' => [
                            'Haldi, Kanku, Abil, Gulal, Sindoor - 150g each',
                            'Akshat (Clean whole rice) - 2 kg',
                            'Janeu for Groom & Panditji - 5 pieces',
                            'Wooden Bajot (Chowki) - 4 pieces',
                            'Copper Kalash - 2 pieces with Mango leaves',
                            'Aarti thali with Diya, Camphor, and Bell',
                        ]
                    ]
                ]
            ],
            'navchandi-yajna' => [
                'name' => 'Shri Navchandi Yajna & Devi Anushthan',
                'name_gu' => 'શ્રી નવચંડી યજ્ઞ અને દેવી અનુષ્ઠાન',
                'description' => 'The revered Vedic Yajna invoking Goddess Durga/Chandi for divine blessings, prosperity, and removal of obstacles.',
                'duration' => '4 to 6 hours',
                'deity' => 'Maa Durga / Chandi, Navdurga',
                'sections' => [
                    [
                        'title' => 'Maha Yajna Dravya (મહાયજ્ઞ દ્રવ્ય)',
                        'items' => [
                            'High quality Dry Mango wood - 10 kg',
                            'Pure Cow Ghee - 2 to 3 kg',
                            'Special Navchandi Havan Samagri - 2 kg',
                            'Kamal Gatta (Lotus seeds) - 100g',
                            'Bhojpatra & Astagandha for Yantra writing',
                            'Guggal, Camphor, Loban - 150g each',
                            'Black Til & Jau - 500g each',
                            'Dry Coconut Gola (whole shell) - 2 for Purnahuti',
                            'Red silk cloth (1.25m) with silver coin for Purnahuti',
                        ]
                    ],
                    [
                        'title' => 'Devi Shringar & Offerings (માતાજીનો શ્રૃંગાર અને ચુંદડી)',
                        'items' => [
                            'Red Chunari for Mataji - 2 pieces',
                            'Complete Shringar kit (Bangles, Bindi, Sindoor, Kajal, Mirror, Comb)',
                            'Red flowers (Jaswand / Hibiscus and Red Roses) - 500g',
                            'Mataji na Garlands (Har) - 2 fresh pieces',
                            'Nagarvel leaves - 40 pieces & Supari 40 pieces',
                            'Dry fruits (Panch Mewa) - 250g',
                        ]
                    ],
                    [
                        'title' => 'Kalash Sthapana & Naivedya (કલશ સ્થાપના)',
                        'items' => [
                            'Copper or Brass Kalash with 5 Mango leaves & Coconut',
                            'Saptamritika & Sarvaushadhi for Kalash sanctification',
                            'Naivedya: Kheer, Puri, and Halwa',
                            '5 types of fresh fruits including Pomegranate (Dadam)',
                            'Dakshina and gifts for Kanya Puja (Kanya Pujan articles for 9 girls)',
                        ]
                    ]
                ]
            ],
            'ganesh-sthapana' => [
                'name' => 'Shri Ganesh Sthapana & Pooja',
                'name_gu' => 'શ્રી ગણેશ સ્થાપના અને પૂજા',
                'description' => 'Auspicious ritual samagri for welcoming Lord Ganesha, removing Vighna (obstacles), and starting new ventures.',
                'duration' => '1 to 1.5 hours',
                'deity' => 'Lord Ganesha (Vighnaharta)',
                'sections' => [
                    [
                        'title' => 'Ganesh Pooja Articles (ગણેશ પૂજન સામગ્રી)',
                        'items' => [
                            'Fresh Durva grass (21 blade clusters) - very important',
                            'Red flowers (Jaswand / Hibiscus / Marigold) - 250g',
                            'Red Sindoor - 50g',
                            'Janeu (Sacred thread) - 2 pieces',
                            'Modak or Ladoo (Boondi or Motichoor) - 21 pieces for Naivedya',
                            'Supari - 11 pieces',
                            'Nadachhadi (Kalava) - 2 rolls',
                            'Akshat (Whole unbroken rice) - 250g',
                            'Haldi & Kumkum - 50g each',
                            'Gangajal & Pure Cow Ghee',
                        ]
                    ],
                    [
                        'title' => 'Chowki Sthapana & Utensils (ચોકી સ્થાપના)',
                        'items' => [
                            'Wooden Bajot (Chowki) - 1 piece',
                            'Red or Yellow cotton cloth - 1.25 meters',
                            'Copper Kalash with fresh water and Mango leaves',
                            'Coconut with water (Shriphal) - 1 whole with coir',
                            'Aarti thali with brass Diya, Bell, and Camphor',
                            '5 varieties of seasonal fruits (Bananas essential)',
                        ]
                    ]
                ]
            ],
            'grah-shanti-havan' => [
                'name' => 'Grah Shanti Havan Pujapo',
                'name_gu' => 'ગૃહ શાંતિ હવન પૂજાપો',
                'description' => 'Complete authentic Vedic pujapo checklist for Grah Shanti Havan, Navgrah Pujan, and Dev Pujan with all essential havan articles and naivedya.',
                'duration' => '2.5 to 3.5 hours',
                'deity' => 'Navgrah Devta, Kuldevi & Ganapati Dada',
                'sections' => [
                    [
                        'title' => 'અનાજ, કઠોળ અને હોમ દ્રવ્ય (Grains, Pulses & Havan Dravya)',
                        'items' => [
                            'ઘઉં - ૧ કિલો',
                            'ચોખા - ૧ કિલો',
                            'મગદાળ - ૧ કિલો',
                            'ચણાદાળ - ૧ કિલો',
                            'અડદદાળ - ૧ કિલો',
                            'તુવેરદાળ - ૫૦૦ ગ્રામ',
                            'કમોદ - ૫૦૦ ગ્રામ',
                            'ખાંડ અથવા ગોળ - ૧ કિલો',
                            'શીંગદાણા - ૧ કિલો',
                            'સાકરીયા - ૨૫૦ ગ્રામ',
                            'તલ - ૧ કિલો',
                            'ઘી - ૧. ૫ કિલો ( શુદ્ધ ગાય નું ) (વૈશ્વ દેવ દાન તથા હોમવા માટે)',
                            'જવ - ૧૦૦ ગ્રામ',
                            'છાણા - ૫ નંગ',
                            'લાકડા- ૭ કિલો',
                            'હવન કુંડ',
                        ]
                    ],
                    [
                        'title' => 'પૂજન સામગ્રી અને નૈવેદ્ય (Pooja Articles & Naivedya)',
                        'items' => [
                            'ફુલ - ૫૦૦ ગ્રામ',
                            'ફ્રૂટ - ૭-૭ નંગ ( અલગ -અલગ )',
                            'પંચામૃત',
                            'ગણપતિ દાદા ની મૂર્તિ',
                            'કુળદેવી માતાજી ની મૂર્તિ',
                            'ખેસ- ચૂંદડી',
                            'સીધો - ૧ (બે માણસ જમી શકે એવો ભરવો)',
                            'રોટલી - ૧૩ નંગ',
                            'લાપસી - ૧ વાટકી',
                            'ખીર - ૧ વાટકી',
                            'મગ - ૧ વાટકી',
                        ]
                    ],
                    [
                        'title' => 'પાત્રો અને આસન (Utensils & Seating)',
                        'items' => [
                            'બાજોઠ - ૭',
                            'પાટલા - ૫',
                            'આસન',
                            'થાળી - ૪',
                            'વાટકી - ૪',
                            'ચમચી- ૪',
                            'ત્રામ્બા ની લોટી- ૨',
                        ]
                    ]
                ]
            ],
        ];
    }

    /**
     * Get a specific samagri list by slug.
     */
    public static function find(string $slug): ?array
    {
        $all = self::all();
        return $all[$slug] ?? null;
    }
}
