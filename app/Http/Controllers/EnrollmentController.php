<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Enrollment;

class EnrollmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer',
            'course_id' => 'required|integer',
        ]);

        // Validate student exists via User Service
        $userResponse = Http::get('http://localhost:8001/api/users'); // adjust port if needed
        $studentExists = collect($userResponse->json())
            ->firstWhere('id', $request->student_id);


        if (!$studentExists || $studentExists['role'] !== 'student') {
            return response()->json(['error' => 'Student not found'], 404);
        }

        // Validate course exists via Course Service
        $courseResponse = Http::get('http://localhost:8002/api/courses/' . $request->course_id);
        if (!$courseResponse->ok()) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $enrollment = Enrollment::create($request->all());
        return response()->json($enrollment, 201);
    }

    public function index()
    {
        return response()->json(Enrollment::all());
    }
}

