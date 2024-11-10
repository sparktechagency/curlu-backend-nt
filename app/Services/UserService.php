<?php
namespace App\Services;

// use App\Models\Salon;

use App\Models\Category;
use App\Models\Salon;
use App\Models\SalonService;
use App\Models\User;
use App\Services\DistanceService;
use Termwind\Components\Dd;

class UserService
{
    public $distanceService;
    public $servicessss;

    public function __construct(DistanceService $distanceService)
    {
        $this->distanceService = $distanceService;
    }

    public function getNearbyProfessionals($userLatitude, $userLongitude, $radius = 10)
    {
        
        $professionals = User::with('salon')->where('role_type', 'PROFESSIONAL')
            ->select('id', 'name', 'last_name', 'address', 'latitude', 'longitude')
            ->paginate(10);

            

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

    //getNearbyProfessionalsByCategory make this function to get nearby professionals by category
    public function getNearbyProfessionalsByCategory($userLatitude, $userLongitude, $radius = 20, $category)
    {
        
        $nearByProf = $this->getNearbyProfessionals($userLatitude, $userLongitude, $radius);
        // dd($nearByProf);
        $nearServicesByCategory = collect($nearByProf)->map(function($item) use ($userLatitude, $userLongitude, $category) {
        $services = SalonService::with(['salon','category'])
                                ->where('salon_id', $item->salon->id)
                                ->where('category_id', $category)
                                ->paginate(10);

        $services->getCollection()->transform(function($service) use($userLatitude, $userLongitude, $item) {
            return [
                'prof_id' => $item->id,
                'prof_name' => $item->name,
                'last_name' => $item->last_name,
                'address' => $item->address,
                'id' => $service->id,
                'name' => $service->service_name,
                'price' => $service->price,
                'discount_price' => $service->discount_price,
                'salon_id' => $service->salon->id,
                'distance' => $this->distanceService->getDistance($userLatitude, $userLongitude, $item->latitude, $item->longitude),
                'category' => $service->category->category_name,
                'category_image' => $service->category->category_image,
                'category_id' => $service->category->id,
            ];
        });
        return [
            'services' => $services,
        ] ;
    });
    
         return $nearServicesByCategory;
    }
}