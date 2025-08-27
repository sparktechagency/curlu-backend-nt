<?php

namespace App\Services;

// use App\Models\Salon;

use App\Models\Category;
use App\Models\Salon;
use App\Models\SalonService;
use App\Models\User;
use App\Services\DistanceService;
use Illuminate\Http\Request;

class UserService
{
    public $distanceService;
    public $servicessss;

    public function __construct(DistanceService $distanceService)
    {
        $this->distanceService = $distanceService;
    }

    public function getNearbyProfessionals(Request $request, $userLatitude, $userLongitude, $radius = 10)
    {

        $professionals = User::with('salon.salon_services.category')->where('role_type', 'PROFESSIONAL')
            ->select('id', 'name', 'last_name', 'address', 'latitude', 'longitude')
            ->paginate($request->per_page??10);

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
    public function getNearbyProfessionalsByCategory($userLatitude, $userLongitude, $radius = 20, $category, $searchTerm = null, $perPage = 10)
    {

        $nearByProf = $this->getNearbyProfessionals($userLatitude, $userLongitude, $radius);

        $nearsetServiceByCategory = collect($nearByProf)->map(function ($professional) use ($category, $searchTerm, $userLatitude, $userLongitude, $perPage) {

            $salonServices = $professional->salon->salon_services()
                ->where('category_id', $category)
                ->where('service_name', 'like', '%' . $searchTerm . '%')
                ->paginate($request->per_page??10);


            $salonServices->transform(function ($service) {
                return [
                    'id' => $service->id,
                    'category_id' => $service->category_id,
                    'name' => $service->service_name,
                    'price' => $service->price,
                    'discount_price' => $service->discount_price,
                    'service_image' => $service->service_image,
                    'service_description' => $service->service_description,
                    'category_name' => $service->category->category_name,
                    'category_image' => $service->category->category_image,
                ];
            });
            return [
                'user_id' => $professional->id,
                'salon_id' => $professional->salon->id,
                'name' => $professional->name,
                'last_name' => $professional->last_name,
                'address' => $professional->address,
                'latitude' => $professional->latitude,
                'longitude' => $professional->longitude,
                'distance' => $this->distanceService->getDistance(
                    $userLatitude,
                    $userLongitude,
                    $professional->latitude,
                    $professional->longitude
                ),
                'salon_services' => $salonServices,

            ];
        });

        // check if the services are empty
        // $nearsetServiceByCategory = $nearsetServiceByCategory->transform(function ($professional) {
        //     if ($professional['salon_services']->isEmpty()) {
        //         $professional = 'No services found';
        //     }
        //     return $professional;
        // })->first();
        return $nearsetServiceByCategory;
    }
}
