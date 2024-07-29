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
		// Create a new instance of EquipmentRepository with the database connection
		$equipmentRepository = new EquipmentRepository($this->getDatabaseConnection());
        // Get the stock of the equipment
        $stock = $equipmentRepository->getStock($equipment_id);
        // Get the total quantity planned in the specified period
        $total_planned = $equipmentRepository->getTotalPlanned($equipment_id, $start, $end);
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
		// Create a new instance of EquipmentRepository with the database connection
		$equipmentRepository = new EquipmentRepository($this->getDatabaseConnection());
        // Get all equipment planned in the specified period
        $results = $equipmentRepository->getEquipmentPlannedInPeriod($start, $end);
        $shortages = [];

        // Iterate through each result to calculate the shortage
        foreach ($results as $result) {
            // Calculate the shortage for each equipment
            $shortage = $result['total_quantity'] - $result['stock'];
            // If there is a shortage (i.e., shortage is greater than 0), add it to the shortages array
            if ($shortage > 0) {
                $shortages[$result['equipment']] = -$shortage;
            }
        }

        // Return the array of shortages
        return $shortages;
    }
}
