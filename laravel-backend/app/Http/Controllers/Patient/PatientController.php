<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PatientController extends Controller
{
    public function index()
    {
        return Inertia::render('Patient/Dashboard');
    }
    public function messages()
    {
        return Inertia::render('Patient/Messages');
    }
    public function appointments()
    {
        $doctors = DB::table('doctors')
            ->join('users', 'doctors.doc_id', '=', 'users.id')
            ->select('doctors.*', 'users.profile_photo_path', 'users.email', 'users.phone')
            ->get();

        // only upcomming appointments
        $appointments = DB::table('appointments')
            ->join('doctors', 'appointments.doc_id', '=', 'doctors.doc_id')
            ->join('users', 'doctors.doc_id', '=', 'users.id')
            ->select('appointments.*', 'doctors.*', 'users.name', 'users.email', 'users.phone')
            ->where('appointments.user_id', auth()->user()->id)
            ->where('appointments.date', '>=', now())
            ->orderBy('date')
            ->orderBy('time')
            ->get();
        // previous appointments
        $previousAppointments = DB::table('appointments')
            ->join('doctors', 'appointments.doc_id', '=', 'doctors.doc_id')
            ->join('users', 'doctors.doc_id', '=', 'users.id')
            ->select('appointments.*', 'doctors.*', 'users.name', 'users.email', 'users.phone')
            ->where('appointments.user_id', auth()->user()->id)
            ->where('appointments.date', '<', now())
            ->orderBy('date')
            ->orderBy('time')
            ->get();
        return Inertia::render('Patient/Appointments',
            ['doctors' => $doctors, 'appointments' => $appointments, 'previousAppointments' => $previousAppointments]);
    }
    public function createAppointment(Request $request)
    {
       $validated = $request->validate([
            'doc_id' => 'required|exists:doctors,doc_id',
            'date' => 'required',
            'time' => 'required',
           'problems' => 'nullable',
        ]);

       // convert date to Y-m-d format
        $validated['date'] = date('Y-m-d', strtotime($validated['date']));
        //add one day to the date
        $validated['date'] = date('Y-m-d', strtotime($validated['date'] . ' +1 day'));
        DB::table('appointments')->insert([
            'user_id' => auth()->user()->id,
            'doc_id' => $validated['doc_id'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'problem' => $validated['problems'],
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
            'status_updated_at' => now(),
        ]);
        //only upcomming appointments
        $appointments = DB::table('appointments')
            ->join('doctors', 'appointments.doc_id', '=', 'doctors.doc_id')
            ->join('users', 'doctors.doc_id', '=', 'users.id')
            ->select('appointments.*', 'doctors.*', 'users.name', 'users.email', 'users.phone')
            ->where('appointments.user_id', auth()->user()->id)
            ->where('appointments.date', '>=', now())
            ->orderBy('date')
            ->orderBy('time')
            ->get();
        // past appointments
        $previousAppointments = DB::table('appointments')
            ->join('doctors', 'appointments.doc_id', '=', 'doctors.doc_id')
            ->join('users', 'doctors.doc_id', '=', 'users.id')
            ->select('appointments.*', 'doctors.*', 'users.name', 'users.email', 'users.phone')
            ->where('appointments.user_id', auth()->user()->id)
            ->where('appointments.date', '<', now())
            ->orderBy('date')
            ->orderBy('time')
            ->get();
        $doctors = DB::table('doctors')
            ->join('users', 'doctors.doc_id', '=', 'users.id')
            ->select('doctors.*', 'users.profile_photo_path', 'users.email', 'users.phone')
            ->get();
        // redirect back
        return redirect()->route('patient.appointments',
            ['doctors' => $doctors, 'appointments' => $appointments, 'previousAppointments' => $previousAppointments]);

    }
    public function consultations()
    {
        return Inertia::render('Patient/Consultations');
    }
    public function medicines()
    {
        return Inertia::render('Patient/Medicines');
    }
    public function healthRecords()
    {
        return Inertia::render('Patient/HealthRecords');
    }
    public function payments()
    {
        return Inertia::render('Patient/Payments');
    }
    public function profile()
    {
        return Inertia::render('Patient/Profile');
    }
    public function bloodBank()
    {
        return Inertia::render('Patient/BloodBank');
    }
    public function settings()
    {
        return Inertia::render('Patient/Settings');
    }

}
