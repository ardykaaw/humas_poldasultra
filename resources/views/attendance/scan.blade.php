<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Absensi - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/Logo_Humas_Polri.svg.png') }}" alt="Logo" style="width: 80px; height: 80px; object-fit: contain;" class="mb-3">
                            <h4 class="card-title">Form Absensi</h4>
                            <p class="text-muted">{{ now()->format('l, d F Y') }}</p>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <form action="{{ route('attendance.process-scan', $code) }}" method="POST" id="attendanceForm">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NIP</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->nip }}" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pangkat</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->pangkat }}" readonly>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jabatan</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->jabatan }}" readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Lokasi</label>
                                <input type="text" name="location" class="form-control" required placeholder="Masukkan lokasi Anda">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Tanda Tangan Digital</label>
                                <div class="border rounded p-3">
                                    <canvas id="signatureCanvas" class="w-100" style="height: 200px; border: 1px solid #dee2e6;"></canvas>
                                    <input type="hidden" name="signature" id="signatureInput">
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-secondary" id="clearSignature">
                                            <i class="bi bi-eraser me-2"></i>
                                            Clear
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Submit Absensi
                                </button>
                                <a href="{{ route('attendance.daily') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('signatureCanvas');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)'
        });
        
        // Resize canvas
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }
        
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();
        
        // Clear signature
        document.getElementById('clearSignature').addEventListener('click', function() {
            signaturePad.clear();
        });
        
        // Submit form
        document.getElementById('attendanceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (signaturePad.isEmpty()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Mohon isi tanda tangan Anda'
                });
                return false;
            }
            
            document.getElementById('signatureInput').value = signaturePad.toDataURL();
            
            // Create a temporary form
            const tempForm = document.createElement('form');
            tempForm.method = 'POST';
            tempForm.action = this.action;
            tempForm.style.display = 'none';

            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
            tempForm.appendChild(csrfInput);

            // Add location
            const locationInput = document.createElement('input');
            locationInput.type = 'hidden';
            locationInput.name = 'location';
            locationInput.value = document.querySelector('input[name="location"]').value;
            tempForm.appendChild(locationInput);

            // Add signature
            const signatureInput = document.createElement('input');
            signatureInput.type = 'hidden';
            signatureInput.name = 'signature';
            signatureInput.value = document.getElementById('signatureInput').value;
            tempForm.appendChild(signatureInput);

            // Add form to body and submit
            document.body.appendChild(tempForm);
            tempForm.submit();
        });
    });
    </script>
</body>
</html> 