<?php
class RoomOccupancy
{
    private string $periode;
    private float $bed_occupancy_rate;

    public function getPeriode(): string
    {
        return $this->periode;
    }

    public function setPeriode(string $periode): void
    {
        $this->periode = $periode;
    }

    public function getBedOccupancyRate(): float
    {
        return $this->bed_occupancy_rate;
    }

    public function setBedOccupancyRate(float $bed_occupancy_rate): void
    {
        $this->bed_occupancy_rate = $bed_occupancy_rate;
    }
}
