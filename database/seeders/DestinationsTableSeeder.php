<?php

namespace Database\Seeders;

use App\Category;
use App\Destinations;
use App\Tag;
use Illuminate\Database\Seeder;

class DestinationsTableSeeder extends Seeder
{
    public function run()
    {
        $catFamily = Category::create(['name' => 'Family travel']);
        $catWeekend = Category::create(['name' => 'Weekend Getaway']);
        $catSummer = Category::create(['name' => 'Summer']);
        $catWild = Category::create(['name' => 'Explore the wild']);
        $catGroup = Category::create(['name' => 'Group Tour']);
        $catGap = Category::create(['name' => 'Gap Year']);
        $catRoad = Category::create(['name' => 'Road Trip']);
        $catSolo = Category::create(['name' => 'Solo travel']);
        $catFriends = Category::create(['name' => 'Travel with friends']);
        $catBeach = Category::create(['name' => 'Beach & Relaxation']);
        $catCity = Category::create(['name' => 'City Break']);
        $catNature = Category::create(['name' => 'Nature & Wildlife']);
        $catCulture = Category::create(['name' => 'Culture & Heritage']);
        $catAdventure = Category::create(['name' => 'Adventure & Sports']);
        $catLuxury = Category::create(['name' => 'Luxury Travel']);

        $tagTravel = Tag::create(['name' => 'Travel']);
        $tagCruise = Tag::create(['name' => 'Cruise']);
        $tagBeach = Tag::create(['name' => 'Beach']);
        $tagAdventure = Tag::create(['name' => 'Adventure']);
        $tagCulture = Tag::create(['name' => 'Culture']);
        $tagFood = Tag::create(['name' => 'Food']);
        $tagNature = Tag::create(['name' => 'Nature']);
        $tagCity = Tag::create(['name' => 'City']);
        $tagLuxury = Tag::create(['name' => 'Luxury']);

        // EUROPE
        $paris = Destinations::create([
            'pricing' => '1290', 'currency' => 'EUR',
            'title' => 'Paris, France',
            'description' => 'The City of Lights — iconic landmarks, world-class cuisine, and timeless romance await around every cobblestone corner.',
            'content' => 'Paris needs no introduction. From the iron lattice of the Eiffel Tower piercing the skyline to the hallowed halls of the Louvre housing the Mona Lisa, every moment in Paris feels cinematic. Stroll along the Seine at sunset, lose yourself in the charming streets of Montmartre, and savour buttery croissants at a sidewalk café. Visit the gothic masterpiece of Notre-Dame, explore the bohemian Marais district, and watch the city sparkle from the steps of Sacré-Cœur. This tour includes guided visits to Versailles, a Seine river cruise, and a food-tasting walk through Le Marais.',
            'category_id' => $catFamily->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '8-12 People', 'tour_type' => 'Cultural & Sightseeing',
            'latitude' => 48.8566, 'longitude' => 2.3522,
        ]);
        $italy = Destinations::create([
            'pricing' => '890', 'currency' => 'EUR',
            'title' => 'Italian Riviera',
            'description' => 'Sun-drenched coastlines, pastel villages clinging to cliffs, and the freshest Mediterranean cuisine you\'ll ever taste.',
            'content' => 'The Italian Riviera, stretching along the Ligurian coast, is where dramatic cliff faces meet crystal-clear turquoise waters. Begin in glamorous Portofino, where luxury yachts bob in a tiny harbour surrounded by painted facades. Hike the legendary Cinque Terre trail connecting five centuries-old fishing villages — Monterosso, Vernazza, Corniglia, Manarola, and Riomaggiore. Feast on fresh pesto, focaccia di Recco, and locally caught seafood paired with crisp Vermentino wine. This tour includes boat transfers between villages, a cooking class, and guided coastal hikes.',
            'category_id' => $catSummer->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Beach & Adventure',
            'latitude' => 44.1260, 'longitude' => 9.7090,
        ]);
        $london = Destinations::create([
            'pricing' => '1450', 'currency' => 'GBP',
            'title' => 'London, United Kingdom',
            'description' => 'Royal palaces, world-class museums, iconic red buses, and a pulsating cultural scene — London is a city that never ceases to amaze.',
            'content' => 'London blends centuries of history with cutting-edge modernity. Explore the Tower of London, witness the Changing of the Guard at Buckingham Palace, and ride the London Eye for stunning skyline views. Discover world-class art at the British Museum and Tate Modern, then catch a show in the West End. From traditional pubs in Soho to curry houses in Brick Lane, London\'s food scene is as diverse as its people. This tour includes a Thames river cruise, a visit to Windsor Castle, and a guided walk through historic Westminster.',
            'category_id' => $catCity->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '8-12 People', 'tour_type' => 'Cultural & Sightseeing',
            'latitude' => 51.5074, 'longitude' => -0.1278,
        ]);
        $barcelona = Destinations::create([
            'pricing' => '760', 'currency' => 'EUR',
            'title' => 'Barcelona, Spain',
            'description' => 'Gaudí masterpieces, sun-drenched beaches, vibrant tapas bars, and a Mediterranean vibe that captivates every visitor.',
            'content' => 'Barcelona is a feast for the senses. Marvel at the surreal architecture of Antoni Gaudí — the Sagrada Família, Park Güell, and Casa Batlló. Stroll down the tree-lined Las Ramblas, explore the Gothic Quarter\'s medieval streets, and relax on Barceloneta beach. Indulge in Catalan cuisine — patatas bravas, jamón ibérico, seafood paella, and cava. This tour includes a guided Gaudí walking tour, a cooking class, and a day trip to the stunning Montserrat mountain.',
            'category_id' => $catFriends->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Beach',
            'latitude' => 41.3874, 'longitude' => 2.1686,
        ]);
        $amsterdam = Destinations::create([
            'pricing' => '680', 'currency' => 'EUR',
            'title' => 'Amsterdam, Netherlands',
            'description' => 'Canal-lined streets, world-class museums, colourful tulip fields, and a uniquely laid-back atmosphere.',
            'content' => 'Amsterdam is a city of canals, cycling, and culture. Explore the Rijksmuseum and Van Gogh Museum, walk through the historic Anne Frank House, and take a canal cruise through the picturesque Jordaan district. Visit the famous flower market, sample Dutch cheeses, and experience the city\'s vibrant café culture. This tour includes a day trip to the Keukenhof tulip gardens and Zaanse Schans windmills.',
            'category_id' => $catSolo->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Sightseeing',
            'latitude' => 52.3676, 'longitude' => 4.9041,
        ]);
        $santorini = Destinations::create([
            'pricing' => '1100', 'currency' => 'EUR',
            'title' => 'Santorini, Greece',
            'description' => 'Whitewashed villages, breathtaking sunsets over the caldera, crystal-clear waters, and ancient history.',
            'content' => 'Santorini is the crown jewel of the Cyclades. Explore the iconic blue-domed churches of Oia, watch the legendary sunset from Fira\'s cliffs, and relax on distinctive red and black sand beaches. Visit ancient Akrotiri, a Minoan city preserved in volcanic ash, and sample Assyrtiko wine at cliffside vineyards. This tour includes a sailing trip around the caldera, a wine-tasting experience, and a visit to the hot springs.',
            'category_id' => $catSummer->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Romantic & Beach',
            'latitude' => 36.3932, 'longitude' => 25.4615,
        ]);

        // AFRICA
        $djerba = Destinations::create([
            'pricing' => '4500', 'currency' => 'TND',
            'title' => 'Djerba, Tunisia',
            'description' => 'Where Mediterranean charm meets North African hospitality — Tunisia\'s stunning island gem with ancient history and pristine beaches.',
            'content' => 'Djerba is a Mediterranean paradise blending golden beaches, whitewashed architecture, and millennia of history. Explore the charming streets of Houmt Souq with its bustling markets and artisan workshops. Visit the ancient El Ghriba Synagogue, one of the oldest in the world. Relax on the stunning beaches of Sidi Mahrez with their turquoise waters and palm-fringed shores. Enjoy fresh Mediterranean cuisine — grilled fish, brik, couscous, and sweet mint tea at seaside restaurants. This package covers airport transfers, beachfront accommodation, a guided island tour, and water sports activities.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '10-15 People', 'tour_type' => 'Beach & Culture',
            'latitude' => 33.8076, 'longitude' => 10.8455,
        ]);
        $marrakech = Destinations::create([
            'pricing' => '6500', 'currency' => 'MAD',
            'title' => 'Marrakech, Morocco',
            'description' => 'Vibrant souks, stunning palaces, the Atlas Mountains backdrop, and an intoxicating blend of Arab, Berber, and French cultures.',
            'content' => 'Marrakech is a sensory explosion. Wander through the labyrinthine souks of the Medina, marvel at the intricate architecture of Bahia Palace and the Saadian Tombs, and haggle for treasures in Jemaa el-Fna square as it transforms into a nighttime food market. Venture into the Atlas Mountains for breathtaking landscapes and Berber village encounters. This tour includes a guided medina walk, a traditional hammam experience, a cooking class, and a desert excursion to the Agafay.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Adventure',
            'latitude' => 31.6295, 'longitude' => -7.9811,
        ]);
        $capetown = Destinations::create([
            'pricing' => '1800', 'currency' => 'USD',
            'title' => 'Cape Town, South Africa',
            'description' => 'Table Mountain, penguin colonies, stunning coastlines, and a rich cultural tapestry at the tip of Africa.',
            'content' => 'Cape Town is one of the world\'s most beautiful cities. Ride the cable car up Table Mountain, explore the colourful Bo-Kaap neighbourhood, and visit the historic Robben Island. Drive along Chapman\'s Peak to the Cape of Good Hope, see African penguins at Boulders Beach, and sample world-class wines in Stellenbosch and Franschhoek. This tour includes a Cape Peninsula day trip, a wine tour, and a township cultural experience.',
            'category_id' => $catFamily->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '6-12 People', 'tour_type' => 'Nature & Culture',
            'latitude' => -33.9249, 'longitude' => 18.4241,
        ]);
        $zanzibar = Destinations::create([
            'pricing' => '1500', 'currency' => 'USD',
            'title' => 'Zanzibar, Tanzania',
            'description' => 'Spice-scented islands, turquoise waters, white-sand beaches, and a fusion of African, Arab, and European influences.',
            'content' => 'Zanzibar is an archipelago of dreams. Explore Stone Town\'s winding alleys with its carved wooden doors and bustling bazaars. Relax on the pristine beaches of Nungwi and Kendwa, snorkel with sea turtles, and visit a spice plantation to learn about cloves, nutmeg, and cinnamon. This tour includes a spice tour, a safari blue boat trip, and a sunset dhow cruise.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Beach & Culture',
            'latitude' => -6.1659, 'longitude' => 39.2026,
        ]);
        $pyramids = Destinations::create([
            'pricing' => '22000', 'currency' => 'EGP',
            'title' => 'Cairo & Luxor, Egypt',
            'description' => 'Ancient pyramids, pharaonic temples, the Nile River, and five thousand years of civilization.',
            'content' => 'Egypt is the cradle of civilization. Stand in awe before the Great Pyramids of Giza and the Sphinx, explore the treasures of the Egyptian Museum, and bargain in the Khan el-Khalili bazaar. In Luxor, visit the Valley of the Kings, Karnak Temple, and Hatshepsut\'s Temple. This tour includes a Nile river cruise, guided temple tours, and a hot air balloon ride over Luxor.',
            'category_id' => $catGap->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '8-15 People', 'tour_type' => 'Historical & Cultural',
            'latitude' => 30.0444, 'longitude' => 31.2357,
        ]);

        // ASIA
        $tokyo = Destinations::create([
            'pricing' => '2500', 'currency' => 'USD',
            'title' => 'Tokyo, Japan',
            'description' => 'Neon-lit skyscrapers, ancient temples, bullet trains, sushi culture, and a fascinating blend of tradition and futurism.',
            'content' => 'Tokyo is a city of endless discovery. Visit the serene Meiji Shrine, explore the vibrant Shibuya Crossing and Harajuku fashion district, and marvel at the Senso-ji Temple in Asakusa. Experience world-class dining — from Michelin-starred sushi to steaming bowls of ramen. This tour includes a day trip to Mount Fuji, a samurai museum visit, a tea ceremony, and a ride on the Shinkansen bullet train.',
            'category_id' => $catCity->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Modern',
            'latitude' => 35.6762, 'longitude' => 139.6503,
        ]);
        $bali = Destinations::create([
            'pricing' => '1200', 'currency' => 'USD',
            'title' => 'Bali, Indonesia',
            'description' => 'Temple-dotted landscapes, rice terraces, surf breaks, and a deeply spiritual culture that nurtures mind, body, and soul.',
            'content' => 'Bali is the Island of the Gods. Explore the sacred Uluwatu Temple perched on cliffs, wander through the Tegallalang rice terraces, and watch a traditional Kecak dance at sunset. Surf at Seminyak, practice yoga in Ubud, and visit the monkey forest. This tour includes a Ubud cultural tour, a Mount Batur sunrise trek, snorkelling at Nusa Penida, and a Balinese cooking class.',
            'category_id' => $catSolo->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '9 Days / 8 Nights', 'group_size' => '4-10 People', 'tour_type' => 'Beach & Spiritual',
            'latitude' => -8.3405, 'longitude' => 115.0920,
        ]);
        $bangkok = Destinations::create([
            'pricing' => '650', 'currency' => 'USD',
            'title' => 'Bangkok, Thailand',
            'description' => 'Ornate temples, floating markets, sizzling street food, and the friendliest welcome in Southeast Asia.',
            'content' => 'Bangkok is a city of contrasts. Visit the Grand Palace and Wat Pho\'s reclining Buddha, explore the floating markets of Damnoen Saduak, and sample pad thai from a street stall. Take a long-tail boat through the klongs, shop at Chatuchak weekend market, and experience the rooftop bars with stunning skyline views. This tour includes a guided temple tour, a street food crawl, and a day trip to Ayutthaya\'s ancient ruins.',
            'category_id' => $catFriends->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-12 People', 'tour_type' => 'Cultural & Food',
            'latitude' => 13.7563, 'longitude' => 100.5018,
        ]);
        $dubai = Destinations::create([
            'pricing' => '1800', 'currency' => 'USD',
            'title' => 'Dubai, UAE',
            'description' => 'Ultra-modern architecture, desert safaris, luxury shopping, and a city that dreamed itself into the future.',
            'content' => 'Dubai pushes boundaries. Ascend the Burj Khalifa, the world\'s tallest building, shop at the Dubai Mall, and watch the Dubai Fountain show. Go dune bashing in the desert, visit the historic Al Fahidi district, and explore the gold and spice souks. This tour includes a desert safari with BBQ dinner, a Dubai Creek abra ride, and a visit to the futuristic Museum of the Future.',
            'category_id' => $catGroup->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Luxury & Adventure',
            'latitude' => 25.2048, 'longitude' => 55.2708,
        ]);
        $maldives = Destinations::create([
            'pricing' => '3200', 'currency' => 'USD',
            'title' => 'Maldives',
            'description' => 'Overwater bungalows, crystal-clear lagoons, dazzling coral reefs, and the ultimate tropical paradise escape.',
            'content' => 'The Maldives is the epitome of tropical luxury. Stay in overwater villas with glass floors, snorkel with manta rays and whale sharks, and dine on the beach under a canopy of stars. Explore local islands, visit a sandbank for a private picnic, and watch dolphins dance at sunset. This all-inclusive package covers seaplane transfers, a private island excursion, and daily snorkelling trips.',
            'category_id' => $catSummer->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '2-6 People', 'tour_type' => 'Luxury & Beach',
            'latitude' => 3.2028, 'longitude' => 73.2207,
        ]);
        $beijing = Destinations::create([
            'pricing' => '9500', 'currency' => 'CNY',
            'title' => 'Beijing, China',
            'description' => 'The Great Wall, Forbidden City, imperial palaces, and five millennia of Chinese civilization.',
            'content' => 'Beijing is China\'s ancient heart. Walk along the Great Wall at Mutianyu, explore the vast Forbidden City, and wander through the serene Summer Palace. Visit the Temple of Heaven, cycle through the hutongs (traditional alleys), and taste authentic Peking duck. This tour includes a Great Wall hike, a Kung Fu show, and a visit to the Silk Market.',
            'category_id' => $catGap->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-12 People', 'tour_type' => 'Historical & Cultural',
            'latitude' => 39.9042, 'longitude' => 116.4074,
        ]);

        // AMERICAS
        $newyork = Destinations::create([
            'pricing' => '2100', 'currency' => 'USD',
            'title' => 'New York City, USA',
            'description' => 'The Big Apple — iconic skyline, world-class museums, diverse neighbourhoods, and energy that never sleeps.',
            'content' => 'New York City is the city that never sleeps. See the Statue of Liberty, walk through Times Square, explore Central Park, and visit the 9/11 Memorial. Catch a Broadway show, eat pizza in Brooklyn, and browse the Met and MoMA. This tour includes a helicopter ride over Manhattan, a Statue of Liberty cruise, and a food tour through Greenwich Village.',
            'category_id' => $catCity->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'City & Culture',
            'latitude' => 40.7128, 'longitude' => -74.0060,
        ]);
        $machupicchu = Destinations::create([
            'pricing' => '2000', 'currency' => 'USD',
            'title' => 'Machu Picchu, Peru',
            'description' => 'The lost Inca citadel perched among cloud forests — one of the New Seven Wonders of the World.',
            'content' => 'Machu Picchu is the crown jewel of the Inca Empire. Hike the Inca Trail through breathtaking Andean scenery, explore the Sun Gate at sunrise, and marvel at the precision stonework of this ancient citadel. Visit the Sacred Valley, colourful markets of Pisac, and the former Inca capital of Cusco. This tour includes a guided Inca Trail trek, train to Aguas Calientes, and a Sacred Valley tour.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '6-12 People', 'tour_type' => 'Adventure & Historical',
            'latitude' => -13.1631, 'longitude' => -72.5450,
        ]);
        $cancun = Destinations::create([
            'pricing' => '1400', 'currency' => 'USD',
            'title' => 'Cancún & Riviera Maya, Mexico',
            'description' => 'Turquoise Caribbean waters, ancient Mayan ruins, vibrant coral reefs, and all-inclusive resort luxury.',
            'content' => 'Cancún is the gateway to the Mayan world. Explore the ruins of Chichén Itzá and Tulum, swim in crystal-clear cenotes, and snorkel in the world\'s second-largest barrier reef. Relax on white-sand beaches, enjoy Mexican cuisine and tequila tastings. This tour includes a Chichén Itzá day trip, a cenote adventure, and a snorkelling excursion.',
            'category_id' => $catFamily->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-15 People', 'tour_type' => 'Beach & Historical',
            'latitude' => 21.1619, 'longitude' => -86.8515,
        ]);
        $rio = Destinations::create([
            'pricing' => '1600', 'currency' => 'USD',
            'title' => 'Rio de Janeiro, Brazil',
            'description' => 'Christ the Redeemer, Copacabana beach, samba rhythms, and the vibrant heart of Brazil.',
            'content' => 'Rio is a city of celebration. Ride the cog train up to Christ the Redeemer, hike Sugarloaf Mountain for panoramic views, and relax on iconic Copacabana and Ipanema beaches. Explore the vibrant Santa Teresa neighbourhood, hear the samba at Lapa, and take a day trip to Petrópolis. This tour includes a guided city tour, a hang gliding experience, and a Brazilian BBQ dinner.',
            'category_id' => $catFriends->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'City & Adventure',
            'latitude' => -22.9068, 'longitude' => -43.1729,
        ]);
        $tulum = Destinations::create([
            'pricing' => '34', 'currency' => 'MXN',
            'title' => 'Tulum, Mexico',
            'description' => 'Cliffside Mayan ruins overlooking the Caribbean, eco-chic resorts, and bohemian beach vibes.',
            'content' => 'Tulum offers a unique blend of ancient history and modern relaxation. Visit the stunning coastal ruins perched above turquoise waters, swim in sacred cenotes, and explore the eco-parks of Xel-Há and Xcaret. This tour includes a guided ruins visit, a cenote tour, and a traditional Mayan ceremony.',
            'category_id' => $catSolo->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Beach & Historical',
            'latitude' => 20.2114, 'longitude' => -87.4654,
        ]);

        // OCEANIA
        $sydney = Destinations::create([
            'pricing' => '2800', 'currency' => 'AUD',
            'title' => 'Sydney, Australia',
            'description' => 'The Opera House, stunning harbour, golden beaches, and the laid-back Aussie lifestyle.',
            'content' => 'Sydney is blessed with natural beauty. Tour the iconic Opera House and Harbour Bridge, relax at Bondi and Manly beaches, and explore the historic Rocks district. Take a ferry to Taronga Zoo, visit the Blue Mountains for bushwalking, and spot whales along the coast. This tour includes a harbour sailing trip, a Blue Mountains day tour, and a coastal walk from Bondi to Coogee.',
            'category_id' => $catFamily->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-10 People', 'tour_type' => 'City & Nature',
            'latitude' => -33.8688, 'longitude' => 151.2093,
        ]);
        $queenstown = Destinations::create([
            'pricing' => '2400', 'currency' => 'NZD',
            'title' => 'Queenstown, New Zealand',
            'description' => 'The adventure capital of the world — stunning fjords, mountains, and adrenaline-pumping activities.',
            'content' => 'Queenstown sits on the shores of Lake Wakatipu surrounded by the Remarkables mountain range. Bungee jump from the Kawarau Bridge, cruise Milford Sound, and explore arrowtown\'s gold rush history. In winter, ski at Coronet Peak and The Remarkables. This tour includes a Milford Sound cruise, a喷射快艇 (jet boat) ride, and a guided hike through stunning alpine scenery.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Nature',
            'latitude' => -45.0312, 'longitude' => 168.6626,
        ]);
        $fiji = Destinations::create([
            'pricing' => '2600', 'currency' => 'USD',
            'title' => 'Fiji',
            'description' => 'Coconut-fringed islands, warm hospitality, world-class diving, and the ultimate South Pacific escape.',
            'content' => 'Fiji is where happiness finds you. Stay in a beachfront bure, snorkel in crystal-clear lagoons, and experience a traditional kava ceremony. Visit the Garden of the Sleeping Giant, explore the Sabeto mud pools, and island-hop through the Mamanuca and Yasawa groups. This package includes a private island day trip, a village visit, and reef snorkelling.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '2-8 People', 'tour_type' => 'Beach & Relaxation',
            'latitude' => -17.7134, 'longitude' => 178.0650,
        ]);

        // Attach tags
        $paris->tags()->attach([$tagTravel->id, $tagCity->id, $tagFood->id]);
        $italy->tags()->attach([$tagBeach->id, $tagFood->id, $tagAdventure->id]);
        $london->tags()->attach([$tagCity->id, $tagCulture->id, $tagTravel->id]);
        $barcelona->tags()->attach([$tagCity->id, $tagBeach->id, $tagFood->id]);
        $amsterdam->tags()->attach([$tagCity->id, $tagCulture->id, $tagTravel->id]);
        $santorini->tags()->attach([$tagBeach->id, $tagTravel->id, $tagNature->id]);
        $djerba->tags()->attach([$tagBeach->id, $tagCulture->id, $tagTravel->id]);
        $marrakech->tags()->attach([$tagAdventure->id, $tagCulture->id, $tagFood->id]);
        $capetown->tags()->attach([$tagNature->id, $tagAdventure->id, $tagCulture->id]);
        $zanzibar->tags()->attach([$tagBeach->id, $tagNature->id, $tagTravel->id]);
        $pyramids->tags()->attach([$tagCulture->id, $tagTravel->id, $tagAdventure->id]);
        $tokyo->tags()->attach([$tagCity->id, $tagCulture->id, $tagFood->id]);
        $bali->tags()->attach([$tagBeach->id, $tagNature->id, $tagCulture->id]);
        $bangkok->tags()->attach([$tagFood->id, $tagCulture->id, $tagAdventure->id]);
        $dubai->tags()->attach([$tagAdventure->id, $tagCity->id, $tagTravel->id]);
        $maldives->tags()->attach([$tagBeach->id, $tagNature->id, $tagTravel->id]);
        $beijing->tags()->attach([$tagCulture->id, $tagTravel->id, $tagCity->id]);
        $newyork->tags()->attach([$tagCity->id, $tagCulture->id, $tagFood->id]);
        $machupicchu->tags()->attach([$tagAdventure->id, $tagNature->id, $tagCulture->id]);
        $cancun->tags()->attach([$tagBeach->id, $tagCulture->id, $tagAdventure->id]);
        $rio->tags()->attach([$tagCity->id, $tagBeach->id, $tagAdventure->id]);
        $tulum->tags()->attach([$tagBeach->id, $tagCulture->id, $tagTravel->id]);
        $sydney->tags()->attach([$tagCity->id, $tagNature->id, $tagBeach->id]);
        $queenstown->tags()->attach([$tagAdventure->id, $tagNature->id, $tagTravel->id]);
        $fiji->tags()->attach([$tagBeach->id, $tagNature->id, $tagTravel->id]);

        // MORE EUROPE
        $prague = Destinations::create([
            'pricing' => '650', 'currency' => 'EUR',
            'title' => 'Prague, Czech Republic',
            'description' => 'Fairytale castles, medieval old town, world-famous beer, and the stunning Charles Bridge — Prague is straight out of a storybook.',
            'content' => 'Prague is one of Europe\'s most beautiful cities. Walk across the historic Charles Bridge, explore the Prague Castle complex, and watch the Astronomical Clock strike the hour in the Old Town Square. Wander through the Jewish Quarter, visit the Lennon Wall, and enjoy a pint of the world\'s best beer at a traditional pub. This tour includes a castle tour, a river cruise, and a beer-tasting experience.',
            'category_id' => $catWeekend->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '6-12 People', 'tour_type' => 'Cultural & Sightseeing',
            'latitude' => 50.0755, 'longitude' => 14.4378,
        ]);
        $lisbon = Destinations::create([
            'pricing' => '720', 'currency' => 'EUR',
            'title' => 'Lisbon, Portugal',
            'description' => 'Sun-kissed hills, pastel-coloured buildings, custard tarts, and the melancholic soul of Fado music.',
            'content' => 'Lisbon charms with its seven hills, vintage trams, and Atlantic light. Explore the Belém Tower and Jerónimos Monastery, ride Tram 28 through the Alfama district, and taste the iconic pastéis de nata. Visit the vibrant LX Factory, catch a Fado performance, and take a day trip to the fairytale Sintra palace. This tour includes a guided city walk, a Sintra day trip, and a port wine tasting.',
            'category_id' => $catSolo->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Food',
            'latitude' => 38.7223, 'longitude' => -9.1393,
        ]);
        $vienna = Destinations::create([
            'pricing' => '850', 'currency' => 'EUR',
            'title' => 'Vienna, Austria',
            'description' => 'Imperial palaces, classical music, coffeehouse culture, and the grandeur of the Habsburg Empire.',
            'content' => 'Vienna is elegance personified. Tour the Schönbrunn Palace, explore the Hofburg, and admire Klimt\'s "The Kiss" at the Belvedere. Attend a concert at the Musikverein, enjoy Sachertorte at a traditional café, and stroll through the Prater park. This tour includes a palace tour, a classical music concert, and a visit to the Vienna Woods.',
            'category_id' => $catFamily->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '8-12 People', 'tour_type' => 'Cultural & Historical',
            'latitude' => 48.2082, 'longitude' => 16.3738,
        ]);
        $budapest = Destinations::create([
            'pricing' => '580', 'currency' => 'EUR',
            'title' => 'Budapest, Hungary',
            'description' => 'Thermal baths, the Danube River, ruin bars, and a stunning blend of Art Nouveau and Gothic architecture.',
            'content' => 'Budapest is the Pearl of the Danube. Soak in the Széchenyi thermal baths, admire the Parliament building from the river, and explore the Buda Castle and Fisherman\'s Bastion. Cross the iconic Chain Bridge, explore the ruin bars of the Jewish Quarter, and taste Hungarian goulash. This tour includes a Danube cruise, a thermal bath visit, and a Hungarian cooking class.',
            'category_id' => $catFriends->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Relaxation',
            'latitude' => 47.4979, 'longitude' => 19.0402,
        ]);
        $reykjavik = Destinations::create([
            'pricing' => '2100', 'currency' => 'EUR',
            'title' => 'Reykjavik & the Golden Circle, Iceland',
            'description' => 'Glaciers, geysers, waterfalls, the Northern Lights, and landscapes that look otherworldly.',
            'content' => 'Iceland is a land of fire and ice. Explore the Golden Circle — Thingvellir National Park, Geysir hot springs, and Gullfoss waterfall. Bathe in the Blue Lagoon, hike on glaciers, and chase the Northern Lights in winter. Visit black-sand beaches, volcanic craters, and thundering waterfalls like Seljalandsfoss and Skógafoss. This tour includes a Golden Circle tour, a glacier hike, and a Northern Lights hunt.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-12 People', 'tour_type' => 'Adventure & Nature',
            'latitude' => 64.1466, 'longitude' => -21.9426,
        ]);
        $swiss = Destinations::create([
            'pricing' => '2200', 'currency' => 'CHF',
            'title' => 'Swiss Alps, Switzerland',
            'description' => 'Snow-capped peaks, pristine lakes, cogwheel trains, and the most stunning alpine scenery in Europe.',
            'content' => 'Switzerland is nature at its most spectacular. Ride the Jungfraubahn to the Top of Europe, cruise Lake Lucerne, and explore the charming streets of Interlaken and Zermatt. Ski in world-class resorts, hike through wildflower meadows, and taste fondue and raclette in a mountain hut. This tour includes a Jungfraujoch excursion, a scenic train ride, and a Swiss chocolate workshop.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Adventure & Nature',
            'latitude' => 46.8182, 'longitude' => 8.2275,
        ]);
        $rome = Destinations::create([
            'pricing' => '980', 'currency' => 'EUR',
            'title' => 'Rome, Italy',
            'description' => 'The Eternal City — two millennia of history, breathtaking art, and cuisine that heaven must have inspired.',
            'content' => 'Rome is an open-air museum. Explore the Colosseum and Roman Forum, toss a coin in the Trevi Fountain, and visit the Vatican Museums and Sistine Chapel. Wander through Trastevere\'s cobbled streets, eat carbonara in a family-run trattoria, and marvel at the Pantheon. This tour includes guided Colosseum and Vatican tours, a pasta-making class, and a Roman night walking tour.',
            'category_id' => $catGap->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '8-12 People', 'tour_type' => 'Historical & Cultural',
            'latitude' => 41.9028, 'longitude' => 12.4964,
        ]);

        // MORE ASIA
        $seoul = Destinations::create([
            'pricing' => '1400', 'currency' => 'USD',
            'title' => 'Seoul, South Korea',
            'description' => 'K-pop culture, ancient palaces, high-tech wonders, and the best Korean BBQ on the planet.',
            'content' => 'Seoul is a dynamic blend of tradition and futurism. Visit the Gyeongbokgung Palace, explore the Bukchon Hanok Village, and shop in Myeongdong. Indulge in Korean BBQ, bibimbap, and street food at Gwangjang Market. Experience the DMZ, hike Bukhansan Mountain, and soak in a jjimjilbang spa. This tour includes a DMZ tour, a palace visit, and a Korean cooking class.',
            'category_id' => $catCity->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Modern',
            'latitude' => 37.5665, 'longitude' => 126.9780,
        ]);
        $hanoi = Destinations::create([
            'pricing' => '700', 'currency' => 'USD',
            'title' => 'Hanoi & Ha Long Bay, Vietnam',
            'description' => 'Ancient pagodas, chaotic yet charming streets, emerald waters dotted with limestone karsts.',
            'content' => 'Vietnam is a feast for the senses. Explore Hanoi\'s Old Quarter, visit Ho Chi Minh\'s mausoleum, and sip egg coffee in a hidden café. Cruise through Ha Long Bay\'s thousands of limestone islands, kayak through caves, and visit floating villages. This tour includes a Ha Long Bay overnight cruise, a Hanoi street food tour, and a visit to Ninh Binh\'s ancient capital.',
            'category_id' => $catGap->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '6-12 People', 'tour_type' => 'Adventure & Culture',
            'latitude' => 21.0278, 'longitude' => 105.8342,
        ]);
        $hongkong = Destinations::create([
            'pricing' => '1500', 'currency' => 'HKD',
            'title' => 'Hong Kong',
            'description' => 'Sky-piercing skyline, neon-lit streets, dim sum culture, and where East meets West.',
            'content' => 'Hong Kong is a city of contrasts. Ride the Peak Tram for stunning skyline views, explore the bustling streets of Mong Kok, and visit the giant Tian Tan Buddha on Lantau Island. Shop in Causeway Bay, eat dim sum in a traditional teahouse, and take a Star Ferry across Victoria Harbour. This tour includes a harbour cruise, a Lantau Island day trip, and a food tour through Temple Street Night Market.',
            'category_id' => $catCity->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'City & Food',
            'latitude' => 22.3193, 'longitude' => 114.1694,
        ]);
        $jaipur = Destinations::create([
            'pricing' => '900', 'currency' => 'USD',
            'title' => 'Jaipur, India',
            'description' => 'The Pink City — majestic forts, vibrant bazaars, royal palaces, and the colours of Rajasthan.',
            'content' => 'Jaipur is the gateway to India\'s royal heritage. Visit the Amber Fort, Hawa Mahal, and City Palace. Explore the colourful bazaars for textiles, jewellery, and spices. Take a cooking class, ride a camel, and watch a traditional Kathak dance performance. This tour includes a guided fort tour, a village safari, and a Rajasthani dinner.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Historical & Cultural',
            'latitude' => 26.9124, 'longitude' => 75.7873,
        ]);
        $singapore = Destinations::create([
            'pricing' => '1300', 'currency' => 'SGD',
            'title' => 'Singapore',
            'description' => 'A futuristic city-state where hawker centres, Gardens by the Bay, and a melting pot of cultures create an unforgettable experience.',
            'content' => 'Singapore is a garden city. Marvel at the Supertrees of Gardens by the Bay, explore Chinatown and Little India, and enjoy a cocktail at the Marina Bay Sands rooftop. Eat your way through hawker centres — Hainanese chicken rice, laksa, and chilli crab. This tour includes a Sentosa Island visit, a food tour, and a night safari at the zoo.',
            'category_id' => $catFamily->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '6-10 People', 'tour_type' => 'City & Food',
            'latitude' => 1.3521, 'longitude' => 103.8198,
        ]);
        $kathmandu = Destinations::create([
            'pricing' => '850', 'currency' => 'USD',
            'title' => 'Kathmandu & Pokhara, Nepal',
            'description' => 'The Himalayas, ancient temples, meditation retreats, and the spiritual heart of South Asia.',
            'content' => 'Nepal is a trekker\'s paradise. Explore Kathmandu\'s Durbar Square, Swayambhunath Stupa, and Pashupatinath Temple. Fly to Pokhara for stunning views of the Annapurna range, paraglide over Phewa Lake, and trek through beautiful mountain villages. This tour includes a guided city tour, a sunrise hike at Sarangkot, and a meditation retreat.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '4-10 People', 'tour_type' => 'Adventure & Spiritual',
            'latitude' => 27.7172, 'longitude' => 85.3240,
        ]);

        // MORE AFRICA
        $victoria = Destinations::create([
            'pricing' => '1600', 'currency' => 'USD',
            'title' => 'Victoria Falls, Zimbabwe & Zambia',
            'description' => 'The Smoke that Thunders — one of the Seven Natural Wonders of the World and the ultimate adventure destination in Africa.',
            'content' => 'Victoria Falls is breathtaking. Witness the mighty Zambezi River plunge into a dramatic gorge, bungee jump off the bridge between Zimbabwe and Zambia, and white-water raft the rapids below. Take a sunset cruise on the Zambezi, walk with lions, and spot elephants and hippos. This tour includes a falls tour, a game drive in Chobe National Park, and a helicopter flight over the falls.',
            'category_id' => $catWild->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Adventure & Nature',
            'latitude' => -17.9243, 'longitude' => 25.8567,
        ]);
        $mauritius = Destinations::create([
            'pricing' => '1800', 'currency' => 'USD',
            'title' => 'Mauritius',
            'description' => 'Turquoise lagoons, waterfalls, sugarcane fields, and a harmonious blend of African, Indian, French, and Chinese cultures.',
            'content' => 'Mauritius is paradise in the Indian Ocean. Relax on white-sand beaches, swim with dolphins, and explore the Seven Coloured Earths in Chamarel. Visit the capital Port Louis, hike to the Tamarind Falls, and taste Creole cuisine. This package includes a catamaran cruise, a visit to Île aux Cerfs, and a guided tour of the island.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Beach & Culture',
            'latitude' => -20.3484, 'longitude' => 57.5522,
        ]);
        $ghana = Destinations::create([
            'pricing' => '1200', 'currency' => 'USD',
            'title' => 'Accra & Cape Coast, Ghana',
            'description' => 'Warm hospitality, vibrant markets, haunting slave castles, and the beating heart of West African culture.',
            'content' => 'Ghana is West Africa\'s gem. Explore the bustling capital Accra, visit the Kwame Nkrumah Memorial, and shop at Makola Market. Travel to Cape Coast to tour the harrowing slave castles and learn about the transatlantic slave trade. Relax on the beaches of Kokrobite, and experience traditional drumming and dance. This tour includes a guided castle tour, a cultural village visit, and a drumming workshop.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-12 People', 'tour_type' => 'Historical & Cultural',
            'latitude' => 5.6037, 'longitude' => -0.1870,
        ]);
        $madagascar = Destinations::create([
            'pricing' => '1700', 'currency' => 'USD',
            'title' => 'Madagascar',
            'description' => 'A world apart — lemurs, baobab trees, rainforests, and wildlife found nowhere else on Earth.',
            'content' => 'Madagascar is nature\'s laboratory. Spot lemurs in Andasibe-Mantadia National Park, marvel at the Avenue of the Baobabs, and explore the Tsingy de Bemaraha stone forest. Relax on the beaches of Nosy Be, snorkel with sea turtles, and visit local villages. This tour includes guided national park visits, a baobab sunset tour, and a Nosy Be island escape.',
            'category_id' => $catWild->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '12 Days / 11 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Nature & Adventure',
            'latitude' => -18.7669, 'longitude' => 46.8691,
        ]);

        // MORE AMERICAS
        $toronto = Destinations::create([
            'pricing' => '1500', 'currency' => 'CAD',
            'title' => 'Toronto & Niagara Falls, Canada',
            'description' => 'A multicultural metropolis, stunning Niagara Falls, and the natural beauty of the Great Lakes.',
            'content' => 'Toronto is one of the world\'s most diverse cities. Visit the CN Tower, explore the Royal Ontario Museum, and wander through Kensington Market. Take a day trip to the awe-inspiring Niagara Falls, ride the Hornblower boat to the base of the falls, and explore Niagara-on-the-Lake wine country. This tour includes a Niagara Falls boat cruise, a wine tasting tour, and a Toronto city tour.',
            'category_id' => $catFamily->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-12 People', 'tour_type' => 'City & Nature',
            'latitude' => 43.6532, 'longitude' => -79.3832,
        ]);
        $havana = Destinations::create([
            'pricing' => '1100', 'currency' => 'USD',
            'title' => 'Havana, Cuba',
            'description' => 'Classic cars, colourful colonial architecture, salsa rhythms, and a city frozen in time.',
            'content' => 'Havana is a living museum. Walk through the four plazas of Old Havana, ride in a vintage 1950s convertible, and sip mojitos at the famous El Floridita. Visit the Fusterlandia mosaic park, watch a live salsa performance, and smoke a Cuban cigar at a tobacco plantation. This tour includes a classic car tour, a Havana club visit, and a cooking class.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Music',
            'latitude' => 23.1136, 'longitude' => -82.3666,
        ]);
        $patagonia = Destinations::create([
            'pricing' => '3500', 'currency' => 'USD',
            'title' => 'Patagonia, Argentina & Chile',
            'description' => 'Towering glaciers, jagged peaks, vast steppes, and some of the most dramatic landscapes on Earth.',
            'content' => 'Patagonia is a wilderness of epic proportions. Trek in Torres del Paine National Park, witness the Perito Moreno Glacier\'s icefalls, and explore the end-of-the-world city of Ushuaia. Kayak through fjords, spot guanacos and condors, and hike the W Trek. This tour includes a glacier trekking experience, a boat tour of the Beagle Channel, and guided hikes.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '14 Days / 13 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Nature',
            'latitude' => -41.8101, 'longitude' => -68.9063,
        ]);
        $orlando = Destinations::create([
            'pricing' => '1800', 'currency' => 'USD',
            'title' => 'Orlando, USA',
            'description' => 'Theme park capital of the world — Walt Disney World, Universal Studios, and endless family fun.',
            'content' => 'Orlando is every kid\'s dream. Explore the magic of Walt Disney World — Magic Kingdom, Epcot, Animal Kingdom, and Hollywood Studios. Experience the thrills of Universal Studios and Islands of Adventure. Cool off at water parks, shop at Disney Springs, and dine with characters. This package includes park hopper tickets, airport transfers, and accommodation.',
            'category_id' => $catFamily->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '2-8 People', 'tour_type' => 'Family & Entertainment',
            'latitude' => 28.5383, 'longitude' => -81.3792,
        ]);
        $cartagena = Destinations::create([
            'pricing' => '900', 'currency' => 'USD',
            'title' => 'Cartagena, Colombia',
            'description' => 'A Caribbean jewel — colourful colonial streets, salsa dancing, and the warmth of Colombian hospitality.',
            'content' => 'Cartagena is a city of colour. Walk through the UNESCO-listed Old Town\'s vibrant streets, visit the Castillo de San Felipe fortress, and explore the Getsemani neighbourhood\'s street art. Take a boat to the Rosario Islands for snorkelling, dance salsa until dawn, and enjoy fresh ceviche. This tour includes a guided city tour, a boat trip to the islands, and a salsa class.',
            'category_id' => $catFriends->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Beach',
            'latitude' => 10.3910, 'longitude' => -75.5144,
        ]);

        // OCEANIA MORE
        $auckland = Destinations::create([
            'pricing' => '1900', 'currency' => 'NZD',
            'title' => 'Auckland & Rotorua, New Zealand',
            'description' => 'Geothermal wonders, Maori culture, stunning harbours, and the adventure capital of the North Island.',
            'content' => 'Auckland is the City of Sails. Climb the Sky Tower, sail the Hauraki Gulf, and visit Waiheke Island\'s vineyards. Drive to Rotorua to experience Maori culture, see geysers and boiling mud pools, and soak in natural hot springs. Try zorbing, luging, and white-water rafting. This tour includes a Maori cultural evening, a geothermal park visit, and a harbour cruise.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Adventure & Culture',
            'latitude' => -36.8485, 'longitude' => 174.7633,
        ]);
        $cairns = Destinations::create([
            'pricing' => '2200', 'currency' => 'AUD',
            'title' => 'Cairns & Great Barrier Reef, Australia',
            'description' => 'The world\'s largest coral reef, ancient rainforests, and tropical paradise in Far North Queensland.',
            'content' => 'Cairns is the gateway to two natural wonders. Snorkel or dive the Great Barrier Reef — swim with sea turtles, clownfish, and colourful coral. Explore the Daintree Rainforest, the oldest tropical forest on Earth. Take the Skyrail over the canopy, visit Kuranda village, and relax on palm-fringed beaches. This tour includes a reef cruise, a Daintree tour, and an Aboriginal cultural experience.',
            'category_id' => $catNature->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '4-10 People', 'tour_type' => 'Nature & Beach',
            'latitude' => -16.9186, 'longitude' => 145.7781,
        ]);
        $tahiti = Destinations::create([
            'pricing' => '3500', 'currency' => 'XPF',
            'title' => 'Tahiti & Bora Bora, French Polynesia',
            'description' => 'The ultimate South Pacific dream — overwater bungalows, turquoise lagoons, and lush volcanic peaks.',
            'content' => 'French Polynesia is where dreams become reality. Stay in overwater bungalows in Bora Bora, swim with manta rays and sharks, and watch the sunset from Mount Otemanu. Explore Tahiti\'s black-sand beaches and waterfalls, and visit the Marae temples on Raiatea. This all-inclusive package covers inter-island flights, a lagoon tour, and a Polynesian cultural show.',
            'category_id' => $catSummer->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '2-6 People', 'tour_type' => 'Luxury & Beach',
            'latitude' => -17.6797, 'longitude' => -149.4068,
        ]);

        // MIDDLE EAST
        $istanbul = Destinations::create([
            'pricing' => '980', 'currency' => 'USD',
            'title' => 'Istanbul, Turkey',
            'description' => 'Where East meets West — Byzantine mosaics, Ottoman mosques, spice markets, and the magic of the Bosphorus.',
            'content' => 'Istanbul straddles two continents. Visit the Hagia Sophia, Blue Mosque, and Topkapi Palace. Explore the Grand Bazaar\'s 4,000 shops, cruise the Bosphorus Strait, and experience a Turkish bath. Feast on kebabs, baklava, and Turkish tea. This tour includes a guided historic peninsula walk, a Bosphorus dinner cruise, and a Turkish cooking class.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Historical & Cultural',
            'latitude' => 41.0082, 'longitude' => 28.9784,
        ]);
        $petra = Destinations::create([
            'pricing' => '1400', 'currency' => 'USD',
            'title' => 'Petra & Wadi Rum, Jordan',
            'description' => 'The rose-red city carved into rock, vast desert landscapes, and the warmest hospitality in the Middle East.',
            'content' => 'Jordan is a treasure trove of ancient wonders. Walk through the Siq to the Treasury in Petra, float in the Dead Sea, and explore the Roman ruins of Jerash. Spend a night in a Bedouin camp in Wadi Rum, watch the stars over the desert, and ride a camel through Martian landscapes. This tour includes a Petra guided tour, a Wadi Rum jeep safari, and a Dead Sea experience.',
            'category_id' => $catGap->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-12 People', 'tour_type' => 'Historical & Adventure',
            'latitude' => 30.3285, 'longitude' => 35.4444,
        ]);
        $muscat = Destinations::create([
            'pricing' => '1200', 'currency' => 'USD',
            'title' => 'Muscat & Salalah, Oman',
            'description' => 'Crystalline wadis, stunning deserts, pristine beaches, and Arabian hospitality at its finest.',
            'content' => 'Oman is the hidden gem of Arabia. Explore the Sultan Qaboos Grand Mosque, wander through the Mutrah Souq, and visit the Royal Opera House. Travel to the Wahiba Sands for a desert camp, swim in turquoise wadis, and see green turtles at Ras Al Jinz. This tour includes a desert safari, a wadi adventure, and a dhow cruise along the coast.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Adventure & Culture',
            'latitude' => 23.5880, 'longitude' => 58.3829,
        ]);

        // CARIBBEAN
        $cuba = Destinations::create([
            'pricing' => '1300', 'currency' => 'USD',
            'title' => 'Varadero & Trinidad, Cuba',
            'description' => 'Crystal-clear beaches, colonial charm, and the timeless rhythm of Cuban life.',
            'content' => 'Cuba captivates with its beauty and spirit. Relax on Varadero\'s 20-kilometre stretch of white sand, explore the cobblestone streets of Trinidad, and hike in the Topes de Collantes nature reserve. Dance salsa, smoke a cigar, and learn to make authentic Cuban cocktails. This tour includes a Trinidad day trip, a catamaran excursion, and a salsa lesson.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-12 People', 'tour_type' => 'Beach & Culture',
            'latitude' => 23.1136, 'longitude' => -82.3666,
        ]);
        $jamaica = Destinations::create([
            'pricing' => '1400', 'currency' => 'USD',
            'title' => 'Montego Bay & Negril, Jamaica',
            'description' => 'Reggae rhythms, jerk chicken, cascading waterfalls, and the laid-back island vibe of Jamaica.',
            'content' => 'Jamaica is pure positive energy. Climb Dunn\'s River Falls, float down the Martha Brae River on a bamboo raft, and relax on Seven Mile Beach. Visit Bob Marley\'s museum, taste authentic jerk chicken at a roadside stand, and enjoy a sunset catamaran cruise. This tour includes a falls climb, a river rafting experience, and a reggae night out.',
            'category_id' => $catFriends->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Beach & Music',
            'latitude' => 18.1096, 'longitude' => -77.2975,
        ]);
        $costarica = Destinations::create([
            'pricing' => '1600', 'currency' => 'USD',
            'title' => 'Costa Rica',
            'description' => 'Cloud forests, active volcanoes, Pacific and Caribbean beaches, and the purest pura vida lifestyle.',
            'content' => 'Costa Rica is biodiversity heaven. Zip-line through the Monteverde cloud forest, hike the Arenal Volcano, and relax in hot springs. White-water raft the Pacuare River, spot monkeys and toucans in Manuel Antonio, and surf at Santa Teresa. This tour includes a guided rainforest hike, a zip-lining adventure, and a wildlife boat tour.',
            'category_id' => $catNature->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '4-10 People', 'tour_type' => 'Nature & Adventure',
            'latitude' => 9.9281, 'longitude' => -84.0907,
        ]);

        // SCANDINAVIA / NORDIC
        $copenhagen = Destinations::create([
            'pricing' => '1350', 'currency' => 'DKK',
            'title' => 'Copenhagen, Denmark',
            'description' => 'Hygge living, world-class design, the Little Mermaid, and Scandinavia\'s most charming capital.',
            'content' => 'Copenhagen is the capital of cool. Visit Tivoli Gardens, explore the colourful Nyhavn harbour, and walk through the Freetown Christiania. Discover Danish design in the Designmuseum Denmark, taste smørrebrød and Danish pastries, and bike through the city like a local. This tour includes a canal tour, a Christiania visit, and a Danish cooking class.',
            'category_id' => $catCity->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Design',
            'latitude' => 55.6761, 'longitude' => 12.5683,
        ]);
        $stockholm = Destinations::create([
            'pricing' => '1200', 'currency' => 'EUR',
            'title' => 'Stockholm & Swedish Lapland, Sweden',
            'description' => 'A stunning archipelago, medieval old town, and the wild beauty of the Arctic north.',
            'content' => 'Stockholm is built on 14 islands connected by bridges. Explore the Gamla Stan old town, visit the Vasa Museum, and take a boat through the archipelago. Head north to Swedish Lapland for dog sledding, Northern Lights, and ice hotel stays. This tour includes an archipelago cruise, a museum tour, and a Lapland winter adventure.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Nature',
            'latitude' => 59.3293, 'longitude' => 18.0686,
        ]);

        // Attach tags for new destinations
        $prague->tags()->attach([$tagCity->id, $tagCulture->id, $tagFood->id]);
        $lisbon->tags()->attach([$tagCity->id, $tagFood->id, $tagCulture->id]);
        $vienna->tags()->attach([$tagCulture->id, $tagCity->id, $tagTravel->id]);
        $budapest->tags()->attach([$tagCity->id, $tagCulture->id, $tagTravel->id]);
        $reykjavik->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $swiss->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $rome->tags()->attach([$tagCulture->id, $tagCity->id, $tagFood->id]);
        $seoul->tags()->attach([$tagCity->id, $tagFood->id, $tagCulture->id]);
        $hanoi->tags()->attach([$tagAdventure->id, $tagFood->id, $tagNature->id]);
        $hongkong->tags()->attach([$tagCity->id, $tagFood->id, $tagTravel->id]);
        $jaipur->tags()->attach([$tagCulture->id, $tagTravel->id, $tagFood->id]);
        $singapore->tags()->attach([$tagCity->id, $tagFood->id, $tagTravel->id]);
        $kathmandu->tags()->attach([$tagAdventure->id, $tagNature->id, $tagCulture->id]);
        $victoria->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $mauritius->tags()->attach([$tagBeach->id, $tagNature->id, $tagTravel->id]);
        $ghana->tags()->attach([$tagCulture->id, $tagTravel->id, $tagAdventure->id]);
        $madagascar->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $toronto->tags()->attach([$tagCity->id, $tagNature->id, $tagTravel->id]);
        $havana->tags()->attach([$tagCulture->id, $tagCity->id, $tagTravel->id]);
        $patagonia->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $orlando->tags()->attach([$tagTravel->id, $tagCity->id, $tagAdventure->id]);
        $cartagena->tags()->attach([$tagCity->id, $tagBeach->id, $tagCulture->id]);
        $auckland->tags()->attach([$tagAdventure->id, $tagCulture->id, $tagNature->id]);
        $cairns->tags()->attach([$tagNature->id, $tagBeach->id, $tagAdventure->id]);
        $tahiti->tags()->attach([$tagBeach->id, $tagNature->id, $tagTravel->id]);
        $istanbul->tags()->attach([$tagCulture->id, $tagCity->id, $tagFood->id]);
        $petra->tags()->attach([$tagCulture->id, $tagAdventure->id, $tagTravel->id]);
        $muscat->tags()->attach([$tagAdventure->id, $tagCulture->id, $tagNature->id]);
        $cuba->tags()->attach([$tagBeach->id, $tagCulture->id, $tagTravel->id]);
        $jamaica->tags()->attach([$tagBeach->id, $tagTravel->id, $tagFood->id]);
        $costarica->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $copenhagen->tags()->attach([$tagCity->id, $tagCulture->id, $tagFood->id]);
        $stockholm->tags()->attach([$tagCity->id, $tagNature->id, $tagAdventure->id]);

        // MORE AFRICA II
        $rwanda = Destinations::create([
            'pricing' => '4000', 'currency' => 'USD',
            'title' => 'Rwanda — Gorilla Trekking',
            'description' => 'Trek through misty volcanoes to meet mountain gorillas — the most humbling wildlife experience on Earth.',
            'content' => 'Rwanda is the Land of a Thousand Hills. Trek into Volcanoes National Park to spend an unforgettable hour with a mountain gorilla family. Visit the Kigali Genocide Memorial, explore Nyungwe Forest\'s canopy walkway, and relax on the shores of Lake Kivu. This tour includes a gorilla trekking permit, guided trek, and a cultural village visit.',
            'category_id' => $catWild->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Wildlife & Nature',
            'latitude' => -1.9403, 'longitude' => 29.8739,
        ]);
        $senegal = Destinations::create([
            'pricing' => '1100', 'currency' => 'USD',
            'title' => 'Dakar & Saint-Louis, Senegal',
            'description' => 'Teranga hospitality, vibrant music scenes, pink lakes, and the westernmost point of Africa.',
            'content' => 'Senegal is the heartbeat of West Africa. Explore Dakar\'s vibrant markets, visit Goree Island\'s House of Slaves, and see the pink waters of Lake Retba. Travel to Saint-Louis, a UNESCO-listed colonial gem, and ride the waves at N\'Gor surf break. This tour includes a Goree Island ferry, a 4x4 Lake Retba tour, and a traditional mbalax dance night.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Beach',
            'latitude' => 14.7167, 'longitude' => -17.4677,
        ]);
        $namibia = Destinations::create([
            'pricing' => '2800', 'currency' => 'USD',
            'title' => 'Namibia — Desert & Dunes',
            'description' => 'The world\'s oldest desert, red sand dunes, shipwreck coasts, and the stark beauty of wild Africa.',
            'content' => 'Namibia is a photographer\'s paradise. Climb the towering red dunes of Sossusvlei at sunrise, explore the surreal Deadvlei clay pan, and see the Skeleton Coast\'s shipwrecks. Go game viewing in Etosha National Park, stay in a desert lodge, and stargaze in the darkest skies on Earth. This tour includes a Sossusvlei excursion, Etosha safari, and a scenic flight over the coast.',
            'category_id' => $catWild->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Nature & Photography',
            'latitude' => -22.9576, 'longitude' => 18.4904,
        ]);
        $seychelles = Destinations::create([
            'pricing' => '3000', 'currency' => 'EUR',
            'title' => 'Seychelles',
            'description' => 'Granite boulders, the world\'s most beautiful beaches, giant tortoises, and pure Indian Ocean paradise.',
            'content' => 'Seychelles is a castaway dream. Relax on Anse Source d\'Argent, one of the world\'s most photographed beaches. Hike through the Vallée de Mai nature reserve, home to the giant Coco de Mer palm. Snorkel with sea turtles at Sainte Anne Marine Park, and visit the giant tortoise sanctuary on Curieuse Island. This package includes island-hopping boat transfers, a snorkelling cruise, and guided nature walks.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '2-6 People', 'tour_type' => 'Beach & Nature',
            'latitude' => -4.6796, 'longitude' => 55.4920,
        ]);

        // MORE ASIA II
        $srilanka = Destinations::create([
            'pricing' => '900', 'currency' => 'USD',
            'title' => 'Sri Lanka',
            'description' => 'Tea plantations, ancient cities, leopard-filled national parks, and stunning beaches in the pearl of the Indian Ocean.',
            'content' => 'Sri Lanka is magic in a teardrop. Climb the ancient Sigiriya rock fortress, explore the Temple of the Tooth in Kandy, and go on safari in Yala National Park. Ride the scenic train through tea country, sample Ceylon tea, and surf on the south coast beaches. This tour includes a guided cultural triangle tour, a wildlife safari, and a train ride through the hills.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '6-12 People', 'tour_type' => 'Cultural & Nature',
            'latitude' => 7.8731, 'longitude' => 80.7718,
        ]);
        $philippines = Destinations::create([
            'pricing' => '1100', 'currency' => 'USD',
            'title' => 'Palawan, Philippines',
            'description' => 'Limestone karsts, hidden lagoons, pristine coral reefs, and the most beautiful island in the world.',
            'content' => 'Palawan is an archipelago of dreams. Cruise through the underground river in Puerto Princesa, explore the lagoons of El Nido, and island-hop in Coron\'s crystal-clear waters. Snorkel over Japanese shipwrecks, relax on hidden beaches, and kayak through secret lagoons. This tour includes an underground river tour, El Nido island hopping, and Coron wreck diving.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Beach & Adventure',
            'latitude' => 9.7500, 'longitude' => 118.7500,
        ]);
        $samarkand = Destinations::create([
            'pricing' => '800', 'currency' => 'USD',
            'title' => 'Samarkand & Bukhara, Uzbekistan',
            'description' => 'Silk Road splendour — turquoise domes, caravanserais, and the legendary cities of the ancient trade route.',
            'content' => 'Uzbekistan is the heart of the Silk Road. Stand in awe before the Registan in Samarkand, explore the Ark of Bukhara, and wander through the blue-tiled Shah-i-Zinda. Visit the old trading domes, sample plov (the national dish), and stay in a traditional guesthouse. This tour includes a guided Samarkand tour, a Bukhara walking tour, and a traditional Uzbek dinner.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-12 People', 'tour_type' => 'Historical & Cultural',
            'latitude' => 39.6270, 'longitude' => 66.9746,
        ]);
        $malaysia = Destinations::create([
            'pricing' => '1000', 'currency' => 'USD',
            'title' => 'Kuala Lumpur & Borneo, Malaysia',
            'description' => 'Petronas Towers, rainforests, orangutans, and a melting pot of Malay, Chinese, and Indian cultures.',
            'content' => 'Malaysia offers city and jungle. Explore Kuala Lumpur\'s Petronas Towers and Batu Caves, then fly to Borneo for an entirely different world. See orangutans in Sepilok, dive in Sipadan\'s pristine waters, and trek through the Danum Valley rainforest. This tour includes a KL city tour, an orangutan sanctuary visit, and a Borneo jungle trek.',
            'category_id' => $catNature->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Nature & City',
            'latitude' => 3.1390, 'longitude' => 101.6869,
        ]);
        $bhutan = Destinations::create([
            'pricing' => '3200', 'currency' => 'USD',
            'title' => 'Bhutan — The Happiness Kingdom',
            'description' => 'Dragon temples, tiger\'s nest monasteries, pristine valleys, and a country that measures Gross National Happiness.',
            'content' => 'Bhutan is unlike anywhere else. Hike to the iconic Tiger\'s Nest Monastery (Paro Taktsang), visit the Punakha Dzong, and explore the capital Thimphu. Witness traditional mask dances, visit a local farmhouse, and soak in the breathtaking Himalayan views. This tour includes a Tiger\'s Nest hike, guided dzong tours, and a traditional hot stone bath.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Cultural & Spiritual',
            'latitude' => 27.5142, 'longitude' => 90.4336,
        ]);
        
        // MORE EUROPE II
        $dubrovnik = Destinations::create([
            'pricing' => '850', 'currency' => 'EUR',
            'title' => 'Dubrovnik & Dalmatian Coast, Croatia',
            'description' => 'Medieval walls, Adriatic islands, Game of Thrones scenery, and the best seafood on the Mediterranean.',
            'content' => 'Croatia\'s Dalmatian Coast is spectacular. Walk the ancient walls of Dubrovnik, take a ferry to the lavender-scented Hvar island, and explore Diocletian\'s Palace in Split. Kayak around the Elaphiti Islands, swim in the crystal-clear Adriatic, and enjoy fresh octopus and oysters. This tour includes a Dubrovnik city tour, an island-hopping boat trip, and a wine tasting in Pelješac.',
            'category_id' => $catFriends->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Beach',
            'latitude' => 42.6507, 'longitude' => 18.0944,
        ]);
        $scotland = Destinations::create([
            'pricing' => '1300', 'currency' => 'GBP',
            'title' => 'Edinburgh & Highlands, Scotland',
            'description' => 'Misty lochs, ancient castles, whisky trails, and landscapes that inspired legends.',
            'content' => 'Scotland is a land of epic beauty. Explore Edinburgh Castle and the Royal Mile, then head north to the Highlands. Drive through Glencoe, cruise Loch Ness, and visit the Isle of Skye\'s fairy pools. Tour a Scotch whisky distillery, see the Kelpies, and hear bagpipes echo through the glens. This tour includes an Edinburgh walking tour, a Highlands road trip, and a whisky tasting.',
            'category_id' => $catNature->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Nature & Historical',
            'latitude' => 55.9533, 'longitude' => -3.1883,
        ]);
        $norway = Destinations::create([
            'pricing' => '2500', 'currency' => 'EUR',
            'title' => 'Norwegian Fjords',
            'description' => 'Dramatic fjords, towering waterfalls, Arctic wildlife, and the magical Northern Lights.',
            'content' => 'Norway\'s fjords are nature at its most dramatic. Cruise through Geirangerfjord and Nærøyfjord, hike the famous Trolltunga and Preikestolen cliffs, and explore the colourful wooden houses of Bergen. In winter, chase the Northern Lights from Tromsø, go husky sledding, and visit the Snow Hotel. This tour includes a fjord cruise, a guided hike, and a Northern Lights hunt.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Nature',
            'latitude' => 60.4720, 'longitude' => 8.4689,
        ]);
        $dublin = Destinations::create([
            'pricing' => '750', 'currency' => 'EUR',
            'title' => 'Dublin & Wild Atlantic Way, Ireland',
            'description' => 'Pub culture, dramatic cliffs, ancient castles, and the warmest welcome in the Emerald Isle.',
            'content' => 'Ireland is pure craic. Explore Dublin\'s Trinity College and Temple Bar, then drive the Wild Atlantic Way. Visit the Cliffs of Moher, the Ring of Kerry, and the Dingle Peninsula. Tour the Old Jameson Distillery, kiss the Blarney Stone, and enjoy live folk music in a cosy pub. This tour includes a guided city walk, a Wild Atlantic Way road trip, and a pub crawl.',
            'category_id' => $catFriends->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Road Trip',
            'latitude' => 53.3498, 'longitude' => -6.2603,
        ]);

        // MORE AMERICAS II
        $sanfran = Destinations::create([
            'pricing' => '1600', 'currency' => 'USD',
            'title' => 'San Francisco & Yosemite, USA',
            'description' => 'The Golden Gate, Alcatraz, Silicon Valley, and the awe-inspiring granite cliffs of Yosemite.',
            'content' => 'San Francisco is a city of hills and innovation. Ride a cable car across the city, visit Alcatraz Island, and walk across the Golden Gate Bridge. Drive to Yosemite National Park to see El Capitan, Half Dome, and Bridalveil Fall. This tour includes an Alcatraz tour, a Golden Gate bike ride, and a guided Yosemite hike.',
            'category_id' => $catCity->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-10 People', 'tour_type' => 'City & Nature',
            'latitude' => 37.7749, 'longitude' => -122.4194,
        ]);
        $galapagos = Destinations::create([
            'pricing' => '4500', 'currency' => 'USD',
            'title' => 'Galápagos Islands, Ecuador',
            'description' => 'Darwin\'s living laboratory — giant tortoises, marine iguanas, blue-footed boobies, and fearless wildlife.',
            'content' => 'The Galápagos Islands are a wildlife wonderland. Cruise between islands to see giant tortoises, marine iguanas, blue-footed boobies, and Galápagos penguins. Snorkel with sea lions and hammerhead sharks, hike volcanic landscapes, and visit the Charles Darwin Research Station. This tour includes an island cruise, guided nature walks, and daily snorkelling excursions.',
            'category_id' => $catWild->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '6-12 People', 'tour_type' => 'Wildlife & Nature',
            'latitude' => -0.3833, 'longitude' => -90.3167,
        ]);
        $losangeles = Destinations::create([
            'pricing' => '1700', 'currency' => 'USD',
            'title' => 'Los Angeles, USA',
            'description' => 'Hollywood glamour, stunning beaches, world-class museums, and the entertainment capital of the world.',
            'content' => 'LA is a city of dreams. Walk the Hollywood Walk of Fame, hike to the Griffith Observatory, and tour a movie studio. Relax on Santa Monica and Venice Beaches, browse the Getty Center, and spot celebs in Beverly Hills. Drive along the stunning Pacific Coast Highway to Malibu. This tour includes a studio tour, a guided city tour, and a day trip to Disneyland.',
            'category_id' => $catCity->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'City & Entertainment',
            'latitude' => 34.0522, 'longitude' => -118.2437,
        ]);

        // LATIN AMERICA MORE
        $bogota = Destinations::create([
            'pricing' => '800', 'currency' => 'USD',
            'title' => 'Bogotá & Coffee Region, Colombia',
            'description' => 'Colonial charm, world-famous coffee, colourful valleys, and the rhythm of Colombia.',
            'content' => 'Colombia\'s Coffee Region is a paradise of green hills. Explore Bogotá\'s La Candelaria district and Gold Museum, then travel to Salento for the iconic Cocora Valley with its towering wax palms. Tour a coffee finca, hike through the valley, and visit the colourful town of Filandia. This tour includes a coffee tour, a Cocora Valley hike, and a Bogotá street art walk.',
            'category_id' => $catNature->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Nature & Culture',
            'latitude' => 4.7110, 'longitude' => -74.0721,
        ]);
        $chichen = Destinations::create([
            'pricing' => '1200', 'currency' => 'USD',
            'title' => 'Chichén Itzá & Yucatán, Mexico',
            'description' => 'Mayan pyramids, colonial cities, cenotes, and the rich cultural tapestry of the Yucatán Peninsula.',
            'content' => 'The Yucatán is steeped in Mayan history. Marvel at the Kukulcán Pyramid in Chichén Itzá, swim in sacred cenotes with crystal-clear waters, and explore the colonial city of Mérida. Visit the Uxmal ruins, spot flamingos in Celestún, and taste cochinita pibil. This tour includes a Chichén Itzá guided tour, a cenote swim, and a Mérida walking tour.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-12 People', 'tour_type' => 'Historical & Adventure',
            'latitude' => 20.6843, 'longitude' => -88.5678,
        ]);

        // MIDDLE EAST II
        $doha = Destinations::create([
            'pricing' => '1500', 'currency' => 'USD',
            'title' => 'Doha, Qatar',
            'description' => 'Futuristic skyline, traditional souqs, desert dunes, and world-class museums on the Arabian Gulf.',
            'content' => 'Doha is a city of contrasts. Visit the Museum of Islamic Art and the National Museum of Qatar, explore the Souq Waqif, and take a traditional dhow cruise. Go dune bashing in the Khor Al Adaid desert, visit the Katara Cultural Village, and shop at the Pearl-Qatar. This tour includes a desert safari, a museum tour, and a guided city exploration.',
            'category_id' => $catCity->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '6-10 People', 'tour_type' => 'City & Culture',
            'latitude' => 25.2854, 'longitude' => 51.5310,
        ]);
        $beirut = Destinations::create([
            'pricing' => '900', 'currency' => 'USD',
            'title' => 'Beirut & Byblos, Lebanon',
            'description' => 'Phoenician ruins, world-class cuisine, vibrant nightlife, and the resilient spirit of the Mediterranean Levant.',
            'content' => 'Lebanon is a Mediterranean gem. Explore Beirut\'s Corniche and vibrant Gemmayzeh district, visit the ruins of Byblos and Baalbek, and ski in the mountains of Faraya Mzaar. Taste the legendary Lebanese mezze, visit the Jeita Grotto, and explore the ancient cedar forests. This tour includes a guided archaeological tour, a wine tasting in the Bekaa Valley, and a Beirut food crawl.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Food',
            'latitude' => 33.8938, 'longitude' => 35.5018,
        ]);

        // INDIAN OCEAN
        $comoros = Destinations::create([
            'pricing' => '1400', 'currency' => 'EUR',
            'title' => 'Comoros Islands',
            'description' => 'Volcanic islands, fragrant ylang-ylang plantations, coral reefs, and one of Africa\'s best-kept secrets.',
            'content' => 'The Comoros are the perfume islands. Explore the active volcano Mount Karthala on Grande Comore, relax on the beaches of Mohéli, and visit the ylang-ylang distilleries of Anjouan. Snorkel with sea turtles and humpback whales, hike through rainforests, and experience the unique Swahili-Arabic culture. This package includes inter-island flights, guided volcano trek, and snorkelling excursions.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Beach & Nature',
            'latitude' => -11.6455, 'longitude' => 43.3333,
        ]);

        // Attach tags for new destinations
        $rwanda->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $senegal->tags()->attach([$tagCulture->id, $tagBeach->id, $tagTravel->id]);
        $namibia->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $seychelles->tags()->attach([$tagBeach->id, $tagNature->id, $tagTravel->id]);
        $srilanka->tags()->attach([$tagNature->id, $tagCulture->id, $tagAdventure->id]);
        $philippines->tags()->attach([$tagBeach->id, $tagAdventure->id, $tagNature->id]);
        $samarkand->tags()->attach([$tagCulture->id, $tagTravel->id, $tagAdventure->id]);
        $malaysia->tags()->attach([$tagNature->id, $tagCity->id, $tagFood->id]);
        $bhutan->tags()->attach([$tagCulture->id, $tagNature->id, $tagAdventure->id]);
        $dubrovnik->tags()->attach([$tagCity->id, $tagBeach->id, $tagFood->id]);
        $scotland->tags()->attach([$tagNature->id, $tagCulture->id, $tagAdventure->id]);
        $norway->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $dublin->tags()->attach([$tagCity->id, $tagCulture->id, $tagFood->id]);
        $sanfran->tags()->attach([$tagCity->id, $tagNature->id, $tagTravel->id]);
        $galapagos->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $losangeles->tags()->attach([$tagCity->id, $tagTravel->id, $tagBeach->id]);
        $bogota->tags()->attach([$tagNature->id, $tagCulture->id, $tagFood->id]);
        $chichen->tags()->attach([$tagCulture->id, $tagAdventure->id, $tagTravel->id]);
        $doha->tags()->attach([$tagCity->id, $tagCulture->id, $tagTravel->id]);
        $beirut->tags()->attach([$tagCity->id, $tagFood->id, $tagCulture->id]);
        $comoros->tags()->attach([$tagBeach->id, $tagNature->id, $tagTravel->id]);

        // EUROPE III
        $madrid = Destinations::create([
            'pricing' => '850', 'currency' => 'EUR',
            'title' => 'Madrid & Seville, Spain',
            'description' => 'World-class art, flamenco passion, tapas culture, and the sun-drenched soul of southern Europe.',
            'content' => 'Spain pulses with life. Explore the Prado Museum and Royal Palace in Madrid, then take the AVE train to Seville for the Alcázar, Plaza de España, and a live flamenco show. Taste jamón ibérico, sip sangría, and wander through Santa Cruz\'s orange-scented alleys. This tour includes a guided Madrid art walk, a Seville tapas crawl, and a flamenco workshop.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Food',
            'latitude' => 40.4168, 'longitude' => -3.7038,
        ]);
        $tuscany = Destinations::create([
            'pricing' => '1500', 'currency' => 'EUR',
            'title' => 'Tuscany, Italy',
            'description' => 'Rolling vineyards, medieval hilltop towns, Renaissance art, and the dolce vita lifestyle of central Italy.',
            'content' => 'Tuscany is the heart of Italy. Explore Florence\'s Uffizi Gallery and Duomo, visit the Leaning Tower of Pisa, and wander through Siena\'s medieval streets. Drive through the Chianti wine region, stay in a farmhouse agriturismo, and taste truffles, olive oil, and world-class wines. This tour includes a Florence guided tour, a wine-tasting day in Chianti, and a cooking class in a Tuscan villa.',
            'category_id' => $catLuxury->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Food & Wine',
            'latitude' => 43.4069, 'longitude' => 11.2899,
        ]);
        $munich = Destinations::create([
            'pricing' => '950', 'currency' => 'EUR',
            'title' => 'Munich & Bavaria, Germany',
            'description' => 'Oktoberfest, fairy-tale castles, Alpine views, and the best beer culture in the world.',
            'content' => 'Bavaria is Germany at its most charming. Visit Munich\'s Marienplatz and Hofbräuhaus, tour the Neuschwanstein Castle that inspired Disney, and explore the picturesque villages of the Romantic Road. Hike in the Bavarian Alps, visit the Dachau memorial, and enjoy a stein of beer in a traditional beer garden. This tour includes a Neuschwanstein visit, a Munich walking tour, and a beer tasting.',
            'category_id' => $catFriends->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '8-12 People', 'tour_type' => 'Cultural & Beer',
            'latitude' => 48.1351, 'longitude' => 11.5820,
        ]);
        $helsinki = Destinations::create([
            'pricing' => '980', 'currency' => 'EUR',
            'title' => 'Helsinki & Finnish Lakeland',
            'description' => 'Sauna culture, midnight sun, pristine lakes, and the cleanest air in Europe.',
            'content' => 'Finland is the land of a thousand lakes. Explore Helsinki\'s design district and Suomenlinna sea fortress, then head to the Lakeland region for sauna, swimming, and canoeing. In winter, stay in a glass igloo watching the Northern Lights, go husky sledding, and visit Santa Claus Village in Rovaniemi. This tour includes a sauna experience, a lakeland canoe trip, and a Northern Lights chase.',
            'category_id' => $catNature->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Nature & Adventure',
            'latitude' => 60.1699, 'longitude' => 24.9384,
        ]);

        // ASIA III
        $cambodia = Destinations::create([
            'pricing' => '800', 'currency' => 'USD',
            'title' => 'Angkor Wat, Cambodia',
            'description' => 'The world\'s largest religious monument — ancient temple cities emerging from the jungle.',
            'content' => 'Angkor Wat is a wonder of the world. Watch sunrise over the iconic temple, explore the jungle-shrouded Ta Prohm (the Tomb Raider temple), and cycle through Angkor Thom\'s Bayon with its stone faces. Visit the floating villages of Tonlé Sap Lake and learn about Cambodia\'s history in Siem Reap. This tour includes a multi-day Angkor pass, guided temple tours, and a floating village visit.',
            'category_id' => $catGap->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-12 People', 'tour_type' => 'Historical & Cultural',
            'latitude' => 13.4125, 'longitude' => 103.8670,
        ]);
        $taiwan = Destinations::create([
            'pricing' => '1200', 'currency' => 'USD',
            'title' => 'Taipei & Taroko Gorge, Taiwan',
            'description' => 'Night markets, hot springs, marble gorges, and one of Asia\'s most underrated travel destinations.',
            'content' => 'Taiwan is a hidden gem. Explore Taipei 101, the National Palace Museum, and the bustling Shilin Night Market. Take the scenic train to Hualien for the breathtaking Taroko Gorge — marble cliffs, hiking trails, and suspension bridges. Soak in hot springs, visit the rainbow village, and taste beef noodle soup and bubble tea. This tour includes a Taipei city tour, Taroko Gorge hike, and a food crawl.',
            'category_id' => $catCity->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-10 People', 'tour_type' => 'City & Nature',
            'latitude' => 25.0330, 'longitude' => 121.5654,
        ]);
        $mongolia = Destinations::create([
            'pricing' => '1800', 'currency' => 'USD',
            'title' => 'Mongolia — Gobi Desert & Steppes',
            'description' => 'Endless steppes, nomadic culture, dinosaur fossils, and the raw beauty of the last true wilderness.',
            'content' => 'Mongolia is adventure on an epic scale. Ride horses across the steppes with a nomadic herder family, explore the flaming cliffs of the Gobi Desert where dinosaur eggs were discovered, and stay in traditional ger camps. Visit the capital Ulaanbaatar, see the Khustai Przewalski\'s horses, and float down the Orkhon River. This tour includes a nomadic homestay, a Gobi Desert expedition, and a horseback riding adventure.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '12 Days / 11 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Culture',
            'latitude' => 47.8864, 'longitude' => 106.9057,
        ]);
        $myanmar = Destinations::create([
            'pricing' => '750', 'currency' => 'USD',
            'title' => 'Bagan & Inle Lake, Myanmar',
            'description' => 'Thousands of ancient temples dotting the plains, floating gardens, and the timeless charm of Burma.',
            'content' => 'Myanmar is a land of golden pagodas. Watch sunrise over Bagan\'s temple-studded plains from a hot air balloon, explore the floating gardens and leg-rowing fishermen of Inle Lake, and visit the golden Shwedagon Pagoda in Yangon. This tour includes a Bagan temple tour, a hot air balloon ride, and an Inle Lake boat excursion.',
            'category_id' => $catGap->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Historical & Cultural',
            'latitude' => 21.9162, 'longitude' => 95.9560,
        ]);

        // AFRICA III
        $serengeti = Destinations::create([
            'pricing' => '3500', 'currency' => 'USD',
            'title' => 'Serengeti & Ngorongoro, Tanzania',
            'description' => 'The Great Migration, the Big Five, the world\'s largest caldera, and Africa\'s ultimate safari.',
            'content' => 'Tanzania is safari heaven. Witness the Great Migration in the Serengeti — millions of wildebeest and zebras crossing crocodile-filled rivers. Descend into the Ngorongoro Crater for incredible game viewing, and relax on Zanzibar\'s beaches afterwards. See lions, leopards, elephants, rhinos, and buffalo. This tour includes daily game drives, a crater descent, and a hot air balloon safari over the Serengeti.',
            'category_id' => $catWild->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Wildlife & Safari',
            'latitude' => -2.3333, 'longitude' => 34.8333,
        ]);
        $ethiopia = Destinations::create([
            'pricing' => '1500', 'currency' => 'USD',
            'title' => 'Lalibela & Simien Mountains, Ethiopia',
            'description' => 'Rock-hewn churches, prehistoric landscapes, and the cradle of humanity.',
            'content' => 'Ethiopia is a world apart. Marvel at the 12th-century rock-hewn churches of Lalibela, trek through the dramatic Simien Mountains with gelada baboons, and visit the Danakil Depression — one of the hottest and most surreal places on Earth. Taste Ethiopian coffee, eat injera, and experience a unique calendar and alphabet. This tour includes a Lalibela guided tour, a Simien trek, and a coffee ceremony.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Historical & Adventure',
            'latitude' => 9.1450, 'longitude' => 40.4897,
        ]);
        $botswana = Destinations::create([
            'pricing' => '4000', 'currency' => 'USD',
            'title' => 'Okavango Delta, Botswana',
            'description' => 'Africa\'s last Eden — pristine waterways, elephants, and the most exclusive safari experience on the continent.',
            'content' => 'Botswana offers the ultimate wilderness experience. Explore the Okavango Delta by mokoro (dugout canoe), track wild dogs and lions in Moremi Game Reserve, and see vast herds of elephants in Chobe National Park. Stay in luxury tented camps, sleep under the stars, and witness Africa at its wildest. This tour includes daily game drives, a mokoro excursion, and a helicopter flight over the delta.',
            'category_id' => $catLuxury->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '4-6 People', 'tour_type' => 'Wildlife & Luxury',
            'latitude' => -22.3285, 'longitude' => 24.6849,
        ]);

        // AMERICAS III
        $banff = Destinations::create([
            'pricing' => '2200', 'currency' => 'CAD',
            'title' => 'Banff & Jasper, Canadian Rockies',
            'description' => 'Turquoise lakes, towering peaks, grizzly bears, and some of the most jaw-dropping scenery on Earth.',
            'content' => 'The Canadian Rockies are spectacular. Drive the Icefields Parkway connecting Banff and Jasper, hike to Lake Louise and Moraine Lake\'s turquoise waters, and spot bears and elk. Soak in the Banff hot springs, walk on the Athabasca Glacier, and take a gondola up Sulphur Mountain. This tour includes guided hikes, a Columbia Icefield adventure, and a wildlife tour.',
            'category_id' => $catNature->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Nature & Adventure',
            'latitude' => 51.1784, 'longitude' => -115.5708,
        ]);
        $buenosaires = Destinations::create([
            'pricing' => '1400', 'currency' => 'USD',
            'title' => 'Buenos Aires & Iguazú, Argentina',
            'description' => 'Tango, steak, wine, and the most powerful waterfalls in the world.',
            'content' => 'Argentina is a land of passion. Explore Buenos Aires\' colourful La Boca, Recoleta Cemetery, and Palermo Soho. Take a tango lesson, eat the best steak of your life, and drink Malbec. Fly to Iguazú to witness 275 waterfalls thundering into the Devil\'s Throat. This tour includes a Buenos Aires city tour, a tango show and dinner, and a full-day Iguazú Falls exploration.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Nature',
            'latitude' => -34.6037, 'longitude' => -58.3816,
        ]);
        $salar = Destinations::create([
            'pricing' => '1800', 'currency' => 'USD',
            'title' => 'Salar de Uyuni & La Paz, Bolivia',
            'description' => 'The world\'s largest salt flat, mirror skies, colourful lagoons, and the highest capital city on Earth.',
            'content' => 'Bolivia is surreal. Walk across the otherworldly Salar de Uyuni — a vast salt flat that becomes a giant mirror after rain. See flamingos on colourful high-altitude lagoons, explore the Valle de la Luna, and ride the cable cars of La Paz. Visit the eerie Train Cemetery and stay in a salt hotel. This tour includes a Uyuni 4x4 excursion, a La Paz city tour, and a visit to the Moon Valley.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Nature',
            'latitude' => -20.1338, 'longitude' => -67.4891,
        ]);

        // CARIBBEAN & ISLANDS II
        $dominican = Destinations::create([
            'pricing' => '1200', 'currency' => 'USD',
            'title' => 'Dominican Republic — Punta Cana',
            'description' => 'Endless white-sand beaches, all-inclusive resorts, merengue beats, and Caribbean bliss.',
            'content' => 'The Dominican Republic is the Caribbean at its finest. Relax on Bavaro\'s palm-fringed beaches, swim in the Hoyo Azul natural pool, and take a catamaran to Saona Island. Explore the historic Zona Colonial in Santo Domingo, zip-line through the jungle, and dance merengue under the stars. This package includes airport transfers, all-inclusive resort stay, and a Saona Island excursion.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '2-8 People', 'tour_type' => 'Beach & Relaxation',
            'latitude' => 18.7357, 'longitude' => -70.1627,
        ]);
        $barbados = Destinations::create([
            'pricing' => '1600', 'currency' => 'BBD',
            'title' => 'Barbados',
            'description' => 'Coral sands, rum punches, flying fish, and the friendliest island in the Caribbean.',
            'content' => 'Barbados is pure Caribbean joy. Relax on Crane Beach, swim with sea turtles, and visit Harrison\'s Cave. Tour a rum distillery, explore the historic Garrison Savannah, and eat flying fish and cou-cou. Take a catamaran along the west coast, snorkel shipwrecks, and experience the Crop Over festival. This package includes a turtle snorkel tour, a rum distillery visit, and a guided island tour.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Beach & Culture',
            'latitude' => 13.1939, 'longitude' => -59.5432,
        ]);

        // OCEANIA III
        $tasmania = Destinations::create([
            'pricing' => '1800', 'currency' => 'AUD',
            'title' => 'Tasmania, Australia',
            'description' => 'Wilderness, convict history, world-class whisky, and the cleanest air on the planet.',
            'content' => 'Tasmania is Australia\'s wild isle. Hike through Cradle Mountain-Lake St Clair National Park, explore the Museum of Old and New Art (MONA) in Hobart, and visit the UNESCO-listed Port Arthur penal settlement. Taste oysters, cheese, and whisky on the Tasmanian Food Trail, spot wombats and Tasmanian devils, and kayak on Wineglass Bay. This tour includes a Cradle Mountain trek, a MONA visit, and a food and whisky trail.',
            'category_id' => $catNature->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Nature & Food',
            'latitude' => -42.8821, 'longitude' => 147.3272,
        ]);
        $png = Destinations::create([
            'pricing' => '3500', 'currency' => 'USD',
            'title' => 'Papua New Guinea',
            'description' => 'Tribal cultures, coral reefs, rugged highlands, and one of the most culturally diverse places on Earth.',
            'content' => 'Papua New Guinea is for the truly adventurous. Attend a colourful sing-sing festival with warriors in traditional dress, dive some of the world\'s best coral reefs, and trek the Kokoda Track through dense jungle. Visit remote highland villages, see birds of paradise, and experience 800+ languages in one country. This tour includes a Kokoda Track trek, a diving trip, and a cultural festival visit.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '14 Days / 13 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Culture',
            'latitude' => -6.3150, 'longitude' => 143.9555,
        ]);

        // POLAR
        $greenland = Destinations::create([
            'pricing' => '4500', 'currency' => 'EUR',
            'title' => 'Greenland — Arctic Explorer',
            'description' => 'Massive icebergs, remote Inuit settlements, dog sledding, and the raw majesty of the Arctic.',
            'content' => 'Greenland is the ultimate frontier. Cruise between towering icebergs in Ilulissat Icefjord, visit remote Inuit villages, and dog sled across the frozen tundra. See the Northern Lights dance across the Arctic sky, hike on the Greenland ice cap, and spot whales and polar bears. This tour includes a boat tour among icebergs, a dog sledding excursion, and a guided hike on the ice cap.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Polar',
            'latitude' => 71.7069, 'longitude' => -42.6043,
        ]);
        
        // Attach tags
        $madrid->tags()->attach([$tagCulture->id, $tagCity->id, $tagFood->id]);
        $tuscany->tags()->attach([$tagFood->id, $tagCulture->id, $tagTravel->id]);
        $munich->tags()->attach([$tagCity->id, $tagFood->id, $tagCulture->id]);
        $helsinki->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $cambodia->tags()->attach([$tagCulture->id, $tagTravel->id, $tagAdventure->id]);
        $taiwan->tags()->attach([$tagFood->id, $tagCity->id, $tagNature->id]);
        $mongolia->tags()->attach([$tagAdventure->id, $tagNature->id, $tagTravel->id]);
        $myanmar->tags()->attach([$tagCulture->id, $tagTravel->id, $tagAdventure->id]);
        $serengeti->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $ethiopia->tags()->attach([$tagCulture->id, $tagAdventure->id, $tagTravel->id]);
        $botswana->tags()->attach([$tagNature->id, $tagLuxury->id, $tagAdventure->id]);
        $banff->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $buenosaires->tags()->attach([$tagCity->id, $tagFood->id, $tagCulture->id]);
        $salar->tags()->attach([$tagAdventure->id, $tagNature->id, $tagTravel->id]);
        $dominican->tags()->attach([$tagBeach->id, $tagTravel->id, $tagNature->id]);
        $barbados->tags()->attach([$tagBeach->id, $tagFood->id, $tagTravel->id]);
        $tasmania->tags()->attach([$tagNature->id, $tagAdventure->id, $tagFood->id]);
        $png->tags()->attach([$tagAdventure->id, $tagCulture->id, $tagNature->id]);
        $greenland->tags()->attach([$tagAdventure->id, $tagNature->id, $tagTravel->id]);

        // EUROPE IV
        $warsaw = Destinations::create([
            'pricing' => '550', 'currency' => 'PLN',
            'title' => 'Warsaw & Krakow, Poland',
            'description' => 'Resilient cities, medieval squares, salt mines, and the heart of Polish history and culture.',
            'content' => 'Poland is a story of survival and rebirth. Explore Warsaw\'s reconstructed Old Town, visit the POLIN Museum, and see the Palace of Culture. Travel to Krakow for the stunning Rynek Główny square, Wawel Castle, and the Wieliczka Salt Mine. Visit the solemn Auschwitz-Birkenau memorial. This tour includes a Krakow walking tour, a salt mine visit, and an Auschwitz guided tour.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '8-12 People', 'tour_type' => 'Historical & Cultural',
            'latitude' => 52.2297, 'longitude' => 21.0122,
        ]);
        $porto = Destinations::create([
            'pricing' => '650', 'currency' => 'EUR',
            'title' => 'Porto & Douro Valley, Portugal',
            'description' => 'Port wine, ribeira charm, azulejo tiles, and terraced vineyards along the Douro River.',
            'content' => 'Porto is Portugal\'s soulful northern gem. Explore the Ribeira district, visit the Livraria Lello bookstore, and tour a port wine cellar across the river in Vila Nova de Gaia. Take a scenic train through the Douro Valley, taste wines at a quinta, and cruise the Douro River. This tour includes a port wine tasting, a Douro Valley day trip, and a guided city walk.',
            'category_id' => $catWeekend->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Wine',
            'latitude' => 41.1579, 'longitude' => -8.6291,
        ]);
        $bali2 = Destinations::create([
            'pricing' => '680', 'currency' => 'EUR',
            'title' => 'Slovenia — Lake Bled & Ljubljana',
            'description' => 'Emerald lakes, Alpine peaks, a fairy-tale island church, and Europe\'s greenest capital.',
            'content' => 'Slovenia is Europe\'s hidden treasure. Row to the tiny island church on Lake Bled, hike through Triglav National Park, and explore Ljubljana\'s charming old town with its dragon bridge and castle. Visit the Postojna Cave, taste world-class wines in the Brda region, and experience the magic of the Soča River Valley. This tour includes a Lake Bled boat ride, a cave tour, and a Ljubljana food walk.',
            'category_id' => $catNature->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Nature & Culture',
            'latitude' => 46.3611, 'longitude' => 14.1069,
        ]);
        $crete = Destinations::create([
            'pricing' => '780', 'currency' => 'EUR',
            'title' => 'Crete, Greece',
            'description' => 'Minoan ruins, Samaria Gorge, pink-sand beaches, and the birthplace of European civilization.',
            'content' => 'Crete is Greece in miniature. Explore the Palace of Knossos — the heart of Minoan civilisation. Hike through the Samaria Gorge, relax on Elafonisi\'s pink-sand beach, and wander through Chania\'s Venetian harbour. Taste Cretan olive oil, dakos salad, and raki. This tour includes a Knossos guided visit, a gorge hike, and a Cretan cooking class.',
            'category_id' => $catSummer->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Historical & Beach',
            'latitude' => 35.2401, 'longitude' => 24.8093,
        ]);
        $transylvania = Destinations::create([
            'pricing' => '600', 'currency' => 'EUR',
            'title' => 'Transylvania, Romania',
            'description' => 'Dracula\'s castle, medieval Saxon towns, Carpathian wilderness, and Europe\'s best-kept secret.',
            'content' => 'Transylvania is a land of legends. Explore Bran Castle (Dracula\'s Castle), walk through the medieval streets of Brașov and Sibiu, and drive the spectacular Transfăgărășan Highway — one of the world\'s most scenic roads. Visit bear sanctuaries, taste Romanian cuisine and palincă, and hike in the Carpathian Mountains. This tour includes a Bran Castle visit, a medieval city walking tour, and a bear-watching excursion.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Historical & Adventure',
            'latitude' => 46.7500, 'longitude' => 24.1500,
        ]);

        // ASIA IV
        $laos = Destinations::create([
            'pricing' => '600', 'currency' => 'USD',
            'title' => 'Luang Prabang, Laos',
            'description' => 'Monks in saffron robes, Kuang Si waterfalls, Mekong sunsets, and the slow-paced soul of Southeast Asia.',
            'content' => 'Luang Prabang is a UNESCO dream. Wake at dawn to witness the alms-giving ceremony, explore the Royal Palace and Wat Xieng Thong, and hike to the Kuang Si Waterfalls\' turquoise pools. Take a slow boat on the Mekong to the Pak Ou Caves, and climb Mount Phousi for sunset views. This tour includes an alms-giving experience, a waterfall day trip, and a Mekong River cruise.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Spiritual',
            'latitude' => 19.8563, 'longitude' => 102.4955,
        ]);
        $almaty = Destinations::create([
            'pricing' => '700', 'currency' => 'USD',
            'title' => 'Almaty & Kazakh Steppe',
            'description' => 'Tian Shan mountains, apple orchards, Soviet-era charm, and the vast Eurasian steppe.',
            'content' => 'Kazakhstan is Central Asia\'s giant. Explore Almaty\'s Green Bazaar and Zenkov Cathedral, ride the cable car up Kok Tobe hill, and drive to the stunning Big Almaty Lake. Visit the Charyn Canyon — Kazakhstan\'s Grand Canyon, and experience a yurt stay on the steppe. This tour includes a canyon day trip, a city tour, and a traditional Kazakh dinner.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Adventure & Culture',
            'latitude' => 43.2220, 'longitude' => 76.8512,
        ]);
        $iran = Destinations::create([
            'pricing' => '1200', 'currency' => 'USD',
            'title' => 'Tehran & Isfahan, Iran',
            'description' => 'Persian mosques, ancient ruins, bazaars, and the warmest hospitality in the Middle East.',
            'content' => 'Iran is the jewel of the Silk Road. Explore Tehran\'s Golestan Palace and National Museum, then fly to Isfahan for the breathtaking Naqsh-e Jahan Square, Sheikh Lotfollah Mosque, and Si-o-se-pol Bridge. Wander through the Vank Cathedral, smell spices in the bazaar, and taste saffron ice cream and kebabs. This tour includes guided historical site visits, a Persian cooking class, and a desert excursion.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Historical & Cultural',
            'latitude' => 32.4279, 'longitude' => 53.6880,
        ]);

        // AFRICA IV
        $uganda = Destinations::create([
            'pricing' => '2800', 'currency' => 'USD',
            'title' => 'Uganda — Chimpanzee Trekking',
            'description' => 'The Pearl of Africa — track chimpanzees, see tree-climbing lions, and raft the Nile.',
            'content' => 'Uganda is a primate paradise. Trek chimpanzees in Kibale Forest, track golden monkeys in Mgahinga, and see tree-climbing lions in Queen Elizabeth National Park. Visit the source of the Nile in Jinja for white-water rafting and bungee jumping. This tour includes a chimp trekking permit, a wildlife safari, and a Nile rafting adventure.',
            'category_id' => $catWild->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Wildlife & Adventure',
            'latitude' => 1.3733, 'longitude' => 32.2903,
        ]);
        $mozambique = Destinations::create([
            'pricing' => '1600', 'currency' => 'USD',
            'title' => 'Mozambique — Bazaruto Archipelago',
            'description' => 'Pristine Indian Ocean islands, dhow sailing, dugongs, and Africa\'s best-kept beach secret.',
            'content' => 'Mozambique has some of Africa\'s best beaches. Explore the Bazaruto Archipelago\'s white-sand islands and coral reefs, swim with dugongs and dolphins, and snorkel in crystal-clear waters. Visit the colonial capital Maputo, taste peri-peri prawns, and take a dhow cruise at sunset. This package includes island-hopping, a marine safari, and a Maputo city tour.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Beach & Nature',
            'latitude' => -19.6657, 'longitude' => 34.7456,
        ]);
        $gambia = Destinations::create([
            'pricing' => '800', 'currency' => 'USD',
            'title' => 'Gambia — Smiling Coast',
            'description' => 'Birdlife, river cruises, and the friendliest country in West Africa.',
            'content' => 'The Gambia is Africa in miniature. Cruise up the Gambia River to see chimpanzees on Baboon Island, visit the slave houses on Kunta Kinteh Island, and relax on the Atlantic beaches of Kololi. Spot exotic birds in Kiang West National Park and visit a local village. This tour includes a river cruise, a cultural village visit, and a birdwatching excursion.',
            'category_id' => $catNature->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Nature & Culture',
            'latitude' => 13.4432, 'longitude' => -15.3101,
        ]);
        
        // AMERICAS IV
        $chicago = Destinations::create([
            'pricing' => '1300', 'currency' => 'USD',
            'title' => 'Chicago, USA',
            'description' => 'Sky-scraping architecture, deep-dish pizza, blues music, and the Windy City\'s unmistakable energy.',
            'content' => 'Chicago is America\'s architectural masterpiece. Take an architecture boat cruise on the Chicago River, visit the Art Institute, and ride to the top of the Willis Tower Skydeck. Eat deep-dish pizza and Chicago-style hot dogs, catch a blues show, and walk through Millennium Park. This tour includes a river cruise, an architectural walking tour, and a food tour.',
            'category_id' => $catCity->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '6-10 People', 'tour_type' => 'City & Food',
            'latitude' => 41.8781, 'longitude' => -87.6298,
        ]);
        $vancouver = Destinations::create([
            'pricing' => '1600', 'currency' => 'CAD',
            'title' => 'Vancouver & Whistler, Canada',
            'description' => 'Mountains meeting the ocean, Stanley Park, world-class skiing, and Pacific Northwest beauty.',
            'content' => 'Vancouver is consistently one of the world\'s most liveable cities. Explore Stanley Park\'s seawall, visit Granville Island Market, and cross the Capilano Suspension Bridge. Drive the Sea to Sky Highway to Whistler for skiing, hiking, and mountain biking. This tour includes a city tour, a Whistler day trip, and a whale-watching cruise.',
            'category_id' => $catCity->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'City & Nature',
            'latitude' => 49.2827, 'longitude' => -123.1207,
        ]);
        $guatemala = Destinations::create([
            'pricing' => '900', 'currency' => 'USD',
            'title' => 'Guatemala — Tikal & Lake Atitlán',
            'description' => 'Mayan pyramids piercing the jungle, a volcanic lake, and the vibrant culture of the Maya highlands.',
            'content' => 'Guatemala is Central America\'s heartland. Watch sunrise over Tikal\'s towering pyramids emerging from the jungle. Explore the stunning Lake Atitlán surrounded by volcanoes and Maya villages, hike the Pacaya Volcano, and visit the colourful market of Chichicastenango. This tour includes a Tikal guided tour, an Atitlán boat trip, and a volcano hike.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Historical & Nature',
            'latitude' => 15.7835, 'longitude' => -90.2308,
        ]);
        $neworleans = Destinations::create([
            'pricing' => '1100', 'currency' => 'USD',
            'title' => 'New Orleans, USA',
            'description' => 'Jazz, Creole cuisine, French Quarter charm, voodoo history, and the most unique city in America.',
            'content' => 'New Orleans is a party for the soul. Explore the French Quarter, hear live jazz on Frenchmen Street, and indulge in gumbo, jambalaya, and beignets at Café du Monde. Visit the Garden District, take a swamp tour to see alligators, and experience Mardi Gras World. This tour includes a French Quarter walking tour, a jazz club crawl, and a Louisiana swamp tour.',
            'category_id' => $catFriends->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Food',
            'latitude' => 29.9511, 'longitude' => -90.0715,
        ]);
        $panama = Destinations::create([
            'pricing' => '1300', 'currency' => 'USD',
            'title' => 'Panama City & Panama Canal',
            'description' => 'An engineering marvel, a modern skyline, and rainforests teaming with wildlife at the crossroads of the Americas.',
            'content' => 'Panama is where continents meet. Watch massive ships pass through the legendary Panama Canal locks, explore the colonial Casco Viejo district, and hike through Soberanía National Park. Visit the Emberá indigenous village, snorkel in the San Blas Islands, and spot toucans and sloths. This tour includes a canal viewing, a Casco Viejo walk, and a rainforest expedition.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Adventure & Culture',
            'latitude' => 8.9824, 'longitude' => -79.5199,
        ]);

        // MIDDLE EAST III
        $jerusalem = Destinations::create([
            'pricing' => '1800', 'currency' => 'USD',
            'title' => 'Jerusalem & Tel Aviv, Israel',
            'description' => 'The holy city, the Dead Sea, ancient fortresses, and Tel Aviv\'s vibrant beachside energy.',
            'content' => 'Israel is a land of layers. Walk through Jerusalem\'s Old City — the Western Wall, Church of the Holy Sepulchre, and Dome of the Rock. Float in the Dead Sea, hike Masada at sunrise, and explore the vibrant Carmel Market in Tel Aviv. Visit the Baha\'i Gardens in Haifa and taste hummus, falafel, and shakshuka. This tour includes a guided Old City tour, a Dead Sea experience, and a Masada sunrise hike.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Historical & Cultural',
            'latitude' => 31.7683, 'longitude' => 35.2137,
        ]);
        $bahrain = Destinations::create([
            'pricing' => '900', 'currency' => 'USD',
            'title' => 'Bahrain',
            'description' => 'Ancient Dilmun civilization, Formula 1, pearl diving history, and modern Arabian sophistication.',
            'content' => 'Bahrain is the Gulf\'s hidden gem. Explore the Bahrain National Museum and Qal\'at al-Bahrain fort, visit the Tree of Life in the desert, and shop in the Manama Souq. Take a pearl-diving boat trip, visit the Formula 1 circuit, and relax at the Al Areen Wildlife Park. This tour includes a museum visit, a pearl-diving experience, and a guided city tour.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Modern',
            'latitude' => 25.9304, 'longitude' => 50.6378,
        ]);

        // INDIAN OCEAN II
        $andaman = Destinations::create([
            'pricing' => '1000', 'currency' => 'USD',
            'title' => 'Andaman Islands, India',
            'description' => 'Crystal-clear waters, untouched beaches, coral reefs, and a former colonial penal colony.',
            'content' => 'The Andamans are India\'s tropical paradise. Relax on Radhanagar Beach (Asia\'s best), snorkel at Havelock Island, and explore the limestone caves of Baratang. Visit the Cellular Jail in Port Blair and see the light and sound show. This package includes ferry transfers, a snorkelling cruise, and a guided island tour.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Beach & History',
            'latitude' => 12.5000, 'longitude' => 92.7500,
        ]);
        $reunion = Destinations::create([
            'pricing' => '2000', 'currency' => 'EUR',
            'title' => 'Réunion Island',
            'description' => 'Active volcanoes, lush cirques, epic hiking, and a unique blend of French, African, and Indian cultures.',
            'content' => 'Réunion is an adventure playground. Hike to the summit of the active Piton de la Fournaise volcano, explore the lush Cirque de Mafate, and canyoneer through waterfalls and gorges. Visit the capital Saint-Denis, taste Creole cuisine, and spot humpback whales. This tour includes a volcano guided hike, a canyoning expedition, and a scenic helicopter flight.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Nature',
            'latitude' => -21.1151, 'longitude' => 55.5364,
        ]);
        $capeverde = Destinations::create([
            'pricing' => '1100', 'currency' => 'EUR',
            'title' => 'Cape Verde',
            'description' => 'Volcanic islands, morna music, Creole culture, and year-round sunshine off West Africa.',
            'content' => 'Cape Verde is an Atlantic archipelago of soul. Explore Sal\'s white-sand beaches and salt flats, hike the volcanic peaks of Fogo, and experience the vibrant music scene of Mindelo on São Vicente. Wind-surf in Santa Maria, walk through the cobbled streets of Cidade Velha, and taste grogue (sugarcane spirit). This package includes inter-island flights, a Fogo volcano trek, and a music night in Mindelo.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '9 Days / 8 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Beach & Culture',
            'latitude' => 15.1200, 'longitude' => -23.6050,
        ]);

        // PACIFIC II
        $cookislands = Destinations::create([
            'pricing' => '2800', 'currency' => 'NZD',
            'title' => 'Cook Islands',
            'description' => 'Turquoise lagoons, coral atolls, coconut palms, and the warmest smiles in the South Pacific.',
            'content' => 'The Cook Islands are paradise found. Swim in the crystal-clear lagoon of Aitutaki — one of the world\'s most beautiful islands. Explore Rarotonga by scooter, hike the Cross-Island Track through the jungle, and snorkel with giant trevally and sea turtles. Enjoy an island night with drumming and dance. This package includes an Aitutaki lagoon cruise, a Rarotonga island tour, and a Polynesian cultural evening.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '2-6 People', 'tour_type' => 'Beach & Relaxation',
            'latitude' => -21.2367, 'longitude' => -159.7777,
        ]);
        $vanuatu = Destinations::create([
            'pricing' => '2200', 'currency' => 'USD',
            'title' => 'Vanuatu',
            'description' => 'Active volcanoes, tribal villages, blue holes, and the world\'s most accessible live volcano.',
            'content' => 'Vanuatu is adventure off the beaten path. Stand at the rim of Mount Yasur, the world\'s most accessible active volcano. Explore the blue holes of Santo, dive the SS President Coolidge wreck, and visit traditional villages where kastom (custom) is still strong. This tour includes a volcano excursion, a blue holes day trip, and a cultural village visit.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Culture',
            'latitude' => -15.3767, 'longitude' => 166.9592,
        ]);

        // TUNISIA — All major destinations
        $tunis = Destinations::create([
            'pricing' => '1200', 'currency' => 'TND',
            'title' => 'Tunis & Carthage, Tunisia',
            'description' => 'The vibrant capital of Tunisia — ancient Carthage ruins, the blue-and-white Sidi Bou Said, and the sprawling Medina of Tunis.',
            'content' => 'Tunis is where Tunisia\'s soul lives. Explore the labyrinthine Medina of Tunis (UNESCO-listed), visit the Bardo Museum with the world\'s largest collection of Roman mosaics, and wander through the blue-and-white streets of Sidi Bou Said overlooking the Mediterranean. Discover the ancient ruins of Carthage — the Baths of Antoninus, the Punic ports, and Byrsa Hill. Taste brik, ojja, and fresh seafood at the port of La Goulette. This tour includes a guided Medina walk, a Carthage archaeological tour, and a Sidi Bou Said sunset visit.',
            'category_id' => $catCity->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Historical',
            'latitude' => 36.8065, 'longitude' => 10.1815,
        ]);
        $sousse = Destinations::create([
            'pricing' => '800', 'currency' => 'TND',
            'title' => 'Sousse & Port El Kantaoui, Tunisia',
            'description' => 'Golden beaches, a UNESCO-listed medina, and Tunisia\'s most famous marina resort town.',
            'content' => 'Sousse is the pearl of the Sahel. Walk through the fortified medina (UNESCO-listed), climb the Ribat watchtower for panoramic views, and visit the Great Mosque. Relax on the long sandy beaches of Boujaafar, and explore the nearby Port El Kantaoui marina with its golf courses, yacht harbour, and water sports. Take a day trip to the ancient city of Kairouan. This tour includes a guided medina visit, a Port El Kantaoui water sports day, and a Kairouan excursion.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-12 People', 'tour_type' => 'Beach & Culture',
            'latitude' => 35.8252, 'longitude' => 10.6370,
        ]);
        $hammamet = Destinations::create([
            'pricing' => '900', 'currency' => 'TND',
            'title' => 'Hammamet & Nabeul, Tunisia',
            'description' => 'Jasmine-scented streets, Mediterranean beaches, pottery workshops, and the birthplace of Tunisian tourism.',
            'content' => 'Hammamet is the garden of Tunisia. Stroll through the charming whitewashed medina, relax on miles of sandy beaches, and visit the historic Hammamet Fort. Cross to Nabeul to explore its famous pottery and ceramics workshops, and taste fresh seafood at the port. The surrounding Cap Bon peninsula offers stunning landscapes, vineyards, and the ancient city of Kerkouane. This tour includes a medina walk, a pottery workshop in Nabeul, and a Cap Bon wine tasting.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Beach & Culture',
            'latitude' => 36.4085, 'longitude' => 10.6192,
        ]);
        $sfax = Destinations::create([
            'pricing' => '700', 'currency' => 'TND',
            'title' => 'Sfax & Kerkennah Islands, Tunisia',
            'description' => 'Tunisia\'s second city, a living medina, and the untouched Kerkennah archipelago.',
            'content' => 'Sfax is the economic heart of Tunisia with one of the best-preserved medinas in the country. Explore the Dar Jellouli Museum, the Great Mosque, and the bustling souks. Take a ferry to the Kerkennah Islands — a peaceful archipelago with palm-fringed beaches, simple fishing villages, and traditional charfiya fishing techniques. This tour includes a guided medina tour, a Kerkennah island-hopping day, and a seafood lunch at a local fisherman\'s home.',
            'category_id' => $catCity->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Beach',
            'latitude' => 34.7394, 'longitude' => 10.7604,
        ]);
        $tozeur = Destinations::create([
            'pricing' => '1100', 'currency' => 'TND',
            'title' => 'Tozeur & Nefta — Mountain Oases, Tunisia',
            'description' => 'Saharan oases, palm forests, ancient irrigation systems, and the gateway to the Great Eastern Erg.',
            'content' => 'Tozeur is a desert paradise in southern Tunisia. Wander through the old city with its distinctive brick architecture, explore the oasis\'s thousands of date palms, and visit the Eden Palm museum. Take a rickshaw or 4x4 into the Chebika, Tamerza, and Mides mountain oases — dramatic canyons with waterfalls in the middle of the desert. Cross the Great Eastern Erg sand dunes by camel or 4x4, and visit the salt lake Chott El Jerid. Film locations for Star Wars and The English Patient are nearby. This tour includes guided oasis walks, a desert safari, and a night in a desert camp.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Desert & Adventure',
            'latitude' => 33.9095, 'longitude' => 8.1312,
        ]);
        $kairouan = Destinations::create([
            'pricing' => '600', 'currency' => 'TND',
            'title' => 'Kairouan, Tunisia',
            'description' => 'The fourth holiest city in Islam — the Great Mosque, ancient medina, and masterful carpet craftsmanship.',
            'content' => 'Kairouan is Tunisia\'s spiritual capital. Visit the magnificent Great Mosque of Uqba with its massive marble courtyard and ancient minaret. Explore the Aghlabid Basins, the mausoleums of holy men, and the carpet weaving workshops where Kairouan\'s famous rugs are made by hand. Walk through the medina\'s narrow streets and taste makroudh (date-filled semolina pastries). This tour includes a guided mosque visit, a carpet workshop tour, and a traditional Tunisian lunch.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '2 Days / 1 Night', 'group_size' => '6-12 People', 'tour_type' => 'Religious & Cultural',
            'latitude' => 35.6769, 'longitude' => 10.0946,
        ]);
        $tabarka = Destinations::create([
            'pricing' => '1000', 'currency' => 'TND',
            'title' => 'Tabarka & Ain Drahem, Tunisia',
            'description' => 'Coral fishing, pine forests, mountain hikes, and the greenest corner of Tunisia.',
            'content' => 'Tabarka is Tunisia\'s natural jewel on the northwestern coast. Visit the Genoese fortress overlooking the Mediterranean, dive or snorkel in crystal-clear waters famous for red coral. Head inland to Ain Drahem, Tunisia\'s highest town surrounded by cork oak forests — ideal for hiking, picnicking, and escaping the summer heat. Explore the nearby Roman ruins of Chemtou and Bulla Regia with their underground villas. This tour includes a coral diving experience, a forest hike, and a Roman ruins tour.',
            'category_id' => $catNature->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Nature & Adventure',
            'latitude' => 36.9584, 'longitude' => 8.7580,
        ]);
        $bizerte = Destinations::create([
            'pricing' => '650', 'currency' => 'TND',
            'title' => 'Bizerte, Tunisia',
            'description' => 'Tunisia\'s northernmost city — a charming old port, Mediterranean beaches, and the stunning Ichkeul National Park.',
            'content' => 'Bizerte is where the Mediterranean meets the lake. Explore the charming old port with its colourful fishing boats, cross the iconic mobile bridge, and visit the ancient kasbah. Just outside the city lies Ichkeul National Park (UNESCO Biosphere Reserve) — a wetland paradise for birdwatchers with thousands of flamingos, storks, and ducks. Relax on the beaches of Sidi Salem or explore the nearby Cap Angela, the northernmost point of Africa. This tour includes a medina walk, a birdwatching trip to Ichkeul, and a Cap Angela hike.',
            'category_id' => $catNature->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '3 Days / 2 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Nature & Sightseeing',
            'latitude' => 37.2753, 'longitude' => 9.8720,
        ]);
        $douz = Destinations::create([
            'pricing' => '900', 'currency' => 'TND',
            'title' => 'Douz — Sahara Desert, Tunisia',
            'description' => 'The gateway to the Sahara — camel treks, starry desert nights, and the famous International Sahara Festival.',
            'content' => 'Douz is the Sahara\'s front door. Ride a camel into the vast golden dunes of the Great Eastern Erg, watch the desert sunset paint the sands in shades of orange and red, and sleep under a blanket of stars in a traditional Bedouin camp. Visit the Museum of the Sahara, and time your visit with the International Festival of the Sahara featuring camel races, folk dances, and Bedouin poetry. This tour includes a camel trek, a desert camp overnight, and a 4x4 dune adventure.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Desert & Adventure',
            'latitude' => 33.4618, 'longitude' => 9.0223,
        ]);
        $eljem = Destinations::create([
            'pricing' => '500', 'currency' => 'TND',
            'title' => 'El Jem, Tunisia',
            'description' => 'One of the best-preserved Roman amphitheatres in the world — the Colosseum of Tunisia.',
            'content' => 'El Jem is home to the magnificent El Jem Amphitheatre, a UNESCO World Heritage site and one of the largest Roman colosseums ever built (capacity 35,000). Wander through its underground chambers where gladiators and wild animals awaited their fate, climb to the top for panoramic views over the town, and visit the nearby mosaic museum in the former archaeological museum. A short drive away lies the ancient city of Thysdrus. This tour includes a guided amphitheatre tour and a mosaic workshop visit.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '1 Day', 'group_size' => '6-15 People', 'tour_type' => 'Historical & Heritage',
            'latitude' => 35.3005, 'longitude' => 10.7177,
        ]);
        $mahdia = Destinations::create([
            'pricing' => '750', 'currency' => 'TND',
            'title' => 'Mahdia, Tunisia',
            'description' => 'A peaceful coastal gem — Fatimid capital, pristine beaches, and the best seafood in Tunisia.',
            'content' => 'Mahdia is a hidden treasure on Tunisia\'s east coast. Walk through the historic medina with its whitewashed houses and the Great Mosque, visit the Fatimid capital\'s archaeological remains, and explore the Borj El Kebir fortress overlooking the sea. Relax on the stunning beaches of Mahdia and the nearby Cape Africa — one of the finest natural swimming spots in the country. The port offers the freshest fish and seafood in Tunisia. This tour includes a guided historical walk, a fishing boat trip, and a seafood cooking class.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Beach & Historical',
            'latitude' => 35.5008, 'longitude' => 11.0621,
        ]);
        $monastir = Destinations::create([
            'pricing' => '700', 'currency' => 'TND',
            'title' => 'Monastir, Tunisia',
            'description' => 'The Ribat city — a stunning Islamic fortress, Bourguiba\'s mausoleum, and beautiful Mediterranean beaches.',
            'content' => 'Monastir is a city of history and sun. The stunning Ribat of Monastir is one of the most photographed Islamic fortresses in the world (used as a filming location for Monty Python\'s Life of Brian). Visit the Bourguiba Mausoleum, a masterpiece of modern Islamic architecture, and explore the small medina. Relax along the marina and beaches, and visit the nearby Skanes resort area. This tour includes a guided Ribat tour, a Bourguiba Mausoleum visit, and a coastal walk.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '3 Days / 2 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Historical & Beach',
            'latitude' => 35.7797, 'longitude' => 10.8332,
        ]);
        $matmata = Destinations::create([
            'pricing' => '800', 'currency' => 'TND',
            'title' => 'Matmata & Troglodyte Dwellings, Tunisia',
            'description' => 'Ancient underground homes carved into the earth — the real-life Star Wars set in southern Tunisia.',
            'content' => 'Matmata is famous for its unique troglodyte dwellings — houses and entire communities carved into the soft rock of the mountains. Stay in a cave hotel, visit the famous Hotel Sidi Driss (used as the Lars homestead in Star Wars), and explore the underground homes of the Berber people. Nearby, the ksour (fortified granaries) of Medenine and Tataouine are straight out of a sci-fi film. This tour includes a troglodyte home visit, a Star Wars filming location tour, and a Berber cultural experience.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '3 Days / 2 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Cultural & Adventure',
            'latitude' => 33.5435, 'longitude' => 9.9667,
        ]);
        $capbon = Destinations::create([
            'pricing' => '850', 'currency' => 'TND',
            'title' => 'Cap Bon — Kelibia & Haouaria, Tunisia',
            'description' => 'Wild beaches, the clearest waters in Tunisia, falconry, and the fertile peninsula of Cap Bon.',
            'content' => 'Cap Bon is Tunisia\'s green lung and beach paradise. Visit the port town of Kelibia with its stunning fortress overlooking the Mediterranean, relax on the wild beaches of Mansoura and El Haouaria — some of the clearest waters in the country. Explore the Cap Bon wine route, visit the falconry festival in El Haouaria (March), and hike through national parks. Ancient ruins of Kerkouane (UNESCO) line the coast. This tour includes a Kelibia fortress visit, a beach day at El Haouaria, and a wine tasting.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Beach & Nature',
            'latitude' => 36.8333, 'longitude' => 11.0000,
        ]);
        $tataouine = Destinations::create([
            'pricing' => '700', 'currency' => 'TND',
            'title' => 'Tataouine & Chenini, Tunisia',
            'description' => 'The land of Star Wars — fortified ksour, Berber villages clinging to cliffs, and the desert of Tatooine.',
            'content' => 'Tataouine province is where George Lucas found inspiration for the planet Tatooine in Star Wars. Explore the hilltop Berber village of Chenini — an ancient fortified granary (ksar) carved into the mountainside. Visit Ksar Ouled Soltane and Ksar Hadada (used as Mos Eisley in Star Wars), and see the unique architecture of these multi-storey granaries. Experience traditional Berber hospitality, taste kamounia and couscous, and explore the lunar landscapes of the south. This tour includes a ksour photography tour, a Berber village visit, and a desert sunset.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '3 Days / 2 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Cultural & Desert',
            'latitude' => 32.9293, 'longitude' => 10.4511,
        ]);
        $zaghouan = Destinations::create([
            'pricing' => '400', 'currency' => 'TND',
            'title' => 'Zaghouan, Tunisia',
            'description' => 'The Mountain of the Gods — Roman water temples, mountain springs, and the sacred peak of Djebel Zaghouan.',
            'content' => 'Zaghouan is the source of Carthage\'s ancient water supply. Visit the Temple of Water (the Nymphaeum) on the slopes of Djebel Zaghouan — a magnificent Roman monument built to honour the source of the aqueduct that supplied Carthage. Hike through the pine and oak forests of Djebel Zaghouan, the highest peak in northern Tunisia. Visit the olive oil cooperatives, taste local honey and cheese, and relax in the mountain air. This tour includes a Temple of Water guided visit, a mountain hike, and an olive oil tasting.',
            'category_id' => $catNature->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '1 Day', 'group_size' => '4-10 People', 'tour_type' => 'Nature & Historical',
            'latitude' => 36.4026, 'longitude' => 10.1435,
        ]);

        // Attach tags for Tunisian destinations
        $tunis->tags()->attach([$tagCity->id, $tagCulture->id, $tagTravel->id]);
        $sousse->tags()->attach([$tagBeach->id, $tagCulture->id, $tagTravel->id]);
        $hammamet->tags()->attach([$tagBeach->id, $tagFood->id, $tagCulture->id]);
        $sfax->tags()->attach([$tagCity->id, $tagCulture->id, $tagTravel->id]);
        $tozeur->tags()->attach([$tagAdventure->id, $tagNature->id, $tagCulture->id]);
        $kairouan->tags()->attach([$tagCulture->id, $tagTravel->id, $tagAdventure->id]);
        $tabarka->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $bizerte->tags()->attach([$tagNature->id, $tagCity->id, $tagTravel->id]);
        $douz->tags()->attach([$tagAdventure->id, $tagNature->id, $tagTravel->id]);
        $eljem->tags()->attach([$tagCulture->id, $tagTravel->id, $tagAdventure->id]);
        $mahdia->tags()->attach([$tagBeach->id, $tagFood->id, $tagTravel->id]);
        $monastir->tags()->attach([$tagCulture->id, $tagBeach->id, $tagTravel->id]);
        $matmata->tags()->attach([$tagAdventure->id, $tagCulture->id, $tagTravel->id]);
        $capbon->tags()->attach([$tagBeach->id, $tagNature->id, $tagFood->id]);
        $tataouine->tags()->attach([$tagCulture->id, $tagAdventure->id, $tagTravel->id]);
        $zaghouan->tags()->attach([$tagNature->id, $tagCulture->id, $tagTravel->id]);

        // KENYA
        $masaimara = Destinations::create([
            'pricing' => '2800', 'currency' => 'KES',
            'title' => 'Masai Mara & Lake Nakuru, Kenya',
            'description' => 'The greatest wildlife show on Earth -- the Great Migration, Big Five safaris, and flamingo-fringed lakes.',
            'content' => 'Kenya is the original safari destination. Witness the Great Migration in the Masai Mara -- millions of wildebeest and zebras crossing the crocodile-filled Mara River. Spot the Big Five on daily game drives, visit a Maasai village, and drive to Lake Nakuru for thousands of flamingos painting the lake pink. Hot air balloon safaris offer a breathtaking sunrise view of the Mara. This tour includes daily game drives, a balloon safari, a Maasai village visit, and a Lake Nakuru excursion.',
            'category_id' => $catWild->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Wildlife & Safari',
            'latitude' => -1.5000, 'longitude' => 35.0000,
        ]);
        $nairobi = Destinations::create([
            'pricing' => '1500', 'currency' => 'KES',
            'title' => 'Nairobi & Amboseli, Kenya',
            'description' => 'The safari capital of Africa -- the David Sheldrick Elephant Orphanage, giraffes, and Kilimanjaro views at Amboseli.',
            'content' => 'Nairobi is the only capital city with a national park on its doorstep. Visit the David Sheldrick Wildlife Trust to see baby elephants, feed giraffes at the Giraffe Centre, and explore the Karen Blixen Museum. Then head to Amboseli National Park with its iconic views of Mount Kilimanjaro -- vast herds of elephants against Africa highest peak. This tour includes a Nairobi city safari, an elephant orphanage visit, and a guided Amboseli game drive.',
            'category_id' => $catFamily->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Wildlife & City',
            'latitude' => -1.2921, 'longitude' => 36.8219,
        ]);
        $mombasa = Destinations::create([
            'pricing' => '1800', 'currency' => 'KES',
            'title' => 'Mombasa & Diani Beach, Kenya',
            'description' => 'Turquoise Indian Ocean waters, white sand beaches, Swahili culture, and the historic old town of Mombasa.',
            'content' => 'Mombasa is Kenya coastal jewel. Explore the historic Fort Jesus, wander through the narrow streets of Old Town with its Swahili-Arabic architecture, and cross the Likoni Ferry. South of Mombasa lies Diani Beach -- one of Africa finest beaches with powdery white sand, coral reefs, and palm trees. Snorkel at Kisite-Mpunguti Marine Park, spot dolphins, and taste Swahili cuisine. This tour includes a Mombasa city tour, a Diani beach stay, and a marine park snorkelling excursion.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Beach & Culture',
            'latitude' => -4.0435, 'longitude' => 39.6682,
        ]);
        $lamu = Destinations::create([
            'pricing' => '2000', 'currency' => 'KES',
            'title' => 'Lamu, Kenya',
            'description' => 'A UNESCO-listed Swahili town frozen in time -- no cars, only donkeys, dhows, and ancient stone streets.',
            'content' => 'Lamu is unlike anywhere else on Earth. This UNESCO World Heritage site has no cars -- transport is by donkey or dhow. Explore the narrow winding streets of Lamu Old Town, visit the Lamu Museum and Swahili House Museum, and take a dhow sailing trip to the surrounding islands. Experience the Lamu Cultural Festival and relax on the pristine beaches of Shela and Manda Island. This tour includes a guided Old Town walk, a dhow sunset cruise, and a visit to the ruins of Takwa on Manda Island.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Cultural & Beach',
            'latitude' => -2.2690, 'longitude' => 40.9021,
        ]);
        $naivasha = Destinations::create([
            'pricing' => '1200', 'currency' => 'KES',
            'title' => 'Lake Naivasha & Hell Gate, Kenya',
            'description' => 'A freshwater lake teeming with hippos and birdlife, plus a national park you can explore by bicycle.',
            'content' => 'The Great Rift Valley lakes are breathtaking. Lake Naivasha is a freshwater paradise where hippos grunt offshore and fish eagles soar overhead. Take a boat trip to Crescent Island to walk among zebras, giraffes, and waterbucks. Nearby Hell Gate National Park offers dramatic cliffs, gorges, and hot springs -- explore by bicycle past grazing buffalo and gazelles, just like in The Lion King. This tour includes a lake boat safari, a Hell Gate cycling adventure, and a visit to a geothermal spa.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '3 Days / 2 Nights', 'group_size' => '4-10 People', 'tour_type' => 'Adventure & Nature',
            'latitude' => -0.7171, 'longitude' => 36.4310,
        ]);

        // ALGERIA
        $algiers = Destinations::create([
            'pricing' => '1800', 'currency' => 'DZD',
            'title' => 'Algiers & Tipaza, Algeria',
            'description' => 'The white city on the Mediterranean -- Casbah, Roman ruins, and a blend of Berber, Arab, and French heritage.',
            'content' => 'Algiers is the radiant white capital of Algeria. Explore the UNESCO-listed Casbah with its Ottoman palaces and winding alleyways, visit the Ketchaoua Mosque and the Bardo Museum, and take the cable car up to Notre Dame d Afrique. Drive along the coast to Tipaza, a stunning Roman coastal city with ruins overlooking the Mediterranean Sea. Taste couscous, chakhchoukha, and fresh seafood. This tour includes a guided Casbah walk, a Tipaza archaeological tour, and a traditional Algerian dinner.',
            'category_id' => $catCity->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Historical',
            'latitude' => 36.7372, 'longitude' => 3.0864,
        ]);
        $constantine = Destinations::create([
            'pricing' => '1500', 'currency' => 'DZD',
            'title' => 'Constantine & Timgad, Algeria',
            'description' => 'The city of bridges suspended over a dramatic gorge, and the best-preserved Roman city in North Africa.',
            'content' => 'Constantine is a city of drama -- perched on a deep gorge with spectacular bridges connecting its cliffs. Visit the Emir Abdelkader Mosque, the Ahmed Bey Palace, and the Cirta Museum. A short drive away lies Timgad, a UNESCO-listed Roman city founded by Emperor Trajan in 100 AD -- its grid layout, Trajan Arch, and theatre are remarkably preserved. This tour includes a Constantine city tour, a Timgad guided archaeological visit, and a stop in the beautiful mountain town of Batna.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '3 Days / 2 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Historical & Heritage',
            'latitude' => 36.3650, 'longitude' => 6.6147,
        ]);
        $ghardaia = Destinations::create([
            'pricing' => '2000', 'currency' => 'DZD',
            'title' => 'Ghardaia & M Valley, Algeria',
            'description' => 'Five fortified cities (ksour) of the Ibadi Mozabites -- a unique, perfectly preserved Islamic urban landscape.',
            'content' => 'The M Valley is one of the most unique places in the Sahara. Five ancient fortified cities built by the Ibadi community around 1000 AD, each on a hilltop with a mosque and market at its centre. Ghardaia is the largest, with its distinctive white, blue, and ochre houses. This tour includes guided walks through the ancient ksour, a visit to the traditional carpet workshops, and a date plantation tour in the oasis.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Cultural & Heritage',
            'latitude' => 32.4905, 'longitude' => 3.6700,
        ]);
        $oran = Destinations::create([
            'pricing' => '1600', 'currency' => 'DZD',
            'title' => 'Oran, Algeria',
            'description' => 'The vibrant second city of Algeria -- Spanish-influenced architecture, Rai music, and Mediterranean energy.',
            'content' => 'Oran is the capital of Algerian Rai music and a city of Spanish-influenced charm. Visit the Santa Cruz Fortress with panoramic views over the bay, explore the beautiful Cathedral of the Sacred Heart (now the Public Library), and wander through the historic Sidi El Houari district. Relax on the beaches of Les Andalouses, and listen to live Rai music in a local club. This tour includes a guided city tour, a fortress visit, and an evening of traditional music.',
            'category_id' => $catCity->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '3 Days / 2 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Music',
            'latitude' => 35.6989, 'longitude' => -0.6331,
        ]);
        $tassili = Destinations::create([
            'pricing' => '3500', 'currency' => 'DZD',
            'title' => 'Tassili n Ajjer & Djanet, Algeria',
            'description' => 'A UNESCO-listed lunar landscape with prehistoric rock art, sandstone arches, and the deepest Sahara.',
            'content' => 'Tassili n Ajjer is a vast plateau in the Sahara with some of the most important prehistoric rock art in the world (over 15,000 engravings dating back 12,000 years). The sandstone formations create surreal landscapes of natural arches, canyons, and pillars. Explore by 4x4 and camel, visit ancient cave paintings of elephants, giraffes, and mysterious horned figures, and sleep under the most brilliant starry skies on Earth. This tour includes a multi-day desert expedition, guided rock art visits, and a Tuareg cultural experience.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '4-6 People', 'tour_type' => 'Desert & Archaeology',
            'latitude' => 24.5500, 'longitude' => 9.5000,
        ]);

        // GREECE MAINLAND
        $athens = Destinations::create([
            'pricing' => '850', 'currency' => 'EUR',
            'title' => 'Athens & Delphi, Greece',
            'description' => 'The cradle of Western civilisation -- the Parthenon, Delphi Oracle, and the birthplace of democracy.',
            'content' => 'Athens is where it all began. Stand in awe before the Parthenon atop the Acropolis, explore the Acropolis Museum, and wander through the ancient Agora and Plaka district. Drive to Delphi, considered the centre of the ancient world -- home to the Oracle of Apollo, the Temple of Apollo, and the Delphi Theatre set against stunning mountain scenery. This tour includes a guided Acropolis tour, a Delphi day trip, and a Greek cooking class in the Plaka neighbourhood.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '8-12 People', 'tour_type' => 'Historical & Cultural',
            'latitude' => 37.9838, 'longitude' => 23.7275,
        ]);
        $meteora = Destinations::create([
            'pricing' => '900', 'currency' => 'EUR',
            'title' => 'Meteora & Thessaloniki, Greece',
            'description' => 'Monasteries suspended in the sky atop towering rock pillars, and the vibrant capital of Macedonian Greece.',
            'content' => 'Meteora is one of the most awe-inspiring places on Earth -- monasteries perched atop gigantic rock pillars that seem to float in the sky. Visit six active monasteries, hike the stunning trails between them, and watch sunset from the iconic viewpoint. Continue to Thessaloniki, Greece second city -- rich in Byzantine history, Roman ruins, and the best food scene in northern Greece. This tour includes a guided Meteora monasteries visit, a rock climbing experience, and a Thessaloniki food tour.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Adventure',
            'latitude' => 39.7136, 'longitude' => 21.6338,
        ]);

        // BELGIUM
        $belgium = Destinations::create([
            'pricing' => '980', 'currency' => 'EUR',
            'title' => 'Brussels & Bruges, Belgium',
            'description' => 'Medieval charm, world-class chocolate, Trappist beer, and the most beautiful square in Europe.',
            'content' => 'Belgium packs a punch. Explore Brussels Grand Place -- one of Europe most beautiful squares, see the Manneken Pis, and visit the Atomium. Then travel to Bruges, a fairytale city of canals, cobblestones, and medieval architecture. Climb the Belfry tower, take a canal boat ride, and taste Belgian waffles, chocolate, and fries. This tour includes a chocolate-making workshop, a Bruges canal cruise, and a beer-tasting session at a historic Trappist brewery.',
            'category_id' => $catCity->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Food',
            'latitude' => 50.8503, 'longitude' => 4.3517,
        ]);

        // MONTENEGRO
        $montenegro = Destinations::create([
            'pricing' => '750', 'currency' => 'EUR',
            'title' => 'Bay of Kotor & Budva, Montenegro',
            'description' => 'A breathtaking fjord-like bay, medieval walled towns, and the dramatic Dinaric Alps meeting the Adriatic.',
            'content' => 'Montenegro is one of Europe most beautiful hidden corners. Cruise into the Bay of Kotor -- often called Europe southernmost fjord. Walk the ancient walls of Kotor Old Town, hike up to the Fortress of St John for panoramic views, and visit the church of Our Lady of the Rocks. Drive along the coast to Budva medieval old town and relax on the beaches of the Budva Riviera. This tour includes a Kotor walking tour, a bay cruise, and a visit to the charming village of Perast.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Adventure & Beach',
            'latitude' => 42.4247, 'longitude' => 18.7712,
        ]);

        // MALTA
        $malta = Destinations::create([
            'pricing' => '680', 'currency' => 'EUR',
            'title' => 'Valletta & Gozo, Malta',
            'description' => 'A sun-drenched island nation -- the Knights of St John, megalithic temples, and crystal-clear Mediterranean waters.',
            'content' => 'Malta is a living museum. Explore Valletta, the fortified capital built by the Knights of St John -- visit St John Co-Cathedral with Caravaggio masterpieces, the Grandmaster Palace, and the Upper Barrakka Gardens. Cross to Gozo for the stunning Azure Window site, the Ggantija Temples (older than the Pyramids), and the clear waters of the Blue Lagoon. This tour includes a Valletta guided walk, a Gozo island tour, and a traditional Maltese cooking class.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Beach',
            'latitude' => 35.8997, 'longitude' => 14.5147,
        ]);

        // CYPRUS
        $cyprus = Destinations::create([
            'pricing' => '720', 'currency' => 'EUR',
            'title' => 'Paphos & Limassol, Cyprus',
            'description' => 'Where Aphrodite rose from the foam -- ancient mosaics, Crusader castles, and year-round Mediterranean sun.',
            'content' => 'Cyprus is the island of Aphrodite. Explore Paphos stunning Roman mosaics in the Archaeological Park, visit the legendary birthplace of Aphrodite at Petra tou Romiou, and walk through the Tombs of the Kings. Drive to Limassol for the medieval castle, the old town, and the famous Cyprus wine region. This tour includes a Paphos archaeological tour, a wine-tasting day in the Troodos Mountains, and a coastal hike along the Aphrodite Trail.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Historical & Beach',
            'latitude' => 34.7754, 'longitude' => 32.4218,
        ]);

        // GEORGIA
        $georgia = Destinations::create([
            'pricing' => '800', 'currency' => 'USD',
            'title' => 'Tbilisi & Kazbegi, Georgia',
            'description' => 'The crossroads of Europe and Asia -- ancient wine traditions, the Caucasus mountains, and legendary hospitality.',
            'content' => 'Georgia is one of the world oldest wine regions and a travel gem. Explore Tbilisi charming Old Town with its sulphur bathhouses, colourful balconied houses, and the Narikala Fortress. Drive the Georgian Military Highway to Kazbegi (Stepantsminda) for the iconic Gergeti Trinity Church against the backdrop of Mount Kazbek, one of the highest peaks in the Caucasus. Visit ancient cave monasteries, taste khinkali (dumplings) and khachapuri (cheese bread), and sample natural wines. This tour includes a Tbilisi walking tour, a Kazbegi mountain trek, and a wine-tasting in Kakheti.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Culture',
            'latitude' => 41.7151, 'longitude' => 44.8271,
        ]);

        // SERBIA
        $serbia = Destinations::create([
            'pricing' => '550', 'currency' => 'EUR',
            'title' => 'Belgrade, Serbia',
            'description' => 'A city where the Danube and Sava meet -- vibrant nightlife, socialist architecture, and a truly unique energy.',
            'content' => 'Belgrade is a city of contrasts. Explore the massive Kalemegdan Fortress at the confluence of the Danube and Sava rivers, wander through the bohemian Skadarlija quarter, and marvel at the massive Temple of Saint Sava. Experience the famous splavovi (river barge clubs) for unforgettable nights, visit the Museum of Yugoslav History, and explore the Zemun district cobbled streets. This tour includes a fortress tour, a city highlights walk, and an evening on the river barges.',
            'category_id' => $catCity->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '3 Days / 2 Nights', 'group_size' => '6-10 People', 'tour_type' => 'City & Nightlife',
            'latitude' => 44.7866, 'longitude' => 20.4489,
        ]);

        // BOSNIA
        $bosnia = Destinations::create([
            'pricing' => '500', 'currency' => 'EUR',
            'title' => 'Sarajevo & Mostar, Bosnia',
            'description' => 'Where East meets West -- Ottoman bazaars, the iconic Stari Most bridge, and a city of resilient spirit.',
            'content' => 'Bosnia is a country of stunning beauty and deep history. Sarajevo is a unique blend of Ottoman, Austro-Hungarian, and Yugoslav heritage -- visit the Bascarsija bazaar, the Gazi Husrev-bey Mosque, and the Latin Bridge. Drive to Mostar to see the iconic Stari Most (Old Bridge) reconstructed after the war, watch divers leap from its arch, and wander through cobblestone streets. This tour includes a Sarajevo war history tour, a Mostar walking tour, and a traditional Bosnian coffee tasting.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Historical & Cultural',
            'latitude' => 43.8563, 'longitude' => 18.4131,
        ]);

        // SLOVENIA
        $slovenia = Destinations::create([
            'pricing' => '700', 'currency' => 'EUR',
            'title' => 'Ljubljana & Lake Bled, Slovenia',
            'description' => 'A fairytale lake with an island church, a charming green capital, and the stunning Julian Alps.',
            'content' => 'Slovenia is Europe hidden green heart. Visit Lake Bled -- a fairytale scene with a church on an island and a medieval castle perched on a cliff. Row to the island, ring the wishing bell, and eat the famous Bled cream cake. Explore Ljubljana, one of Europe most liveable small capitals -- with its dragon bridge, riverside cafes, and the hilltop castle. Drive into the Julian Alps for Lake Bohinj and Triglav National Park. This tour includes a Bled Island visit, a Ljubljana walking tour, and a Triglav National Park hike.',
            'category_id' => $catNature->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Nature & Adventure',
            'latitude' => 46.1512, 'longitude' => 14.9955,
        ]);

        // BULGARIA
        $bulgaria = Destinations::create([
            'pricing' => '500', 'currency' => 'EUR',
            'title' => 'Sofia & Plovdiv, Bulgaria',
            'description' => 'Europe oldest continuously inhabited city, Rila Monastery, and the Balkan Mountains.',
            'content' => 'Bulgaria is a treasure trove of history and nature. Explore Sofia with its onion-domed Alexander Nevsky Cathedral, Roman ruins scattered through the city centre, and Vitosha Mountain as a backdrop. Drive to Plovdiv -- Europe oldest living city (6000+ years) with a perfectly preserved Roman theatre, colourful Revival-period houses, and a creative arts scene. Visit the Rila Monastery, a UNESCO site with stunning frescoes. This tour includes a Plovdiv walking tour, a Rila Monastery day trip, and a traditional Bulgarian dinner with folk dancing.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Historical & Cultural',
            'latitude' => 42.6977, 'longitude' => 23.3219,
        ]);

        // ESTONIA
        $estonia = Destinations::create([
            'pricing' => '680', 'currency' => 'EUR',
            'title' => 'Tallinn, Estonia',
            'description' => 'A perfectly preserved medieval Hanseatic city, cutting-edge digital society, and Baltic coastal beauty.',
            'content' => 'Tallinn is a medieval fairy tale. Walk through the cobblestone streets of the Old Town (UNESCO-listed) with its ancient city walls, defensive towers, and the Town Hall Square. Visit the Alexander Nevsky Cathedral and Toompea Castle. Explore the creative Kalamaja district and the trendy Telliskivi creative quarter. Estonia is also one of the world most digitally advanced nations -- experience e-Estonia. This tour includes a guided Old Town walk, a visit to the Seaplane Harbour Museum, and a day trip to Lahemaa National Park.',
            'category_id' => $catCity->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '3 Days / 2 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & City',
            'latitude' => 59.4370, 'longitude' => 24.7536,
        ]);

        // LATVIA
        $latvia = Destinations::create([
            'pricing' => '600', 'currency' => 'EUR',
            'title' => 'Riga, Latvia',
            'description' => 'The world finest collection of Art Nouveau architecture, a lively Baltic capital, and white sand beaches.',
            'content' => 'Riga is a Baltic beauty. Stroll through the cobbled streets of Old Town, visit the House of the Black Heads, and climb St Peter Church tower for panoramic views. Riga has the world highest concentration of Art Nouveau architecture -- walk Alberta Street for the best examples. Relax on Jurmala white sand beaches, just 20 minutes from the city. This tour includes an Art Nouveau walking tour, a Central Market visit (one of Europe largest), and a day trip to the Gauja National Park with Turaida Castle.',
            'category_id' => $catCity->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '3 Days / 2 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & City',
            'latitude' => 56.9496, 'longitude' => 24.1052,
        ]);

        // LITHUANIA
        $lithuania = Destinations::create([
            'pricing' => '550', 'currency' => 'EUR',
            'title' => 'Vilnius & Trakai, Lithuania',
            'description' => 'Baroque architecture, a stunning island castle, and the self-declared Republic of Uzupis.',
            'content' => 'Vilnius is a Baroque masterpiece. Wander through the largest surviving Old Town in Eastern Europe (UNESCO), visit the Gate of Dawn and St Anne Church, and explore the bohemian Republic of Uzupis with its own constitution, president, and army. Drive to Trakai Island Castle -- a fairy-tale red-brick castle on an island in Lake Galve. This tour includes a Vilnius walking tour, an Uzupis exploration, and a Trakai kayaking trip with castle visit.',
            'category_id' => $catCity->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '3 Days / 2 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Historical & Nature',
            'latitude' => 54.6872, 'longitude' => 25.2797,
        ]);

        // RUSSIA
        $moscow = Destinations::create([
            'pricing' => '1800', 'currency' => 'USD',
            'title' => 'Moscow, Russia',
            'description' => 'The Kremlin, Red Square, St Basil Cathedral, and the immense scale of Russia powerful capital.',
            'content' => 'Moscow is a city of staggering scale and beauty. Stand in Red Square before the candy-coloured St Basil Cathedral, tour the Kremlin cathedrals and the Armoury Chamber with its Faberge eggs, and walk through the vast Alexander Garden. Descend into the Moscow Metro -- a palace-like underground museum of socialist realism. Explore the Tretyakov Gallery, Gorky Park, and the soaring skyscrapers of Moscow City. This tour includes a Kremlin guided tour, a Metro art tour, and a traditional Russian banya experience.',
            'category_id' => $catCity->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Historical',
            'latitude' => 55.7558, 'longitude' => 37.6173,
        ]);
        $stpetersburg = Destinations::create([
            'pricing' => '1700', 'currency' => 'USD',
            'title' => 'St Petersburg, Russia',
            'description' => 'The Venice of the North -- the Hermitage Museum, canals, imperial palaces, and White Nights.',
            'content' => 'St Petersburg is Russia cultural soul. Spend days in the Hermitage Museum (one of the world greatest art collections) housed in the Winter Palace. Visit the Peterhof Fountain Park, Catherine Palace with the Amber Room, and the Church of the Saviour on Spilled Blood. Cruise the canals at night during the White Nights season. This tour includes a Hermitage guided visit, a Peterhof day trip, and a classical ballet performance at the Mariinsky Theatre.',
            'category_id' => $catCity->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Historical',
            'latitude' => 59.9343, 'longitude' => 30.3351,
        ]);

        // SAUDI ARABIA
        $saudi = Destinations::create([
            'pricing' => '2500', 'currency' => 'SAR',
            'title' => 'AlUla & Riyadh, Saudi Arabia',
            'description' => 'The birthplace of Arabia -- Nabataean tombs carved into sandstone, futuristic cities rising from the desert.',
            'content' => 'Saudi Arabia has opened its doors to the world. Explore AlUla, a vast desert valley with the UNESCO-listed Hegra (Madain Saleh) -- Nabataean tombs carved into sandstone cliffs, similar to Petra but much less crowded. Visit the Maraya mirror concert hall, the Elephant Rock, and the ancient Dadan kingdom. Continue to Riyadh for the Kingdom Centre views, the National Museum, and the historic Masmak Fortress. This tour includes a Hegra guided tour, a desert stargazing experience, and a Saudi culinary evening.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Historical & Adventure',
            'latitude' => 26.6422, 'longitude' => 49.8183,
        ]);

        // ECUADOR
        $ecuador = Destinations::create([
            'pricing' => '1200', 'currency' => 'USD',
            'title' => 'Quito & Banos, Ecuador',
            'description' => 'The middle of the world -- the best-preserved colonial capital in Latin America and the Avenue of the Volcanoes.',
            'content' => 'Ecuador is a country of superlatives. Explore Quito UNESCO Old Town -- one of the best-preserved colonial centres in the Americas with its golden churches and grand plazas. Stand on the Equator at Mitad del Mundo. Drive through the Avenue of the Volcanoes to Banos, the adventure sports capital of Ecuador -- known for swing at the End of the World at Casa del Arbol, hot springs, and waterfall hikes. This tour includes a Quito colonial walking tour, an Equator visit, and a Banos adventure day.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Adventure',
            'latitude' => -0.2299, 'longitude' => -78.5249,
        ]);

        // CHILE
        $chile = Destinations::create([
            'pricing' => '2200', 'currency' => 'USD',
            'title' => 'Santiago & Atacama Desert, Chile',
            'description' => 'The driest desert on Earth, the Andes, starry skies, and world-class wine in the longest country on Earth.',
            'content' => 'Chile stretches across every climate. Explore Santiago -- a modern city with a stunning backdrop of the Andes, visit the historic Plaza de Armas, the Museo de la Memoria, and the bohemian neighbourhood of Bellavista. Fly north to San Pedro de Atacama for the driest non-polar desert on Earth. Visit the Tatio Geysers at sunrise, Moon Valley, salt flats with flamingos, and stargaze at some of the clearest skies on the planet. This tour includes a Santiago city tour, a Maipo Valley wine tasting, and a 3-day Atacama desert expedition.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Nature',
            'latitude' => -33.4489, 'longitude' => -70.6693,
        ]);

        // PAKISTAN
        $pakistan = Destinations::create([
            'pricing' => '1500', 'currency' => 'USD',
            'title' => 'Hunza Valley & Lahore, Pakistan',
            'description' => 'The world highest mountains, the legendary Karakoram Highway, Mughal architecture, and unmatched hospitality.',
            'content' => 'Pakistan is one of the most beautiful and welcoming countries on Earth. Fly to Gilgit and drive into the Hunza Valley -- a paradise surrounded by Rakaposhi, Ultar Sar, and the Karakoram peaks. Visit the ancient Baltit and Altit Forts, walk through apricot orchards, and hike to Eagles Nest for panoramic views. A separate trip to Lahore reveals the Mughal treasures of Badshahi Mosque, Lahore Fort, and the Walled City colourful bazaars. This tour includes a Hunza Valley trek, a Forts tour, and a Lahore food walk through the legendary Food Street.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Culture',
            'latitude' => 35.9200, 'longitude' => 74.3100,
        ]);

        // BAHAMAS
        $bahamas = Destinations::create([
            'pricing' => '2200', 'currency' => 'USD',
            'title' => 'Nassau & Exumas, Bahamas',
            'description' => 'Pig Beach, swimming pigs, crystal-clear turquoise waters, and 700 tropical islands of paradise.',
            'content' => 'The Bahamas is an archipelago of pure bliss. Explore Nassau pastel-coloured colonial buildings, the Queen Staircase, and the Atlantis Resort water park. Fly or boat to the Exumas -- famous for swimming pigs at Pig Beach, nurse sharks, iguanas, and some of the clearest water on the planet. Swim with tropical fish, visit the Thunderball Grotto, and relax on deserted sandbars. This package includes an Exuma cays boat tour, pig beach visit, and a Nassau historical walking tour.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Beach & Adventure',
            'latitude' => 25.0343, 'longitude' => -77.3963,
        ]);

        // BELIZE
        $belize = Destinations::create([
            'pricing' => '1400', 'currency' => 'BZD',
            'title' => 'Belize -- Great Blue Hole & Caves',
            'description' => 'The Great Blue Hole, ancient Maya cities, jungle caves, and the longest barrier reef in the Western Hemisphere.',
            'content' => 'Belize is a Caribbean wonder. Fly over or dive the Great Blue Hole -- a giant marine sinkhole, part of the Belize Barrier Reef Reserve System (UNESCO). Explore the Maya ruins of Caracol and Xunantunich rising from the jungle, tube through ancient cave systems at ATM Cave (Actun Tunichil Muknal), and spot howler monkeys and toucans. This tour includes a Great Blue Hole flyover or dive, an ATM cave tubing adventure, and a guided Maya ruin tour.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Historical',
            'latitude' => 17.1899, 'longitude' => -88.4976,
        ]);

        // PUERTO RICO
        $puertorico = Destinations::create([
            'pricing' => '1300', 'currency' => 'USD',
            'title' => 'San Juan & El Yunque, Puerto Rico',
            'description' => 'Colourful colonial streets, the only tropical rainforest in the US National Forest System, and bioluminescent bays.',
            'content' => 'Puerto Rico is a vibrant US territory with a soul of its own. Explore the colourful streets of Old San Juan with its massive fortress walls, blue cobblestones, and pastel colonial buildings. Visit El Yunque National Forest -- the only tropical rainforest in the US system, with waterfalls, hiking trails, and endemic wildlife. Kayak on Mosquito Bay in Vieques, the brightest bioluminescent bay in the world. This tour includes a San Juan walking tour, an El Yunque hike and waterfall swim, and a bioluminescent bay kayaking night tour.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Nature & City',
            'latitude' => 18.4655, 'longitude' => -66.1057,
        ]);

        // Attach tags for new destinations
        $masaimara->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $nairobi->tags()->attach([$tagCity->id, $tagNature->id, $tagTravel->id]);
        $mombasa->tags()->attach([$tagBeach->id, $tagCulture->id, $tagTravel->id]);
        $lamu->tags()->attach([$tagCulture->id, $tagBeach->id, $tagTravel->id]);
        $naivasha->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $algiers->tags()->attach([$tagCity->id, $tagCulture->id, $tagTravel->id]);
        $constantine->tags()->attach([$tagCulture->id, $tagTravel->id, $tagAdventure->id]);
        $ghardaia->tags()->attach([$tagCulture->id, $tagTravel->id, $tagAdventure->id]);
        $oran->tags()->attach([$tagCity->id, $tagCulture->id, $tagFood->id]);
        $tassili->tags()->attach([$tagAdventure->id, $tagNature->id, $tagTravel->id]);
        $athens->tags()->attach([$tagCulture->id, $tagTravel->id, $tagFood->id]);
        $meteora->tags()->attach([$tagAdventure->id, $tagCulture->id, $tagTravel->id]);
        $belgium->tags()->attach([$tagCity->id, $tagFood->id, $tagCulture->id]);
        $montenegro->tags()->attach([$tagAdventure->id, $tagBeach->id, $tagNature->id]);
        $malta->tags()->attach([$tagCulture->id, $tagBeach->id, $tagTravel->id]);
        $cyprus->tags()->attach([$tagBeach->id, $tagCulture->id, $tagTravel->id]);
        $georgia->tags()->attach([$tagAdventure->id, $tagFood->id, $tagCulture->id]);
        $serbia->tags()->attach([$tagCity->id, $tagCulture->id, $tagFood->id]);
        $bosnia->tags()->attach([$tagCulture->id, $tagCity->id, $tagTravel->id]);
        $slovenia->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $bulgaria->tags()->attach([$tagCulture->id, $tagTravel->id, $tagFood->id]);
        $estonia->tags()->attach([$tagCity->id, $tagCulture->id, $tagTravel->id]);
        $latvia->tags()->attach([$tagCity->id, $tagCulture->id, $tagTravel->id]);
        $lithuania->tags()->attach([$tagCity->id, $tagCulture->id, $tagNature->id]);
        $moscow->tags()->attach([$tagCity->id, $tagCulture->id, $tagTravel->id]);
        $stpetersburg->tags()->attach([$tagCity->id, $tagCulture->id, $tagTravel->id]);
        $saudi->tags()->attach([$tagAdventure->id, $tagCulture->id, $tagTravel->id]);
        $ecuador->tags()->attach([$tagAdventure->id, $tagNature->id, $tagCulture->id]);
        $chile->tags()->attach([$tagAdventure->id, $tagNature->id, $tagTravel->id]);
        $pakistan->tags()->attach([$tagAdventure->id, $tagNature->id, $tagCulture->id]);
        $bahamas->tags()->attach([$tagBeach->id, $tagAdventure->id, $tagNature->id]);
        $belize->tags()->attach([$tagAdventure->id, $tagNature->id, $tagTravel->id]);
        $puertorico->tags()->attach([$tagNature->id, $tagCity->id, $tagAdventure->id]);

        // Attach tags
        $warsaw->tags()->attach([$tagCulture->id, $tagCity->id, $tagTravel->id]);
        $porto->tags()->attach([$tagFood->id, $tagCulture->id, $tagTravel->id]);
        $bali2->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $crete->tags()->attach([$tagBeach->id, $tagCulture->id, $tagFood->id]);
        $transylvania->tags()->attach([$tagAdventure->id, $tagCulture->id, $tagTravel->id]);
        $laos->tags()->attach([$tagCulture->id, $tagNature->id, $tagTravel->id]);
        $almaty->tags()->attach([$tagAdventure->id, $tagNature->id, $tagTravel->id]);
        $iran->tags()->attach([$tagCulture->id, $tagTravel->id, $tagFood->id]);
        $uganda->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $mozambique->tags()->attach([$tagBeach->id, $tagNature->id, $tagTravel->id]);
        $gambia->tags()->attach([$tagNature->id, $tagCulture->id, $tagTravel->id]);
        $chicago->tags()->attach([$tagCity->id, $tagFood->id, $tagCulture->id]);
        $vancouver->tags()->attach([$tagCity->id, $tagNature->id, $tagAdventure->id]);
        $guatemala->tags()->attach([$tagCulture->id, $tagAdventure->id, $tagNature->id]);
        $neworleans->tags()->attach([$tagCity->id, $tagFood->id, $tagCulture->id]);
        $panama->tags()->attach([$tagAdventure->id, $tagNature->id, $tagTravel->id]);
        $jerusalem->tags()->attach([$tagCulture->id, $tagTravel->id, $tagFood->id]);
        $bahrain->tags()->attach([$tagCulture->id, $tagCity->id, $tagTravel->id]);
        $andaman->tags()->attach([$tagBeach->id, $tagNature->id, $tagTravel->id]);
        $reunion->tags()->attach([$tagAdventure->id, $tagNature->id, $tagTravel->id]);
        $capeverde->tags()->attach([$tagBeach->id, $tagCulture->id, $tagAdventure->id]);
        $cookislands->tags()->attach([$tagBeach->id, $tagNature->id, $tagTravel->id]);
        $vanuatu->tags()->attach([$tagAdventure->id, $tagNature->id, $tagCulture->id]);

        // ARMENIA
        $armenia = Destinations::create([
            'pricing' => '700', 'currency' => 'USD',
            'title' => 'Yerevan & Tatev, Armenia',
            'description' => 'The first Christian nation -- ancient monasteries, Mount Ararat views, brandy, and the warmest Caucasus hospitality.',
            'content' => 'Armenia is a hidden gem of the Caucasus. Explore Yerevan with its pink tuff buildings, Republic Square dancing fountains, and the Cascade complex. Visit the ancient Geghard Monastery carved into the mountain, Garni Temple, and the Sevanavank Monastery on Lake Sevan. Take the Wings of Tatev cable car to the 9th-century Tatev Monastery. Taste Armenian brandy, lavash bread, and khorovats (barbecue). This tour includes a Yerevan city tour, a Tatev Monastery visit, and a brandy tasting.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Historical',
            'latitude' => 40.1792, 'longitude' => 44.4991,
        ]);

        // AZERBAIJAN
        $azerbaijan = Destinations::create([
            'pricing' => '650', 'currency' => 'USD',
            'title' => 'Baku & Gobustan, Azerbaijan',
            'description' => 'The Land of Fire -- futuristic flame towers, ancient mud volcanoes, petroglyphs, and Caspian Sea shores.',
            'content' => 'Azerbaijan is where East meets West. Explore Baku s UNESCO-listed Old City with the Maiden Tower and Shirvanshah Palace, then marvel at the modern Flame Towers and Heydar Aliyev Center. Drive to Gobustan to see 40,000-year-old petroglyphs and bubbling mud volcanoes. Visit the Yanar Dag burning mountain and Ateshgah fire temple. This tour includes a Baku walking tour, a Gobustan day trip, and a traditional Azerbaijani dinner.',
            'category_id' => $catCity->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Modern',
            'latitude' => 40.4093, 'longitude' => 49.8671,
        ]);

        // NICARAGUA
        $nicaragua = Destinations::create([
            'pricing' => '600', 'currency' => 'USD',
            'title' => 'Granada & Ometepe, Nicaragua',
            'description' => 'Colonial charm, twin volcanoes rising from a lake, surfing beaches, and the rhythm of Nica life.',
            'content' => 'Nicaragua is Central America s best-kept secret. Explore Granada s colourful colonial streets, take a boat through the isletas of Lake Nicaragua, and hike the Mombacho Volcano. Cross the lake to Ometepe Island -- two volcanoes (Concepción and Maderas) connected by an isthmus, with hiking, kayaking, and wildlife. Head to San Juan del Sur for world-class surfing. This tour includes a Granada walking tour, an Ometepe hike, and a surf lesson.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Culture',
            'latitude' => 11.9344, 'longitude' => -85.9563,
        ]);

        // EL SALVADOR
        $elsalvador = Destinations::create([
            'pricing' => '500', 'currency' => 'USD',
            'title' => 'El Salvador -- Surf & Volcanoes',
            'description' => 'The world s best surfing waves, volcanoes you can hike, pupusas, and the rejuvenated heart of Central America.',
            'content' => 'El Salvador is Central America s renaissance destination. Surf the legendary waves of La Libertad and El Tunco, hike the Santa Ana Volcano to its emerald crater lake, and explore the colourful colonial town of Suchitoto. Visit the Joya de Cerén archaeological site (the Pompeii of the Americas) and the Ruta de las Flores. This tour includes a surf camp experience, a volcano sunrise hike, and a pupusa-making class.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Culture',
            'latitude' => 13.7942, 'longitude' => -88.8965,
        ]);

        // URUGUAY
        $uruguay = Destinations::create([
            'pricing' => '700', 'currency' => 'USD',
            'title' => 'Montevideo & Colonia, Uruguay',
            'description' => 'South America s most underrated country -- laid-back beaches, colonial gems, and the world s best beef.',
            'content' => 'Uruguay is the quiet neighbour that outshines the rest. Explore Montevideo s Mercado del Puerto for the best asado in South America, walk the Rambla coastline, and visit the colourful Ciudad Vieja. Drive to Colonia del Sacramento, a UNESCO-listed colonial gem with cobblestone streets, Portuguese and Spanish architecture, and stunning Rio de la Plata sunsets. Head to Punta del Este for glamorous beaches and the iconic La Mano sculpture. This tour includes a Colonia walking tour, a Montevideo food tour, and a Punta del Este day trip.',
            'category_id' => $catCity->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Beach',
            'latitude' => -34.9011, 'longitude' => -56.1645,
        ]);

        // SLOVAKIA
        $slovakia = Destinations::create([
            'pricing' => '400', 'currency' => 'EUR',
            'title' => 'Bratislava & High Tatras, Slovakia',
            'description' => 'A fairytale capital, the tallest peaks of the Carpathians, medieval castles, and Europe s best hiking value.',
            'content' => 'Slovakia punches above its weight. Explore Bratislava s charming Old Town, the hilltop castle, and the quirky UFO observation deck. Drive to the High Tatras, the smallest alpine mountain range in Europe -- hike to stunning mountain lakes (Štrbské Pleso, Popradské Pleso), take the cable car to Lomnický štít, and spot chamois and marmots. Visit Spiš Castle, one of the largest castle complexes in Europe. This tour includes a Bratislava walking tour, a High Tatras hike, and a castle visit.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Adventure & Nature',
            'latitude' => 48.1486, 'longitude' => 17.1077,
        ]);

        // NORTH MACEDONIA
        $northmacedonia = Destinations::create([
            'pricing' => '350', 'currency' => 'EUR',
            'title' => 'Ohrid & Skopje, North Macedonia',
            'description' => 'A stunning lake with ancient churches, a capital of statues, and the crossroads of Balkan history.',
            'content' => 'North Macedonia is a Balkan treasure. Explore Lake Ohrid, one of Europe s oldest and deepest lakes -- a UNESCO site with crystal-clear waters, ancient churches, and the hilltop Tsar Samuel s Fortress. Visit the Church of St John at Kaneo with its iconic lakeside setting. Return to Skopje to see the massive statue-filled Macedonia Square, the Stone Bridge, and the historic Old Bazaar. This tour includes an Ohrid lake cruise, a guided Old Town walk, and a traditional Macedonian dinner.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Nature',
            'latitude' => 41.9973, 'longitude' => 21.4280,
        ]);

        // LUXEMBOURG
        $luxembourg = Destinations::create([
            'pricing' => '500', 'currency' => 'EUR',
            'title' => 'Luxembourg City',
            'description' => 'A fairy-tale capital of deep gorges, medieval fortifications, and the heart of European institutions.',
            'content' => 'Luxembourg is one of Europe s most beautiful small countries. The capital is a UNESCO World Heritage site built on multiple levels connected by bridges and elevators. Explore the Bock and Casemates -- underground fortifications carved into the cliffside. Walk through the Grund district along the Alzette River, visit the Grand Ducal Palace, and hike the Mullerthal Trail (Little Switzerland). This tour includes a city walking tour, a casemates visit, and a wine-tasting in the Moselle Valley.',
            'category_id' => $catCity->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '2 Days / 1 Night', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & City',
            'latitude' => 49.6117, 'longitude' => 6.1300,
        ]);

        // KYRGYZSTAN
        $kyrgyzstan = Destinations::create([
            'pricing' => '800', 'currency' => 'USD',
            'title' => 'Kyrgyzstan -- Issyk-Kul & Tien Shan',
            'description' => 'The Switzerland of Central Asia -- alpine lakes, yurt stays, eagle hunters, and the legendary Tien Shan mountains.',
            'content' => 'Kyrgyzstan is a nomad s paradise. Explore Bishkek s Soviet-era architecture, Ala-Too Square, and the Osh Bazaar. Drive to Lake Issyk-Kul, the world s second-largest alpine lake -- swim in its clear waters, stay in a yurt camp on the shores, and hike in the surrounding Tien Shan mountains. Visit the Skazka Canyon, the Burana Tower, and see traditional eagle hunting. This tour includes a yurt camp overnight, an Issyk-Kul lake day, and a horseback riding adventure.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Nature',
            'latitude' => 42.8746, 'longitude' => 74.5698,
        ]);

        // PALESTINE
        $palestine = Destinations::create([
            'pricing' => '500', 'currency' => 'USD',
            'title' => 'Bethlehem & Jericho, Palestine',
            'description' => 'The birthplace of Jesus, the oldest city on Earth, and the resilient heart of Palestinian culture.',
            'content' => 'Palestine is a land of profound history and vibrant culture. Visit Bethlehem s Church of the Nativity (birthplace of Jesus), explore the Banksy artwork on the separation wall, and wander through the Old City s markets. Drive to Jericho, the oldest continuously inhabited city in the world -- see the Mount of Temptation and Hisham s Palace. Visit Ramallah for modern Palestinian culture, and taste maqluba, musakhan, and knafeh. This tour includes a Bethlehem guided walk, a Jericho archaeological visit, and a Palestinian cooking class.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Religious & Cultural',
            'latitude' => 31.7054, 'longitude' => 35.2024,
        ]);

        // ZAMBIA
        $zambia = Destinations::create([
            'pricing' => '2500', 'currency' => 'USD',
            'title' => 'Victoria Falls & South Luangwa, Zambia',
            'description' => 'The Zambian side of the Smoke that Thunders, walking safaris, and one of Africa s greatest wildlife parks.',
            'content' => 'Zambia offers Africa s most authentic safari. View Victoria Falls from the Zambian side with the stunning Knife Edge Bridge and Boiling Pot views -- swim in the Devil s Pool at the edge of the falls. Then head to South Luangwa National Park, the birthplace of walking safaris. Track lions, leopards, and wild dogs on foot with expert guides, see enormous herds of elephants, and sleep in a bush camp under the stars. This tour includes a falls tour, a Devil s Pool visit, and a 4-day South Luangwa walking safari.',
            'category_id' => $catWild->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Wildlife & Adventure',
            'latitude' => -15.4167, 'longitude' => 28.2833,
        ]);

        // MALAWI
        $malawi = Destinations::create([
            'pricing' => '1200', 'currency' => 'USD',
            'title' => 'Malawi -- Lake of Stars',
            'description' => 'The warm heart of Africa -- a massive crystal-clear lake, hiking, wildlife, and the friendliest people on the continent.',
            'content' => 'Malawi is known as the warm heart of Africa. Lake Malawi is one of the great lakes of Africa -- crystal-clear freshwater, sandy beaches, and colourful cichlid fish found nowhere else on Earth. Explore Cape Maclear, relax on the beaches of Nkhata Bay, and kayak on the lake. Visit Liwonde National Park for boat safaris with hippos and elephants, hike Mount Mulanje, and experience local village life. This tour includes a Lake Malawi beach stay, a Liwonde safari, and a village homestay experience.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '10 Days / 9 Nights', 'group_size' => '4-10 People', 'tour_type' => 'Beach & Nature',
            'latitude' => -13.2543, 'longitude' => 34.3015,
        ]);

        // TRINIDAD AND TOBAGO
        $trinidad = Destinations::create([
            'pricing' => '1100', 'currency' => 'USD',
            'title' => 'Trinidad & Tobago',
            'description' => 'The birthplace of Carnival, steelpan, and calypso -- pristine rainforests, coral reefs, and Caribbean energy.',
            'content' => 'Trinidad and Tobago is a dual-island nation of pure vibe. Explore Port of Spain s Queen s Park Savannah, visit the Caroni Bird Sanctuary to see scarlet ibis at sunset, and hike through the Asa Wright Nature Centre. Cross to Tobago for stunning beaches like Pigeon Point and Store Bay, snorkel the Buccoo Reef, and hike to Argyle Waterfall. This tour includes a Caroni swamp tour, a Tobago beach day, and a steelpan music workshop.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Beach & Culture',
            'latitude' => 10.6918, 'longitude' => -61.2225,
        ]);

        // BELARUS
        $belarus = Destinations::create([
            'pricing' => '600', 'currency' => 'USD',
            'title' => 'Minsk & Mir Castle, Belarus',
            'description' => 'The last Soviet republic -- grand Stalinist architecture, pristine forests, and a resilient national identity.',
            'content' => 'Belarus is Europe s last Soviet-style frontier. Explore Minsk s grand Independence Avenue, the KGB Building, and Victory Square. Visit the impressive Mir Castle and Nesvizh Palace -- both UNESCO-listed Renaissance complexes. Drive to the Belovezhskaya Pushcha National Park (the oldest forest in Europe) to see European bison. This tour includes a Minsk Soviet architecture walk, a castle tour, and a bison-spotting forest hike.',
            'category_id' => $catCity->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Historical & City',
            'latitude' => 53.9045, 'longitude' => 27.5615,
        ]);

        // PALAU
        $palau = Destinations::create([
            'pricing' => '3500', 'currency' => 'USD',
            'title' => 'Palau -- Rock Islands & Jellyfish Lake',
            'description' => 'A Pacific paradise of 500+ limestone islands, a lake full of stingless jellyfish, and the world s best diving.',
            'content' => 'Palau is one of the most beautiful island nations on Earth. The Rock Islands are a UNESCO World Heritage site -- hundreds of mushroom-shaped limestone islands rising from turquoise lagoons. Kayak through hidden lagoons and secret beaches, snorkel in Jellyfish Lake with millions of harmless golden jellyfish, and dive world-class sites like Blue Corner and German Channel with sharks, manta rays, and sea turtles. This package includes a Rock Islands kayak tour, a Jellyfish Lake snorkel, and a guided dive excursion.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '2-6 People', 'tour_type' => 'Beach & Diving',
            'latitude' => 7.5150, 'longitude' => 134.5825,
        ]);

        // BANGLADESH
        $bangladesh = Destinations::create([
            'pricing' => '500', 'currency' => 'USD',
            'title' => 'Bangladesh -- Sundarbans & Sylhet',
            'description' => 'The world s largest mangrove forest, Bengal tigers, tea gardens, and the most generous hospitality in Asia.',
            'content' => 'Bangladesh is one of the world s most fascinating and undiscovered destinations. Explore the Sundarbans, the largest mangrove forest in the world -- a UNESCO site where Bengal tigers roam, spotted deer graze, and crocodiles lurk in the waterways. Cruise through the forest on a wooden boat, visit the Sixty Dome Mosque in Bagerhat, and explore the stunning tea gardens of Sylhet with their rolling green hills and terraced plantations. This tour includes a Sundarbans boat safari, a Sylhet tea estate visit, and a rickshaw ride through Old Dhaka.',
            'category_id' => $catNature->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Nature & Cultural',
            'latitude' => 23.6850, 'longitude' => 90.3563,
        ]);

        // IVORY COAST
        $ivorycoast = Destinations::create([
            'pricing' => '1300', 'currency' => 'USD',
            'title' => 'Abidjan & Grand-Bassam, Ivory Coast',
            'description' => 'The Paris of West Africa -- sleek skyscrapers, colonial beach towns, and the vibrant pulse of Ivorian culture.',
            'content' => 'Ivory Coast is West Africa s rising star. Explore Abidjan, a modern metropolis on a lagoon -- visit the St Paul Cathedral, the Plateau business district, and the colourful Treichville market. Drive to Grand-Bassam, a UNESCO-listed colonial beach town with French architecture and sandy beaches. Visit the Yamoussoukro Basilica (the largest church in the world), explore Comoé National Park, and dance to coupé-décalé music. This tour includes an Abidjan city tour, a Grand-Bassam beach day, and a Yamoussoukro basilica visit.',
            'category_id' => $catCity->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & City',
            'latitude' => 5.3600, 'longitude' => -4.0083,
        ]);

        // LIBYA
        $libya = Destinations::create([
            'pricing' => '2000', 'currency' => 'USD',
            'title' => 'Leptis Magna & Tripoli, Libya',
            'description' => 'The best-preserved Roman city in the world, Sahara deserts, and the gateway to North Africa s ancient heritage.',
            'content' => 'Libya holds some of the world s greatest archaeological treasures. Leptis Magna is the most spectacular and best-preserved Roman city outside Italy -- its forum, basilica, theatre, and harbour are almost entirely intact. Explore Tripoli s medina and the Red Castle Museum, visit Sabratha s Roman theatre on the coast, and venture into the Sahara to see the Acacus Mountains with prehistoric rock art and the Ghadames oasis. This tour includes a Leptis Magna guided tour, a Tripoli old city walk, and a Sahara desert expedition.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Historical & Heritage',
            'latitude' => 32.8872, 'longitude' => 13.1913,
        ]);

        // MOLDOVA
        $moldova = Destinations::create([
            'pricing' => '350', 'currency' => 'EUR',
            'title' => 'Moldova -- Wine & Monasteries',
            'description' => 'The world s largest wine cellar, underground wine cities, and the beautiful monasteries of Eastern Europe.',
            'content' => 'Moldova is the undiscovered gem of Eastern Europe. Visit the Cricova Winery with its 120km of underground tunnels holding millions of bottles of wine -- take a guided tour and tasting in an underground city. Explore the Orheiul Vechi cave monastery complex carved into limestone cliffs, and visit the beautiful Capriana Monastery. Walk through Chisinau s parks, taste Moldovan wine, and enjoy traditional mămăligă and sarmale. This tour includes a Cricova wine tour, an Orheiul Vechi visit, and a traditional Moldovan dinner.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '3 Days / 2 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Food',
            'latitude' => 47.0105, 'longitude' => 28.8638,
        ]);

        // KOSOVO
        $kosovo = Destinations::create([
            'pricing' => '300', 'currency' => 'EUR',
            'title' => 'Prizren & Pristina, Kosovo',
            'description' => 'Europe s youngest country -- Ottoman mosques, medieval monasteries, and the warmest welcome in the Balkans.',
            'content' => 'Kosovo is the surprise package of the Balkans. Explore Prizren, one of the most beautiful towns in the region -- its Ottoman-era Old Town, Sinan Pasha Mosque, and the Kalaja fortress overlooking the city. Visit the Gračanica Monastery (UNESCO), hike in the Rugova Valley, and explore Pristina s quirky cafes, the National Library, and the Newborn monument. This tour includes a Prizren walking tour, a Rugova Canyon hike, and a traditional flija dinner.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '3 Days / 2 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Cultural & Nature',
            'latitude' => 42.6026, 'longitude' => 20.9030,
        ]);

        // ANDORRA
        $andorra = Destinations::create([
            'pricing' => '600', 'currency' => 'EUR',
            'title' => 'Andorra -- Pyrenees & Valleys',
            'description' => 'A tiny principality in the Pyrenees -- duty-free shopping, world-class skiing, and stunning mountain hikes.',
            'content' => 'Andorra is a small but mighty country in the heart of the Pyrenees. In winter, ski at Grandvalira and Vallnord -- some of Europe s best snow. In summer, hike through the Madriu-Perafita-Claror Valley (UNESCO), visit the Romanesque churches of the valley, and explore the capital Andorra la Vella with its duty-free shopping and the Casa de la Vall parliament building. This tour includes a mountain hike, a spa visit to Caldea (Europe s largest thermal spa), and a guided city walk.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Relaxation',
            'latitude' => 42.5063, 'longitude' => 1.5218,
        ]);

        // SAMOA
        $samoa = Destinations::create([
            'pricing' => '2000', 'currency' => 'USD',
            'title' => 'Samoa -- The Treasured Islands',
            'description' => 'The heart of Polynesia -- cascading waterfalls, pristine beaches, traditional fale stays, and the legendary To Sua Ocean Trench.',
            'content' => 'Samoa is the essence of Polynesia. Swim in the magnificent To Sua Ocean Trench -- a giant natural swimming hole surrounded by lush gardens. Explore the Alofaaga Blowholes, relax on the white sands of Lalomanu Beach, and hike through the O Le Pupu-Pu e National Park. Stay in a traditional beach fale, experience a Samoan ava ceremony, and watch a fire dance performance. This tour includes a To Sua visit, a traditional village stay, and a snorkelling excursion.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Beach & Culture',
            'latitude' => -13.7590, 'longitude' => -172.1046,
        ]);

        // TONGA
        $tonga = Destinations::create([
            'pricing' => '2500', 'currency' => 'USD',
            'title' => 'Tonga -- Whales & Vava u',
            'description' => 'The Kingdom of Tonga -- swim with humpback whales, explore limestone caves, and experience authentic Polynesian royalty.',
            'content' => 'Tonga is the only kingdom in the South Pacific and one of the best places on Earth to swim with humpback whales (July-October). Explore the Vava u archipelago -- a stunning group of 60+ islands with limestone caves, pristine beaches, and turquoise lagoons. Kayak through the Swallows Cave, visit the blowholes at Mapu a Vaea, and experience a traditional Tongan feast and dance. This tour includes a whale swimming excursion, a Vava u island-hopping day, and a Tongan cultural evening.',
            'category_id' => $catBeach->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '9 Days / 8 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Beach & Wildlife',
            'latitude' => -21.1789, 'longitude' => -175.1978,
        ]);

        // GUYANA
        $guyana = Destinations::create([
            'pricing' => '1800', 'currency' => 'USD',
            'title' => 'Guyana -- Kaieteur Falls & Rainforest',
            'description' => 'One of the world s most powerful waterfalls, pristine Amazon rainforest, and the only English-speaking country in South America.',
            'content' => 'Guyana is South America s best-kept secret. Fly over the jungle to Kaieteur Falls -- one of the world s most powerful waterfalls (5x the height of Niagara). Explore the Iwokrama Rainforest, hike to the top of Turtle Mountain, and spot giant river otters, harpy eagles, and jaguars. Stay at an eco-lodge in the jungle, visit Amerindian villages, and explore the historic capital Georgetown with its wooden St George s Cathedral. This tour includes a Kaieteur flyover, a rainforest multi-day expedition, and a Georgetown city tour.',
            'category_id' => $catNature->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Nature & Adventure',
            'latitude' => 4.8604, 'longitude' => -58.9302,
        ]);

        // SURINAME
        $suriname = Destinations::create([
            'pricing' => '1400', 'currency' => 'USD',
            'title' => 'Suriname -- Paramaribo & Jungle',
            'description' => 'A Dutch colonial gem in the Amazon -- wooden architecture, diverse cultures, and vast uninhabited rainforest.',
            'content' => 'Suriname is one of the most culturally diverse countries in the world. Explore Paramaribo s UNESCO-listed historic inner city with its Dutch wooden colonial buildings, the St Peter and Paul Cathedral, and the lively Central Market. Travel up the Suriname River to the Upper Suriname to experience Maroon villages and see how life has remained unchanged for centuries. Hike through the Central Suriname Nature Reserve (UNESCO), spot birds and monkeys, and visit the Raleighvallen waterfalls. This tour includes a Paramaribo walking tour, a Maroon village boat trip, and a rainforest hike.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Cultural & Nature',
            'latitude' => 5.8520, 'longitude' => -55.2038,
        ]);

        // FRENCH GUIANA
        $frenchguiana = Destinations::create([
            'pricing' => '2000', 'currency' => 'EUR',
            'title' => 'French Guiana -- Space & Jungle',
            'description' => 'The European Spaceport in the Amazon, Devil s Island, and some of the most pristine rainforest on Earth.',
            'content' => 'French Guiana is a French department in South America -- European standards in the heart of the Amazon. Visit the Guiana Space Centre in Kourou and watch a rocket launch. Explore the Salvation Islands (Îles du Salut), including the infamous Devil s Island prison. Hike through the Amazon rainforest to see howler monkeys, caimans, and tropical birds. Visit Cayenne s colonial centre and taste Creole cuisine. This tour includes a space centre visit, a Salvation Islands boat tour, and a jungle expedition.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '6 Days / 5 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Adventure & Science',
            'latitude' => 4.9333, 'longitude' => -52.3333,
        ]);

        // SUDAN
        $sudan = Destinations::create([
            'pricing' => '1500', 'currency' => 'USD',
            'title' => 'Sudan -- Pyramids of Meroe',
            'description' => 'More pyramids than Egypt, untouched, and one of the most incredible archaeological sites in the world.',
            'content' => 'Sudan is home to more pyramids than Egypt. The Royal Necropolis of Meroe has over 200 Nubian pyramids scattered across the desert -- smaller and steeper than Egyptian pyramids but incredibly atmospheric and completely tourist-free. Visit the temples of Naqa and Musawwarat es-Sufra, explore the confluence of the Blue and White Niles in Khartoum, and experience Sudanese hospitality -- one of the warmest in the world. This tour includes a Meroe pyramids guided visit, a Khartoum city tour, and a Nile felucca cruise.',
            'category_id' => $catCulture->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Historical & Heritage',
            'latitude' => 15.5007, 'longitude' => 32.5599,
        ]);

        // HONDURAS
        $honduras = Destinations::create([
            'pricing' => '800', 'currency' => 'USD',
            'title' => 'Roatan & Copan, Honduras',
            'description' => 'The second-largest barrier reef in the world, ancient Maya ruins, and Caribbean island paradise.',
            'content' => 'Honduras offers two worlds. Dive or snorkel in Roatan, part of the Mesoamerican Barrier Reef -- the second-largest reef system in the world. Swim with whale sharks, explore coral gardens, and relax on white-sand beaches. On the mainland, visit the Copan Ruins, one of the most important Maya archaeological sites with the famous Hieroglyphic Stairway and intricate stelae. This tour includes a Roatan snorkelling excursion, a Copan guided ruins tour, and a Spanish colonial visit to Comayagua.',
            'category_id' => $catAdventure->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '4-10 People', 'tour_type' => 'Adventure & Historical',
            'latitude' => 15.5000, 'longitude' => -86.5000,
        ]);

        // Attach tags for remaining destinations
        $armenia->tags()->attach([$tagCulture->id, $tagAdventure->id, $tagTravel->id]);
        $azerbaijan->tags()->attach([$tagCity->id, $tagCulture->id, $tagTravel->id]);
        $nicaragua->tags()->attach([$tagAdventure->id, $tagNature->id, $tagCulture->id]);
        $elsalvador->tags()->attach([$tagAdventure->id, $tagBeach->id, $tagNature->id]);
        $uruguay->tags()->attach([$tagCity->id, $tagBeach->id, $tagFood->id]);
        $slovakia->tags()->attach([$tagAdventure->id, $tagNature->id, $tagCulture->id]);
        $northmacedonia->tags()->attach([$tagCulture->id, $tagNature->id, $tagTravel->id]);
        $luxembourg->tags()->attach([$tagCity->id, $tagCulture->id, $tagTravel->id]);
        $kyrgyzstan->tags()->attach([$tagAdventure->id, $tagNature->id, $tagTravel->id]);
        $palestine->tags()->attach([$tagCulture->id, $tagTravel->id, $tagAdventure->id]);
        $zambia->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $malawi->tags()->attach([$tagBeach->id, $tagNature->id, $tagTravel->id]);
        $trinidad->tags()->attach([$tagBeach->id, $tagCulture->id, $tagFood->id]);
        $belarus->tags()->attach([$tagCity->id, $tagCulture->id, $tagTravel->id]);
        $palau->tags()->attach([$tagBeach->id, $tagNature->id, $tagAdventure->id]);
        $bangladesh->tags()->attach([$tagNature->id, $tagCulture->id, $tagAdventure->id]);
        $ivorycoast->tags()->attach([$tagCity->id, $tagCulture->id, $tagBeach->id]);
        $libya->tags()->attach([$tagCulture->id, $tagTravel->id, $tagAdventure->id]);
        $moldova->tags()->attach([$tagFood->id, $tagCulture->id, $tagTravel->id]);
        $kosovo->tags()->attach([$tagCulture->id, $tagNature->id, $tagTravel->id]);
        $andorra->tags()->attach([$tagAdventure->id, $tagNature->id, $tagTravel->id]);
        $samoa->tags()->attach([$tagBeach->id, $tagCulture->id, $tagNature->id]);
        $tonga->tags()->attach([$tagBeach->id, $tagNature->id, $tagAdventure->id]);
        $guyana->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $suriname->tags()->attach([$tagCulture->id, $tagNature->id, $tagAdventure->id]);
        $frenchguiana->tags()->attach([$tagAdventure->id, $tagNature->id, $tagTravel->id]);
        $sudan->tags()->attach([$tagCulture->id, $tagTravel->id, $tagAdventure->id]);
        $honduras->tags()->attach([$tagBeach->id, $tagAdventure->id, $tagCulture->id]);

        // ── NEW: Mountains & Trekking ──────────────────────────────────────
        $catMountains = Category::create(['name' => 'Mountains & Trekking']);

        $swissAlps = Destinations::create([
            'pricing' => '2200', 'currency' => 'CHF',
            'title' => 'Swiss Alps, Switzerland',
            'description' => 'Towering snow-capped peaks, pristine alpine meadows, and world-class hiking trails in the heart of Europe.',
            'content' => 'The Swiss Alps offer some of the most breathtaking mountain scenery on Earth. Explore the iconic Matterhorn in Zermatt, hike the scenic trails around Interlaken and Grindelwald, and take the cogwheel train up to Jungfraujoch — the Top of Europe. In winter, ski the legendary slopes of St. Moritz and Verbier. Enjoy fondue and raclette in charming mountain huts, and breathe in the crisp, clean alpine air. This package includes guided hikes, a Glacier Express ride, and accommodation in traditional Swiss chalets.',
            'category_id' => $catMountains->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '8 Days / 7 Nights', 'group_size' => '6-10 People', 'tour_type' => 'Adventure & Trekking',
            'latitude' => 46.8182, 'longitude' => 8.2275,
        ]);
        $machuPicchu = Destinations::create([
            'pricing' => '1800', 'currency' => 'USD',
            'title' => 'Machu Picchu, Peru',
            'description' => 'The lost Inca citadel perched high in the Andes — a mystical journey through cloud forests and ancient stone pathways.',
            'content' => 'Machu Picchu is the crown jewel of the Inca Empire, set dramatically on a mountain ridge above the Urubamba River Valley. Hike the classic Inca Trail through lush cloud forests and past Inca ruins to reach the Sun Gate at dawn. Explore the Temple of the Sun, the Room of the Three Windows, and the iconic Huayna Picchu peak. This tour includes Cusco city tours, Sacred Valley exploration, and a guided visit to the citadel itself.',
            'category_id' => $catMountains->id, 'image' => 'images/destination-1.jpg', 'published_at' => now(),
            'duration' => '7 Days / 6 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Trekking & History',
            'latitude' => -13.1631, 'longitude' => -72.5450,
        ]);

        // ── NEW: Desert Safari ────────────────────────────────────────────
        $catDesert = Category::create(['name' => 'Desert Safari']);

        $saharaTunisia = Destinations::create([
            'pricing' => '3200', 'currency' => 'TND',
            'title' => 'Sahara Desert, Tunisia',
            'description' => 'Endless golden dunes, ancient Berber villages, star-filled skies, and the timeless beauty of the Great Sahara.',
            'content' => 'Experience the magic of the Tunisian Sahara — a land of sweeping dunes and dramatic desert landscapes. Ride camels across the vast sands of Douz, the Gateway to the Sahara. Explore the underground homes of Matmata, the stunning salt flats of Chott el Djerid, and the film-set beauty of Ong Jmel. Spend a night in a traditional Berber tent under the most brilliant stars you have ever seen. This tour includes 4x4 desert safaris, camel trekking, and visits to ancient ksar fortresses.',
            'category_id' => $catDesert->id, 'image' => 'images/destination-2.jpg', 'published_at' => now(),
            'duration' => '5 Days / 4 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Desert & Culture',
            'latitude' => 33.5000, 'longitude' => 9.0000,
        ]);
        $wadiRum = Destinations::create([
            'pricing' => '950', 'currency' => 'USD',
            'title' => 'Wadi Rum, Jordan',
            'description' => 'The Valley of the Moon — a surreal red desert landscape of towering rock formations and sweeping sandy valleys.',
            'content' => 'Wadi Rum is a protected desert wilderness that feels like another planet. Its vast ochre sands and jagged granite mountains have served as the backdrop for films like The Martian and Dune. Explore by 4x4, hike through narrow canyons to hidden springs, and climb the iconic Burdah Rock Bridge. Spend the night in a Bedouin camp under a canopy of stars, savouring traditional zarb (slow-cooked lamb) and sweet Bedouin tea. This tour includes a jeep safari, rock climbing, and a camel ride at sunset.',
            'category_id' => $catDesert->id, 'image' => 'images/destination-3.jpg', 'published_at' => now(),
            'duration' => '4 Days / 3 Nights', 'group_size' => '4-8 People', 'tour_type' => 'Desert Adventure',
            'latitude' => 29.5833, 'longitude' => 35.4167,
        ]);

        $swissAlps->tags()->attach([$tagNature->id, $tagAdventure->id, $tagTravel->id]);
        $machuPicchu->tags()->attach([$tagCulture->id, $tagAdventure->id, $tagTravel->id]);
        $saharaTunisia->tags()->attach([$tagAdventure->id, $tagNature->id, $tagTravel->id]);
        $wadiRum->tags()->attach([$tagAdventure->id, $tagCulture->id, $tagNature->id]);
    }
}
