<?php

namespace Database\Seeders;

use App\Destinations;
use App\Hotel;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    private array $countryToDestination = [];

    public function run(): void
    {
        $this->buildDestinationLookup();

        $hotels = json_decode(file_get_contents(__DIR__ . '/hotels_full.json'), true);

        $chunks = array_chunk($hotels, 100);
        $total = 0;

        foreach ($chunks as $chunk) {
            $records = [];
            foreach ($chunk as $h) {
                $record = $this->buildRecord($h);
                if ($record) {
                    $records[] = $record;
                }
            }

            if (!empty($records)) {
                Hotel::insert($records);
                $total += count($records);
            }
        }

        $this->command->info("Seeded {$total} hotels from hotels_full.json.");
    }

    private function buildDestinationLookup(): void
    {
        $destinations = Destinations::all();

        $countryMap = [
            'France'                       => ['France'],
            'Italy'                        => ['Italy', 'Italian Riviera'],
            'United Kingdom'               => ['United Kingdom', 'UK', 'Great Britain', 'England', 'Scotland', 'Wales'],
            'Spain'                        => ['Spain'],
            'Netherlands'                  => ['Netherlands', 'Holland'],
            'Greece'                       => ['Greece'],
            'Tunisia'                      => ['Tunisia'],
            'Morocco'                      => ['Morocco'],
            'South Africa'                 => ['South Africa'],
            'Tanzania'                     => ['Tanzania'],
            'Egypt'                        => ['Egypt'],
            'Japan'                        => ['Japan'],
            'Indonesia'                    => ['Indonesia'],
            'Thailand'                     => ['Thailand'],
            'UAE'                          => ['UAE', 'United Arab Emirates'],
            'Maldives'                     => ['Maldives'],
            'China'                        => ['China'],
            'United States'                => ['United States', 'USA', 'U.S.A.', 'America'],
            'Peru'                         => ['Peru'],
            'Mexico'                       => ['Mexico'],
            'Brazil'                       => ['Brazil'],
            'Australia'                    => ['Australia'],
            'New Zealand'                  => ['New Zealand'],
            'Fiji'                         => ['Fiji'],
            'Czech Republic'               => ['Czech Republic', 'Czechia'],
            'Portugal'                     => ['Portugal'],
            'Austria'                      => ['Austria'],
            'Hungary'                      => ['Hungary'],
            'Iceland'                      => ['Iceland'],
            'Switzerland'                  => ['Switzerland'],
            'South Korea'                  => ['South Korea', 'Korea'],
            'Vietnam'                      => ['Vietnam'],
            'Hong Kong'                    => ['Hong Kong'],
            'India'                        => ['India'],
            'Singapore'                    => ['Singapore'],
            'Nepal'                        => ['Nepal'],
            'Zimbabwe & Zambia'            => ['Zimbabwe', 'Zambia', 'Victoria Falls'],
            'Mauritius'                    => ['Mauritius'],
            'Ghana'                        => ['Ghana'],
            'Madagascar'                   => ['Madagascar'],
            'Canada'                       => ['Canada'],
            'Cuba'                         => ['Cuba'],
            'Argentina & Chile'            => ['Argentina', 'Chile'],
            'Colombia'                     => ['Colombia'],
            'Rwanda'                       => ['Rwanda'],
            'Senegal'                      => ['Senegal'],
            'Namibia'                      => ['Namibia'],
            'Seychelles'                   => ['Seychelles'],
            'Sri Lanka'                    => ['Sri Lanka'],
            'Philippines'                  => ['Philippines'],
            'Uzbekistan'                   => ['Uzbekistan'],
            'Malaysia'                     => ['Malaysia'],
            'Bhutan'                       => ['Bhutan'],
            'Croatia'                      => ['Croatia'],
            'Norway'                       => ['Norway'],
            'Ireland'                      => ['Ireland'],
            'Ecuador'                      => ['Ecuador'],
            'Bolivia'                      => ['Bolivia'],
            'Dominican Republic'           => ['Dominican Republic'],
            'Barbados'                     => ['Barbados'],
            'Papua New Guinea'             => ['Papua New Guinea'],
            'Greenland'                    => ['Greenland'],
            'Poland'                       => ['Poland'],
            'Turkey'                       => ['Turkey', 'Türkiye'],
            'Jordan'                       => ['Jordan'],
            'Oman'                         => ['Oman'],
            'Jamaica'                      => ['Jamaica'],
            'Costa Rica'                   => ['Costa Rica'],
            'Denmark'                      => ['Denmark'],
            'Sweden'                       => ['Sweden'],
            'Qatar'                        => ['Qatar'],
            'Lebanon'                      => ['Lebanon'],
            'Comoros'                      => ['Comoros'],
            'Germany'                      => ['Germany'],
            'Cambodia'                     => ['Cambodia'],
            'Taiwan'                       => ['Taiwan'],
            'Mongolia'                     => ['Mongolia'],
            'Myanmar'                      => ['Myanmar', 'Burma'],
            'Ethiopia'                     => ['Ethiopia'],
            'Botswana'                     => ['Botswana'],
            'Finland'                      => ['Finland'],
            'French Polynesia'             => ['French Polynesia', 'Tahiti', 'Bora Bora'],
            'Albania'                      => ['Albania'],
            'Russia'                       => ['Russia'],
            'Ukraine'                      => ['Ukraine'],
            'Romania'                      => ['Romania'],
            'Bulgaria'                     => ['Bulgaria'],
            'Serbia'                       => ['Serbia'],
            'Kenya'                        => ['Kenya'],
            'Nigeria'                      => ['Nigeria'],
            'Morocco'                      => ['Morocco'],
            'Algeria'                      => ['Algeria'],
            'Panama'                       => ['Panama'],
            'Guatemala'                    => ['Guatemala'],
            'Honduras'                     => ['Honduras'],
            'Nicaragua'                    => ['Nicaragua'],
            'El Salvador'                  => ['El Salvador'],
            'Uruguay'                      => ['Uruguay'],
            'Paraguay'                     => ['Paraguay'],
            'Venezuela'                    => ['Venezuela'],
            'Puerto Rico'                  => ['Puerto Rico'],
            'Bahamas'                      => ['Bahamas'],
            'Trinidad and Tobago'          => ['Trinidad and Tobago', 'Trinidad'],
            'Belgium'                      => ['Belgium'],
            'Luxembourg'                   => ['Luxembourg'],
            'Monaco'                       => ['Monaco'],
            'Malta'                        => ['Malta'],
            'Cyprus'                       => ['Cyprus'],
            'Slovakia'                     => ['Slovakia'],
            'Slovenia'                     => ['Slovenia'],
            'Lithuania'                    => ['Lithuania'],
            'Latvia'                       => ['Latvia'],
            'Estonia'                      => ['Estonia'],
            'Bosnia'                       => ['Bosnia and Herzegovina', 'Bosnia'],
            'Montenegro'                   => ['Montenegro'],
            'North Macedonia'              => ['North Macedonia', 'Macedonia'],
            'Armenia'                      => ['Armenia'],
            'Georgia'                      => ['Georgia'],
            'Azerbaijan'                   => ['Azerbaijan'],
            'Kazakhstan'                   => ['Kazakhstan'],
            'Israel'                       => ['Israel'],
            'Saudi Arabia'                 => ['Saudi Arabia'],
            'Kuwait'                       => ['Kuwait'],
            'Bahrain'                      => ['Bahrain'],
            'Iran'                         => ['Iran'],
            'Pakistan'                     => ['Pakistan'],
            'Bangladesh'                   => ['Bangladesh'],
            'Laos'                         => ['Laos'],
            'Brunei'                       => ['Brunei'],
            'Timor-Leste'                  => ['Timor-Leste', 'East Timor'],
            'South Sudan'                  => ['South Sudan'],
            'Sudan'                        => ['Sudan'],
            'Somalia'                      => ['Somalia'],
            'Djibouti'                     => ['Djibouti'],
            'Eritrea'                      => ['Eritrea'],
            'Angola'                       => ['Angola'],
            'Mozambique'                   => ['Mozambique'],
            'Zambia'                       => ['Zambia'],
            'Malawi'                       => ['Malawi'],
            'Lesotho'                      => ['Lesotho'],
            'Eswatini'                     => ['Eswatini', 'Swaziland'],
            'Ivory Coast'                  => ["Côte d'Ivoire", 'Ivory Coast'],
            'Cameroon'                     => ['Cameroon'],
            'DR Congo'                     => ['Democratic Republic of the Congo', 'DR Congo', 'Congo'],
            'Republic of the Congo'        => ['Republic of the Congo', 'Congo-Brazzaville'],
            'Gabon'                        => ['Gabon'],
            'Equatorial Guinea'            => ['Equatorial Guinea'],
            'Togo'                         => ['Togo'],
            'Benin'                        => ['Benin'],
            'Burkina Faso'                 => ['Burkina Faso'],
            'Mali'                         => ['Mali'],
            'Niger'                        => ['Niger'],
            'Chad'                         => ['Chad'],
            'Central African Republic'     => ['Central African Republic'],
            'Liberia'                      => ['Liberia'],
            'Sierra Leone'                 => ['Sierra Leone'],
            'Guinea'                       => ['Guinea'],
            'Guinea-Bissau'                => ['Guinea-Bissau'],
            'Gambia'                       => ['Gambia'],
            'Mauritania'                   => ['Mauritania'],
            'Antarctica'                   => ['Antarctica'],
        ];

        foreach ($destinations as $dest) {
            $title = $dest->title;

            foreach ($countryMap as $mappedCountry => $aliases) {
                foreach ($aliases as $alias) {
                    if (str_contains($title, $alias) || $alias === $title) {
                        $this->countryToDestination[strtolower($mappedCountry)] = $dest->id;
                        break 2;
                    }
                }
            }

            if (str_contains($title, ',')) {
                $parts = explode(',', $title);
                $possibleCountry = trim(end($parts));
                $key = strtolower($possibleCountry);
                if (!isset($this->countryToDestination[$key])) {
                    $this->countryToDestination[$key] = $dest->id;
                }
            } else {
                $key = strtolower($title);
                if (!isset($this->countryToDestination[$key])) {
                    $this->countryToDestination[$key] = $dest->id;
                }
            }
        }
    }

    private function buildRecord(array $h): ?array
    {
        $name = trim($h['hotelLabel'] ?? '');
        if (empty($name)) {
            return null;
        }

        $countryLabel = trim($h['countryLabel'] ?? $h['countryLabel'] ?? '');
        $destinationId = $this->resolveDestination($countryLabel);

        $lat = null;
        $lng = null;
        if (!empty($h['coord'])) {
            $parsed = $this->parseCoord($h['coord']);
            if ($parsed) {
                [$lng, $lat] = $parsed;
            }
        }

        $images = [];
        if (!empty($h['image'])) {
            $images[] = $h['image'];
        }

        return [
            'name'               => $name,
            'city'               => $destinationId ? $this->getDestinationCity($destinationId) : ($countryLabel ?: 'Unknown'),
            'country'            => $countryLabel ?: 'Unknown',
            'latitude'           => $lat,
            'longitude'          => $lng,
            'stars'              => rand(2, 5),
            'price_per_night_usd'=> round(rand(5000, 50000) / 100, 2),
            'images'             => json_encode($images),
            'amenities'          => json_encode($this->randomAmenities()),
            'is_available'       => true,
            'destination_id'     => $destinationId,
        ];
    }

    private function parseCoord(string $coord): ?array
    {
        if (preg_match('/Point\s*\(\s*([\d\.\-]+)\s+([\d\.\-]+)\s*\)/i', $coord, $m)) {
            return [(float) $m[1], (float) $m[2]];
        }
        return null;
    }

    private function resolveDestination(string $countryLabel): ?int
    {
        if (empty($countryLabel)) {
            return null;
        }

        $key = strtolower(trim($countryLabel));
        return $this->countryToDestination[$key] ?? null;
    }

    private function getDestinationCity(int $destinationId): string
    {
        static $cityCache = [];
        if (!isset($cityCache[$destinationId])) {
            $dest = Destinations::find($destinationId);
            if ($dest && str_contains($dest->title, ',')) {
                $cityCache[$destinationId] = trim(explode(',', $dest->title)[0]);
            } else {
                $cityCache[$destinationId] = $dest->title ?? 'Unknown';
            }
        }
        return $cityCache[$destinationId];
    }

    private function randomAmenities(): array
    {
        $all = ['Free Wi-Fi', 'Swimming Pool', 'Spa', 'Breakfast Included', 'Fitness Center', 'Restaurant', 'Room Service', 'Bar', 'Parking', 'Airport Shuttle', 'Concierge', 'Laundry', 'Pet Friendly', 'Air Conditioning', 'Heating', 'Terrace', 'Garden', 'Business Center'];
        $count = rand(3, 6);
        $keys = array_rand($all, min($count, count($all)));
        if (!is_array($keys)) {
            $keys = [$keys];
        }
        return array_map(fn($k) => $all[$k], $keys);
    }
}
