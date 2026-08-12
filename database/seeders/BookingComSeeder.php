<?php

namespace Database\Seeders;

use App\Category;
use App\Destinations;
use App\Tag;
use Illuminate\Database\Seeder;

class BookingComSeeder extends Seeder
{
    public function run()
    {
        $catBeach = Category::firstOrCreate(['name' => 'Beach & Relaxation']);
        $catCity = Category::firstOrCreate(['name' => 'City Break']);
        $catCulture = Category::firstOrCreate(['name' => 'Culture & Heritage']);
        $catAdventure = Category::firstOrCreate(['name' => 'Adventure & Sports']);

        $tagBeach = Tag::firstOrCreate(['name' => 'Beach']);
        $tagCity = Tag::firstOrCreate(['name' => 'City']);
        $tagCulture = Tag::firstOrCreate(['name' => 'Culture']);
        $tagFood = Tag::firstOrCreate(['name' => 'Food']);
        $tagNature = Tag::firstOrCreate(['name' => 'Nature']);
        $tagTravel = Tag::firstOrCreate(['name' => 'Travel']);
        $tagAdventure = Tag::firstOrCreate(['name' => 'Adventure']);

        $destinations = [
            [
                'title' => 'Hammamet, Tunisia',
                'pricing' => '350', 'currency' => 'TND',
                'description' => 'A stunning coastal town with golden beaches, lush gardens, and a charming medina overlooking the Mediterranean.',
                'content' => 'Hammamet is one of Tunisia\'s premier beach destinations. Relax on pristine sandy beaches, explore the historic medina with its whitewashed walls and blue doors, and visit the famous Hammamet fortress (Kasbah) with panoramic sea views. The city is known for its jasmine-scented gardens, vibrant nightlife, and world-class resorts. This package includes beachfront accommodation, a guided medina tour, and water sports activities.',
                'category_id' => $catBeach->id,
                'image' => 'images/booking/booking-01.webp',
                'duration' => '5 Days / 4 Nights',
                'group_size' => '4-10 People',
                'tour_type' => 'Beach & Relaxation',
                'tags' => [$tagBeach->id, $tagTravel->id, $tagNature->id],
            ],
            [
                'title' => 'Tunis, Tunisia',
                'pricing' => '280', 'currency' => 'TND',
                'description' => 'The vibrant capital of Tunisia — a captivating blend of ancient medina, French-colonial architecture, and modern urban energy.',
                'content' => 'Tunis is a city where history meets modernity. Explore the UNESCO-listed Medina of Tunis with its labyrinthine alleys, bustling souks, and stunning mosques. Visit the Bardo Museum, home to the world\'s finest collection of Roman mosaics. Stroll through the French-colonial Ville Nouvelle, relax in the Belvedere Park, and taste authentic Tunisian cuisine at a traditional restaurant. This tour includes a guided medina walk, a Bardo Museum visit, and a culinary tour.',
                'category_id' => $catCity->id,
                'image' => 'images/booking/booking-02.webp',
                'duration' => '4 Days / 3 Nights',
                'group_size' => '6-12 People',
                'tour_type' => 'Cultural & Sightseeing',
                'tags' => [$tagCity->id, $tagCulture->id, $tagFood->id],
            ],
            [
                'title' => 'Mahdia, Tunisia',
                'pricing' => '320', 'currency' => 'TND',
                'description' => 'A tranquil fishing port with pristine beaches, ancient Phoenician roots, and some of the best seafood in the Mediterranean.',
                'content' => 'Mahdia is a hidden gem on Tunisia\'s coast. Explore the historic old town with its narrow streets and whitewashed houses, visit the impressive Borj El Kebir fortress, and stroll along the beautiful Corniche beach. The city\'s fishing port offers the freshest seafood, and the nearby Cap Afrique coastline provides stunning snorkelling spots. This package includes beach accommodation, a guided historical tour, and a seafood cooking class.',
                'category_id' => $catBeach->id,
                'image' => 'images/booking/booking-03.webp',
                'duration' => '5 Days / 4 Nights',
                'group_size' => '4-8 People',
                'tour_type' => 'Beach & Culture',
                'tags' => [$tagBeach->id, $tagCulture->id, $tagFood->id],
            ],
            [
                'title' => 'Sousse, Tunisia',
                'pricing' => '300', 'currency' => 'TND',
                'description' => 'A vibrant resort city with a UNESCO-listed medina, golden beaches, and a lively marina that buzzes day and night.',
                'content' => 'Sousse is the pearl of the Sahel. Its UNESCO-listed medina is a treasure trove of Islamic architecture, featuring the Great Mosque and the Ribat fortress. The modern city offers a stunning marina, long sandy beaches, and a vibrant nightlife scene. Visit the Sousse Archaeological Museum, take a boat trip along the coast, and enjoy water parks and golf courses. This package includes resort accommodation, a medina tour, and a catamaran excursion.',
                'category_id' => $catBeach->id,
                'image' => 'images/booking/booking-04.webp',
                'duration' => '6 Days / 5 Nights',
                'group_size' => '6-12 People',
                'tour_type' => 'Beach & Entertainment',
                'tags' => [$tagBeach->id, $tagCulture->id, $tagTravel->id],
            ],
            [
                'title' => 'Tabarka, Tunisia',
                'pricing' => '380', 'currency' => 'TND',
                'description' => 'A picturesque coastal town where the Mediterranean meets lush cork forests — perfect for diving, golf, and nature lovers.',
                'content' => 'Tabarka is a paradise for nature enthusiasts. Known for its stunning coral reefs, it\'s one of the best diving destinations in the Mediterranean. Explore the Genoese fortress overlooking the town, hike through the nearby cork oak forests of Ain Draham, and relax on the beautiful sandy beaches. The annual Jazz Festival brings world-class musicians to this charming town. This package includes diving sessions, a forest trek, and beachfront accommodation.',
                'category_id' => $catAdventure->id,
                'image' => 'images/booking/booking-05.webp',
                'duration' => '5 Days / 4 Nights',
                'group_size' => '4-8 People',
                'tour_type' => 'Adventure & Nature',
                'tags' => [$tagBeach->id, $tagNature->id, $tagAdventure->id],
            ],
            [
                'title' => 'Rabat, Morocco',
                'pricing' => '4500', 'currency' => 'MAD',
                'description' => 'Morocco\'s serene capital — a UNESCO World Heritage city blending Islamic heritage, colonial elegance, and Atlantic coastline.',
                'content' => 'Rabat is Morocco\'s capital and cultural heart. Visit the stunning Hassan Tower and the Mohamed V Mausoleum, explore the charming Oudaya Kasbah overlooking the Atlantic, and wander through the peaceful Andalusian Gardens. The city\'s medina is more relaxed than Marrakech\'s, offering a authentic shopping experience. Visit the contemporary art museum, stroll along the beachfront promenade, and taste delicious Moroccan pastries. This tour includes guided historical visits, a beach walk, and a Moroccan tea ceremony.',
                'category_id' => $catCity->id,
                'image' => 'images/booking/booking-06.webp',
                'duration' => '4 Days / 3 Nights',
                'group_size' => '6-10 People',
                'tour_type' => 'Cultural & Sightseeing',
                'tags' => [$tagCity->id, $tagCulture->id, $tagTravel->id],
            ],
            [
                'title' => 'Conakry, Guinea',
                'pricing' => '1200', 'currency' => 'USD',
                'description' => 'A vibrant West African capital where Atlantic beaches meet lively markets, rich musical traditions, and warm hospitality.',
                'content' => 'Conakry is the bustling capital of Guinea, situated on the Atlantic coast. Explore the colourful Marché Madina, one of West Africa\'s largest markets, visit the Guinea National Museum to learn about the country\'s rich cultural heritage, and relax on the beautiful beaches of the Presqu\'île du Kaloum. Take a boat trip to the nearby Îles de Los for pristine beaches and palm trees. Experience live Mbalax and Afrobeat music in the city\'s vibrant nightlife scene.',
                'category_id' => $catCulture->id,
                'image' => 'images/booking/booking-07.webp',
                'duration' => '6 Days / 5 Nights',
                'group_size' => '4-8 People',
                'tour_type' => 'Cultural & Beach',
                'tags' => [$tagCity->id, $tagCulture->id, $tagTravel->id],
            ],
            [
                'title' => 'Lyon, France',
                'pricing' => '890', 'currency' => 'EUR',
                'description' => 'The gastronomic capital of France — Renaissance charm, traboule passages, and cuisine that defines French culinary art.',
                'content' => 'Lyon is a city for food lovers. Explore the UNESCO-listed Old Town (Vieux Lyon) with its Renaissance architecture and hidden traboules (passageways). Visit the Basilica of Notre-Dame de Fourvière for panoramic views, and wander through the colourful Mur des Canuts mural. Lyon is the gastronomic capital of France — dine at traditional bouchons, taste local specialties like quenelles and praline tarts, and explore the Halles de Lyon Paul Bocuse food market. This tour includes a food walking tour, a wine tasting, and a silk-weaving workshop.',
                'category_id' => $catCulture->id,
                'image' => 'images/booking/booking-08.jpg',
                'duration' => '4 Days / 3 Nights',
                'group_size' => '6-10 People',
                'tour_type' => 'Cultural & Food',
                'tags' => [$tagCity->id, $tagCulture->id, $tagFood->id],
            ],
            [
                'title' => 'Seville, Spain',
                'pricing' => '720', 'currency' => 'EUR',
                'description' => 'The heart of Andalusia — flamenco rhythms, Moorish palaces, orange-blossom streets, and the passion of southern Spain.',
                'content' => 'Seville is the soul of Andalusia. Marvel at the Alcázar palace, a stunning Moorish architectural masterpiece, climb the Giralda tower, and explore the vast Seville Cathedral. Wander through the charming Santa Cruz neighbourhood with its narrow alleys and flower-filled courtyards. Watch an authentic flamenco show in Triana, taste tapas and local sherry, and visit the Plaza de España — one of the most beautiful squares in the world. This tour includes guided Alcázar and cathedral visits, a flamenco workshop, and a tapas crawl.',
                'category_id' => $catCulture->id,
                'image' => 'images/booking/booking-09.jpg',
                'duration' => '5 Days / 4 Nights',
                'group_size' => '6-12 People',
                'tour_type' => 'Cultural & Sightseeing',
                'tags' => [$tagCity->id, $tagCulture->id, $tagFood->id],
            ],
        ];

        foreach ($destinations as $data) {
            $tags = $data['tags'];
            unset($data['tags']);
            $destination = Destinations::updateOrCreate(
                ['title' => $data['title']],
                array_merge($data, ['published_at' => now()])
            );
            $destination->tags()->sync($tags);
        }
    }
}
