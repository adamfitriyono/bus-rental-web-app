<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use App\Models\Pemesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    /**
     * Generate ticket after payment success
     * Format kode: KJ-YYYYMMDD-XXXXX
     */
    public function generateTicket($pemesanan_id)
    {
        $pemesanan = Pemesanan::with(['jadwal.rute', 'bus', 'penumpangs'])->findOrFail($pemesanan_id);
        
        // Generate kode tiket: KJ-YYYYMMDD-XXXXX
        $dateCode = Carbon::now()->format('Ymd');
        $randomCode = str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $kodeTiket = 'KJ-' . $dateCode . '-' . $randomCode;

        // Generate QR Code (using simple-qrcode library)
        // $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)->generate($kodeTiket);
        // For now, store as placeholder
        $qrCode = base64_encode('QR_CODE_PLACEHOLDER_' . $kodeTiket);

        // Create tiket record
        $tiket = Tiket::create([
            'kode_tiket' => $kodeTiket,
            'id_pemesanan' => $pemesanan_id,
            'qr_code' => $qrCode,
            'status_tiket' => 'aktif'
        ]);

        // Generate PDF (will be implemented with TCPDF)
        $this->generatePDF($tiket->id_tiket);

        // Send email
        $this->sendEmail($tiket->id_tiket);

        return redirect()->route('ticket.view', $kodeTiket)
                         ->with('success', 'Tiket berhasil dibuat');
    }

    /**
     * Generate QR Code
     */
    public function generateQRCode($kode_tiket)
    {
        // Using simple-qrcode or endroid/qr-code
        // For now, return placeholder
        return response($kode_tiket, 200)
                  ->header('Content-Type', 'image/png');
    }

    /**
     * Generate PDF e-ticket using TCPDF
     */
    public function generatePDF($tiket_id)
    {
        $tiket = Tiket::with(['pemesanan.jadwal.rute', 'pemesanan.bus', 'pemesanan.penumpangs'])->findOrFail($tiket_id);
        
        // TODO: Implement TCPDF generation
        // For now, store path or base64
        $pdfContent = 'PDF_PLACEHOLDER';
        
        $tiket->update([
            'file_pdf' => base64_encode($pdfContent)
        ]);

        return $tiket;
    }

    /**
     * Send email with e-ticket attachment
     */
    public function sendEmail($tiket_id)
    {
        $tiket = Tiket::with(['pemesanan.user', 'pemesanan.jadwal.rute'])->findOrFail($tiket_id);
        
        // TODO: Implement email sending with PHPMailer
        // Send email dengan attachment PDF
        
        return true;
    }

    /**
     * Download ticket PDF
     */
    public function downloadTicket($kode_tiket)
    {
        $tiket = Tiket::with(['pemesanan'])->where('kode_tiket', $kode_tiket)->firstOrFail();
        
        // Check if user owns this ticket
        if (auth()->check() && $tiket->pemesanan->id_user !== auth()->id()) {
            abort(403);
        }

        // Return PDF file
        $pdfContent = base64_decode($tiket->file_pdf);
        
        return response($pdfContent)
                  ->header('Content-Type', 'application/pdf')
                  ->header('Content-Disposition', 'attachment; filename="tiket-' . $kode_tiket . '.pdf"');
    }

    /**
     * View ticket in browser
     */
    public function viewTicket($kode_tiket)
    {
        $tiket = Tiket::with(['pemesanan.jadwal.rute', 'pemesanan.bus', 'pemesanan.penumpangs'])->where('kode_tiket', $kode_tiket)->firstOrFail();
        
        // Check if user owns this ticket or is admin
        if (auth()->check()) {
            if ($tiket->pemesanan->id_user !== auth()->id() && auth()->user()->role == 0) {
                abort(403);
            }
        }

        return view('ticket', [
            'tiket' => $tiket
        ]);
    }
}
