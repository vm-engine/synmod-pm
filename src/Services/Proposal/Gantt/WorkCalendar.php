<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal\Gantt;

use Carbon\CarbonImmutable;
use DateTimeInterface;
use RuntimeException;

/**
 * Working-day arithmetic: Monday–Friday minus non-working dates.
 */
final class WorkCalendar
{
    /** Guard against a calendar with no working day in sight. */
    private const MAX_SCAN_DAYS = 366;

    /** @var array<string, true> */
    private array $offDays = [];

    /**
     * @param  iterable<string|DateTimeInterface>  $nonWorkingDates
     */
    public function __construct(iterable $nonWorkingDates = [])
    {
        foreach ($nonWorkingDates as $date) {
            $this->offDays[CarbonImmutable::parse($date)->toDateString()] = true;
        }
    }

    /**
     * Global holidays plus per-proposal off days, minus the holidays the team works through.
     *
     * @param  list<string>  $globalHolidays
     * @param  list<string>  $extraOffDays
     * @param  list<string>  $workedHolidays
     */
    public static function fromSets(array $globalHolidays, array $extraOffDays = [], array $workedHolidays = []): self
    {
        $normalize = fn (string $date): string => CarbonImmutable::parse($date)->toDateString();
        $worked = array_map($normalize, $workedHolidays);
        $dates = array_map($normalize, [...$globalHolidays, ...$extraOffDays]);

        return new self(array_values(array_diff($dates, $worked)));
    }

    public function isHoliday(CarbonImmutable $date): bool
    {
        return isset($this->offDays[$date->toDateString()]);
    }

    public function isWorkingDay(CarbonImmutable $date): bool
    {
        return ! $date->isWeekend() && ! $this->isHoliday($date);
    }

    /** The given date when it is a working day, otherwise the next one. */
    public function nextWorkingDay(CarbonImmutable $date): CarbonImmutable
    {
        $date = $date->startOfDay();
        for ($i = 0; $i < self::MAX_SCAN_DAYS; $i++) {
            if ($this->isWorkingDay($date)) {
                return $date;
            }
            $date = $date->addDay();
        }

        throw new RuntimeException('No working day within a year of '.$date->toDateString());
    }

    /** Move forward $days working days, starting from the first working day on/after $from. */
    public function addWorkingDays(CarbonImmutable $from, int $days): CarbonImmutable
    {
        $date = $this->nextWorkingDay($from);
        for (; $days > 0; $days--) {
            $date = $this->nextWorkingDay($date->addDay());
        }

        return $date;
    }

    /** Inclusive count of working days between two dates. */
    public function workingDaysBetween(CarbonImmutable $from, CarbonImmutable $to): int
    {
        $count = 0;
        for ($date = $from->startOfDay(); $date->lte($to); $date = $date->addDay()) {
            if ($this->isWorkingDay($date)) {
                $count++;
            }
        }

        return $count;
    }

    /** Inclusive count of weekday holidays between two dates. */
    public function holidaysBetween(CarbonImmutable $from, CarbonImmutable $to): int
    {
        $count = 0;
        for ($date = $from->startOfDay(); $date->lte($to); $date = $date->addDay()) {
            if (! $date->isWeekend() && $this->isHoliday($date)) {
                $count++;
            }
        }

        return $count;
    }
}
