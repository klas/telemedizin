<?php

namespace Tests\Unit;

use App\Mail\AppointmentConfirmation;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_constructor_sets_appointment()
    {
        $appointment = new Appointment();
        $mail = new AppointmentConfirmation($appointment);
        $this->assertEquals($appointment, $mail->appointment);
    }

    public function test_build_returns_mailable()
    {
        $appointment = new Appointment();
        $mail = new AppointmentConfirmation($appointment);
        $mailable = $mail->build();
        $this->assertInstanceOf(\Illuminate\Mail\Mailable::class, $mailable);
    }

    public function test_build_sets_subject()
    {
        $appointment = new Appointment();
        $mail = new AppointmentConfirmation($appointment);
        $mailable = $mail->build();
        $this->assertEquals('Bestätigung Ihres Telemedizin-Termins', $mailable->subject);
    }

    public function test_build_sets_view()
    {
        $appointment = new Appointment();
        $mail = new AppointmentConfirmation($appointment);
        $mailable = $mail->build();
        $this->assertEquals('emails.appointment-confirmation', $mailable->view);
    }
}
