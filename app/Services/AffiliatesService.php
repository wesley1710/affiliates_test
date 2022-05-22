<?php


namespace App\Services;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Class AffiliatesService
 * @package App\Services
 */
class AffiliatesService
{
    /**
     * @var array
     */
    private $affiliates = [];

    /**
     * @var \stdClass
     */
    private $referencePoint;

    /**
     * AffiliatesService constructor.
     * @param string $path
     */
    public function __construct($path = 'affiliates.txt')
    {
        try {
            $this->affiliates = $this->readFile($path);

            $this->referencePoint = new \stdClass();
            $this->referencePoint->latitude = '';
            $this->referencePoint->longitude = '';

        } catch (\Exception $e) {
            Log::error("AffiliatesService - Error on contruct: " . $e->getMessage());
        }
    }

    /**
     * @param $latitude
     * @param $longitude
     */
    public function setReferencePoint($latitude, $longitude)
    {
        $this->referencePoint->latitude = $latitude;
        $this->referencePoint->longitude = $longitude;
    }

    /**
     * @param $kmLimit
     * @return array
     */
    public function filterByDistance($kmLimit = 0)
    {
        try {
            $response = [];
            foreach ($this->affiliates as $affiliate) {
                $affiliate->distance = $this->calculateDistance($affiliate, $this->referencePoint);
                if ($affiliate->distance <= $kmLimit || $kmLimit <= 0) {
                    $response[] = $affiliate;
                }
            }

            usort($response, function ($a, $b) {
                return $a->affiliate_id > $b->affiliate_id;
            });

            return $response;
        } catch (\Exception $e) {
            Log::error("AffiliatesService - Error getting affiliates filtered: " . $e->getMessage());
            return [];
        }
    }

    /**
     * @param $point1
     * @param $point2
     * @return float|int
     */
    private function calculateDistance($point1, $point2)
    {
        if (!isset($point1->latitude) || !isset($point2->latitude) || !isset($point1->longitude) || !isset($point2->longitude)) {
            return 0;
        }

        $point2->latitude = floatval($point2->latitude);
        $point1->longitude = floatval($point1->longitude);
        $point1->latitude = floatval($point1->latitude);
        $point2->longitude = floatval($point2->longitude);

        $R = 6371; // Radius of the earth in km
        $dLat = $this->deg2rad($point2->latitude - $point1->latitude);
        $dLon = $this->deg2rad($point2->longitude - $point1->longitude);

        $a =
            sin($dLat / 2) * sin($dLat / 2) +
            cos($this->deg2rad($point1->latitude)) * cos($this->deg2rad($point2->latitude)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $d = $R * $c; // Distance in km
        return $d;
    }

    /**
     * @param $deg
     * @return float|int
     */
    private function deg2rad($deg)
    {
        return $deg * (pi() / 180);
    }

    /**
     * @param $file
     * @return array
     */
    private function readFile($file)
    {
        try {
            $fileContent = Storage::disk('local')->get($file);
            $arrAffiliates = explode(PHP_EOL, $fileContent);
            foreach ($arrAffiliates as $key => $affiliate) {
                $arrAffiliates[$key] = json_decode($affiliate);
            }

            return $arrAffiliates;

        } catch (\Exception $e) {
            Log::error("Error getting file contents: " . $e->getMessage());

            return [];
        }
    }
}
