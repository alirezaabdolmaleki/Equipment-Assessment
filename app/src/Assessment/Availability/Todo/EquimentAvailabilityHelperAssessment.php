<?php

namespace Assessment\Availability\Todo;

use Database\Database;
use Assessment\Availability\EquipmentAvailabilityHelper;
use \Repository\EquipmentRepository;
use DateTime;

class EquipmentAvailabilityHelperAssessment extends EquipmentAvailabilityHelper
{

    /**
     * This function checks if a given quantity is available in the past time frame
     * @param int      $equipment_id id of the equipment item
     * @param int      $quantity How much should be available
     * @param DateTime $start Start of time window
     * @param DateTime $end End of time window
     * @return bool True if available, false otherwise
     */
    public function isAvailable(int $equipment_id, int $quantity, DateTime $start, DateTime $end): bool
    {
        // Get the stock of the equipment
        $stock = $this->equipmentRepository->getStock($equipment_id);
        // Get the total quantity planned in the specified period
        $total_planned = $this->equipmentRepository->getTotalPlanned($equipment_id, $start, $end);
        // Check if the available quantity is greater than or equal to the requested quantity
        return ($stock - $total_planned) >= $quantity;
    }


    /**
     * Calculate all items that are short in the given period
     * @param DateTime $start Start of time window
     * @param DateTime $end End of time window
     * @return array Key/value array with as indices the equipment id's and as values the shortages
     */
    public function getShortages(DateTime $start, DateTime $end): array
    {
        // Call the findShortages method from the EquipmentRepository to get the shortages within the specified timeframe
        $results = $this->equipmentRepository->findShortages($start, $end);

        // Initialize an empty array to store the shortages
        $shortages = array();

        // Iterate through the results returned by the getShortages method
        foreach ($results as $result) {
            $shortages[$result['id']] = $result['shortage'];
        }

        return $shortages;
    }
}
