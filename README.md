# Equipment Availability Assessment

This project assesses the availability of equipment for a rental company by answering two key questions:
1. Is the equipment the customer wants to hire available in a certain timeframe?
2. Where in the planning are equipment shortages (more planned than the stock)?

## Project Structure

The project is structured to use the Repository design pattern for efficient database interactions. Here is an overview of the key classes and their responsibilities:

### Classes

1. **EquipmentAvailabilityHelperAssessment**
   - Extends the `EquipmentAvailabilityHelper` class and implements two methods:
     - `isAvailable($equipment_id, int $quantity, DateTime $start, DateTime $end) : bool`: Checks if the requested quantity of equipment is available in the specified timeframe.
     - `getShortages(DateTime $start, DateTime $end)`: Finds all shortages in the given timeframe and returns an array with equipment IDs and their shortages.

2. **EquipmentRepository**
   - Handles database interactions related to equipment and planning:
     - `getStock(int $equipment_id): int`: Retrieves the stock quantity of a specific equipment item by its ID.
     - `getTotalPlanned(int $equipment_id, DateTime $start, DateTime $end): int`: Retrieves the total quantity of equipment planned in the specified timeframe.
     - `getEquipmentPlannedInPeriod(DateTime $start, DateTime $end): array`: Retrieves the planned quantities and stock of all equipment items in the given timeframe.

3. **Database**
   - Uses the Singleton design pattern to ensure a single instance of the database connection throughout the application.

## Design Patterns

- **Repository Pattern**: Used to separate the logic that retrieves data from the database from the business logic. This helps in managing data access logic in a cleaner and more modular way.
- **Singleton Pattern**: Ensures that a class has only one instance and provides a global point of access to it. This pattern is used in the `Database` class to manage the database connection.






# Assessment repo

To get the full instructions the first step is to get this docker setup running. If you want to go to the instructions
directly check the file under `app/instructions`


## Get it running

These instructions assume docker is already running.

```
# Give execution permissions to composer.sh (windows and linux)
chmod 755 composer.sh

# install the php autoloader
./composer.sh install  

# run the environment
docker-compose up

```

After running these commmands, these urls are available:

- http://localhost:7000/ Portal page with the instructions
- http://localhost:7001/ phpMyAdmin

## Remarks

- If anything is unclear, just ask!
