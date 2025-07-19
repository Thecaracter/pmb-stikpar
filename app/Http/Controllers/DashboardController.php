<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\PaymentProof;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    private function adminDashboard()
    {
        // Statistics untuk admin
        $stats = [
            'total_registrations' => Registration::count(),
            'pending_payments' => PaymentProof::where('verification_status', 'pending')->count(),
            'waiting_documents' => Registration::where('status', 'waiting_documents')->count(),
            'passed_students' => Registration::where('status', 'passed')->count(),
            'failed_students' => Registration::where('status', 'failed')->count(),
            'waiting_decision' => Registration::where('status', 'waiting_decision')->count(),
        ];

        // Recent activities
        $recentRegistrations = Registration::with(['user', 'wave', 'path'])
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = PaymentProof::with(['registration.user'])
            ->where('verification_status', 'approved')
            ->latest()
            ->take(5)
            ->get();

        // Pending tasks
        $pendingTasks = [
            'document_review' => Registration::where('status', 'waiting_decision')->count(),
            'payment_verification' => PaymentProof::where('verification_status', 'pending')->count(),
            'form_review' => Registration::whereHas('form', function ($q) {
                $q->where('is_completed', true);
            })->where('status', 'waiting_documents')->count(),
        ];

        return view('pages.dashboard', compact('stats', 'recentRegistrations', 'recentPayments', 'pendingTasks'));
    }

    private function userDashboard()
    {
        $user = Auth::user();

        // Get user's registration
        $registration = Registration::where('user_id', $user->id)
            ->with(['wave', 'path', 'form', 'adminPayment', 'registrationPayment'])
            ->first();

        // Progress steps
        $progressSteps = $this->calculateProgressSteps($registration);

        return view('pages.dashboard', compact('registration', 'progressSteps'));
    }

    private function calculateProgressSteps($registration)
    {
        if (!$registration) {
            return [
                'account' => true,
                'admin_fee' => false,
                'upload_proof' => false,
                'fill_form' => false,
                'upload_docs' => false,
                'waiting' => false,
            ];
        }

        $steps = [
            'account' => true, // Always true if user has registration
            'admin_fee' => false,
            'upload_proof' => false,
            'fill_form' => false,
            'upload_docs' => false,
            'waiting' => false,
        ];

        // Check admin payment
        if ($registration->adminPayment && $registration->adminPayment->verification_status === 'approved') {
            $steps['admin_fee'] = true;
            $steps['upload_proof'] = true;
        }

        // Check form completion
        if ($registration->form && $registration->form->is_completed) {
            $steps['fill_form'] = true;
        }

        // Check document upload
        if ($registration->documentUploads()->count() > 0) {
            $steps['upload_docs'] = true;
        }

        // Check if waiting for decision
        if (in_array($registration->status, ['waiting_decision', 'passed', 'failed'])) {
            $steps['waiting'] = true;
        }

        return $steps;
    }
}