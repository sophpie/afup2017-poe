<?php
class Customer
{
    /**
     * @var string
     */
    protected $roomSize;
    /**
     * @var int
     */
    protected $minFloor;

    /**
     * @var Hotel
     */
    protected $hotel;

    /**
     * @var int
     */
    protected $roomNumber;

    /**
     * Customer constructor.
     * @param string $roomSize
     * @param int $minFloor
     */
    public function __construct(string $roomSize,int $minFloor)
    {
        $this->roomSize = $roomSize;
        $this->minFloor = $minFloor;
    }

    /**
     * @param Hotel $hotel
     * @return $this
     */
    public function orderRoomTo(Hotel $hotel)
    {
        $this->hotel = $hotel;
        $hotel->provideRoomTo($this,$this->roomSize,$this->minFloor);
        return $this;
    }

    /**
     * @param int $number
     * @return $this
     */
    public function setRoomNumber(int $number)
    {
        $this->roomNumber = $number;
        return $this;
    }
}

/**
 * Class Hotel
 */
class Hotel
{
    /**
     * @var array
     */
    protected $rooms = [];

    /**
     * @var Customer
     */
    protected $currentCustomer;

    /**
     * Hotel constructor.
     */
    public function __construct()
    {
        $sizes = [Room::SIZE_S,Room::SIZE_M,Room::SIZE_L];
        for ($i = 1; $i < 400; $i++)
        {

            $floor = ceil($i/100);
            $size = $sizes[rand(0,2)];
            $this->rooms[$i] =new Room($this,$size,$i,$floor);
        }
    }

    /**
     * @param Customer $customer
     * @param string $roomSize
     * @param int $minFloor
     * @return $this
     */
    public function provideRoomTo(Customer $customer,string $roomSize, int $minFloor)
    {
        $this->currentCustomer = $customer;
        foreach ($this->rooms as $room)
        {
            $room->bookIfSuitable($roomSize,$minFloor);
        }
        return $this;
    }

    /**
     * @param int $number
     * @return $this
     */
    public function bookRoom(int $number)
    {
        $room = $this->rooms[$number];
        $room->book();
        $this->currentCustomer->setRoomNumber($number);
        return $this;
    }

}

/**
 * Class Room
 */
class Room
{
    const SIZE_S = 's';
    const SIZE_M = 'm';
    const SIZE_L = 'l';

    /**
     * @var int
     */
    protected $number;
    /**
     * @var string
     */
    protected $size;
    /**
     * @var int
     */
    protected $floor;
    /**
     * @var bool
     */
    protected $available = true;

    /**
     * @var Hotel
     */
    protected $hotel;

    /**
     * Room constructor.
     * @param Hotel $hotel
     * @param string $size
     * @param int $number
     * @param int $floor
     */
    public function __construct(Hotel $hotel,string $size, int $number, int $floor)
    {
        $this->hotel = $hotel;
        $this->number = $number;
        $this->size = $size;
        $this->floor = $floor;
    }

    /**
     * @param string $roomSize
     * @param int $minFloor
     * @return $this
     */
    public function bookIfSuitable(string $roomSize,int $minFloor)
    {
        if ( ! $this->available) return $this;
        if ($this->floor < $minFloor) return $this;
        if ($this->size != $roomSize) return $this;
        $this->hotel->bookRoom($this->number);
        return $this;
    }

    /**
     * @return $this
     */
    public function book()
    {
        $this->available = false;
        return $this;
    }
}

$sophie = new Customer(Room::SIZE_M,3);
$ibis = new Hotel();
$sophie->orderRoomTo($ibis);
