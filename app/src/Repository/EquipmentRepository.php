<?php

namespace Repository;

use DateTime;
use PDO;

namespace Repository;

use DateTime;
use PDO;

class EquipmentRepository
{
    private $db;

    /**
     * Constructor to initialize the EquipmentRepository with a database connection.
     *
     * @param PDO $db The database connection to use for queries.
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Get the stock quantity of a specific equipment item by its ID.
     *
     * @param int $equipment_id The ID of the equipment item.
     * @return int The stock quantity of the equipment item.
     */
    public function getStock(int $equipment_id): int
    {
        // Query to select the stock from the equipment table where the equipment ID matches
        $query = "SELECT stock FROM equipment WHERE id = :equipment_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':equipment_id' => $equipment_id]);

        // Fetch the result as an associative array
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the stock value, or 0 if not found
        return $result['stock'] ?? 0;
    }

    /**
     * Get the total quantity of equipment planned in a given timeframe.
     *
     * @param int      $equipment_id The ID of the equipment item.
     * @param DateTime $start        The start datetime of the timeframe.
     * @param DateTime $end          The end datetime of the timeframe.
     * @return int The total planned quantity of the equipment item in the given timeframe.
     */
    public function getTotalPlanned(int $equipment_id, DateTime $start, DateTime $end): int
    {
        // Query to sum the quantities of equipment planned in the specified timeframe
        $query = "
            SELECT SUM(quantity) as total_quantity
            FROM planning
            WHERE equipment = :equipment_id AND (start < :end AND end > :start)
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':equipment_id' => $equipment_id,
            ':start' => $start->format('Y-m-d H:i:s'),
            ':end' => $end->format('Y-m-d H:i:s')
        ]);

        // Fetch the result as an associative array
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the total planned quantity, or 0 if not found
        return $result['total_quantity'] ?? 0;
    }



    /**
     * Find shortages of equipment within a specified timeframe.
     *
     * This method returns the equipment items that have shortages within the given timeframe.
     * An item is considered to have a shortage if the total planned quantity exceeds the stock.
     *
     * @param DateTime $start The start of the timeframe.
     * @param DateTime $end The end of the timeframe.
     * @return array An associative array where the keys are equipment IDs and the values are the shortages.
     */
    public function findShortages(DateTime $start, DateTime $end): array
    {
        print_r($start) ;
        print_r($end);
        // Query to get the total planned quantities and stock of equipment in the specified timeframe
        $query = "SELECT equipment.id as id, 
        (stock - getMaxPlanned(equipment.id,:start,:end))
        
         AS shortage FROM equipment HAVING shortage < 0
    
        
      ";
                    
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':start' => $start->format('Y-m-d H:i:s'),
            ':end' => $end->format('Y-m-d H:i:s')
        ]);

        // Fetch all results as an array of associative arrays
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
