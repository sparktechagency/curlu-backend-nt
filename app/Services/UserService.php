<?php
namespace App\Services;
use App\Models\User;
use App\Services\DistanceService;

class UserService
{
    public $distanceService;

    public function __construct(DistanceService $distanceService)
    {
        $this->distanceService = $distanceService;
    }

    public function getNearbyProfessionals($userLatitude, $userLongitude, $radius = 10)
    {
        
        $professionals = User::where('role_type', 'PROFESSIONAL')
            ->select('id', 'name', 'last_name', 'address', 'latitude', 'longitude')
            ->get();

            

        $nearbyProfessionals = [];
        


        foreach ($professionals as $professional) {

            
            $distance = $this->distanceService->getDistance(
                $userLatitude,
                $userLongitude,
                $professional->latitude,
                $professional->longitude
            );
            // dump($distance);
            if ($distance <= $radius) {
                $nearbyProfessionals[] = $professional;
                
            }
        }
        // dd($nearbyProfessionals);
        return $nearbyProfessionals;
    }
}