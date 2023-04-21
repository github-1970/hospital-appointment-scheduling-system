<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'specialty',
        'working_hours_start',
        'working_hours_end',
        'max_appointments_per_hour',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function getAvailableTime(String $appointment_time)
    {
        $appointment_time = new \DateTime($appointment_time);
        $available_time = clone $appointment_time;
        $available_time->add(new \DateInterval('PT1H')); // Add one hour to the appointment time

        // can doctor appointment more than bookedAppointments?
        if ($this->bookedAppointments($available_time) >= $this->max_appointments_per_hour) {
            return $this->getAvailableTime($available_time->format('Y-m-d H:i:s'));
        }

        if ($message = $this->checkOutsideDoctorWorkingHours($available_time->format('Y-m-d H:i:s'))) {
            return $message;
        }

        return __('Doctor is fully booked at this time. Next available time: ') . $available_time->format('H:i:s');
    }

    // Check if the appointment time is outside of the doctor's working hours
    public function checkOutsideDoctorWorkingHours(String $appointment_time)
    {
        $appointment_time = date('H:i:s', strtotime($appointment_time));
        if ($appointment_time < $this->working_hours_start || $appointment_time > $this->working_hours_end) {
            return __("Doctor is not available at this time.");
        }
        return false;
    }

    public function bookedAppointments($available_time)
    {
        $appointmentTime = Carbon::parse($available_time);

        // booked appointments, in this time
        return $this->appointments()
            ->where('appointment_time', '>=', $appointmentTime->startOfHour()->format('Y-m-d H:i:s'))
            ->where('appointment_time', '<', $appointmentTime->endOfHour()->format('Y-m-d H:i:s'))
            ->count();
    }
}
