@extends('layouts.app')

@section('title', 'Available Cars')

@section('content')
<div class="bg-animation">
    <div class="circle" style="width:220px;height:220px;top:20%;left:15%;background:rgba(255,255,255,.12);"></div>
    <div class="circle" style="width:180px;height:180px;top:65%;left:75%;background:rgba(255,255,255,.1);"></div>
</div>

<div class="hero text-center text-white py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); position:relative; overflow:hidden;">
    <h1 class="display-5 fw-bold">Available Rental Cars</h1>
    <p class="lead opacity-85">Choose a car, pick your date and location, and book instantly.</p>
</div>

<div class="booking-bar bg-white border-bottom py-4">
    <div class="container d-flex flex-column flex-md-row gap-3">
        <div class="field w-100">
            <label class="form-label text-uppercase text-muted small">Pickup Location</label>
            <input type="text" id="location" class="form-control" placeholder="e.g. Islamabad F-10">
        </div>
        <div class="field w-100">
            <label class="form-label text-uppercase text-muted small">Booking Date</label>
            <input type="date" id="date" class="form-control">
        </div>
        <div class="field w-100">
            <label class="form-label text-uppercase text-muted small">Hours Needed</label>
            <input type="number" id="hours" class="form-control" min="1" max="24" placeholder="e.g. 3">
        </div>
    </div>
</div>

<div class="cars-section container py-5">
    <div class="section-label text-uppercase text-muted small mb-4">Select a vehicle</div>
    <div class="row g-4">
        @foreach($cars as $car)
            <div class="col-md-4">
                <div class="car-card card border-0 shadow-sm">
                    <img src="{{ asset($car['image']) }}" alt="{{ $car['name'] }}" class="card-img-top" style="height:190px;object-fit:cover;">
                    <div class="card-body d-flex flex-column">
                        <h3 class="h5">{{ $car['name'] }}</h3>
                        <p class="text-muted mb-3"><strong>Rs.{{ number_format($car['price']) }}</strong> / hour</p>
                        <div class="avail-badge mb-3 d-none" id="badge-{{ str_replace(' ', '-', $car['name']) }}"></div>
                        <button class="btn btn-dark btn-book mt-auto" onclick="checkAndBook('{{ $car['name'] }}', {{ $car['price'] }})">Check &amp; Book</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="payment-wrap container mb-5" id="paymentWrap" style="display:none;">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex align-items-center gap-3">
            <span>💳</span>
            <h3 class="h5 mb-0">Complete Your Booking</h3>
        </div>
        <div class="card-body">
            <div class="booking-summary row gx-3 gy-3 mb-4">
                <div class="col-6"><label class="form-label text-uppercase small text-muted">Car</label><div id="sum-car" class="fw-semibold">—</div></div>
                <div class="col-6"><label class="form-label text-uppercase small text-muted">Location</label><div id="sum-location" class="fw-semibold">—</div></div>
                <div class="col-6"><label class="form-label text-uppercase small text-muted">Date</label><div id="sum-date" class="fw-semibold">—</div></div>
                <div class="col-6"><label class="form-label text-uppercase small text-muted">Hours</label><div id="sum-hours" class="fw-semibold">—</div></div>
                <div class="col-12"><label class="form-label text-uppercase small text-muted">Total Amount</label><div id="sum-total" class="fw-semibold text-primary">—</div></div>
            </div>

            <div class="row gx-3 gy-3 mb-4">
                <div class="col-md-6">
                    <div class="p-3 border rounded-3">
                        <h6 class="mb-2">📱 Easypaisa</h6>
                        <p class="mb-0">0312-1234567</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded-3">
                        <h6 class="mb-2">🏦 HBL Bank</h6>
                        <p class="mb-0">ACC: 1234567890</p>
                    </div>
                </div>
            </div>

            <div class="upload-zone border border-dashed rounded-3 p-4 text-center mb-3" onclick="document.getElementById('paymentProof').click()" style="cursor:pointer;">
                <input type="file" id="paymentProof" accept="image/*,.pdf" onchange="handleFileSelect()" hidden>
                <div class="fs-3">📎</div>
                <p class="mb-0">Click to upload payment screenshot</p>
            </div>
            <div class="file-confirmed text-success fw-semibold mb-3" id="fileConfirmed" style="display:none;"></div>

            <button class="btn btn-primary w-100" id="btnConfirm" onclick="confirmBooking()">Confirm Booking</button>
        </div>
    </div>
</div>

<div class="success-overlay" id="successOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9999;align-items:center;justify-content:center;">
    <div class="card p-4 rounded-4 text-center" style="max-width:360px;">
        <div class="fs-1 mb-3">✅</div>
        <h4 class="mb-2">Booking Confirmed!</h4>
        <p class="text-muted mb-4" id="successMsg">Your car has been successfully booked.</p>
        <button class="btn btn-dark" onclick="resetAll()">Book Another</button>
    </div>
</div>

<style>
    .bg-animation { position: fixed; inset: 0; z-index: -1; overflow: hidden; }
    .circle { position: absolute; border-radius: 50%; animation: float 20s infinite; }
    @keyframes float { 0%,100%{transform:translateY(0) rotate(0deg);}50%{transform:translateY(-20px) rotate(180deg);} }
    .booking-bar { background: #fff; }
    .card-img-top { border-top-left-radius: .5rem; border-top-right-radius: .5rem; }
    .avail-badge { display: none; padding: .5rem .75rem; border-radius: 999px; font-weight: 600; }
    .avail-badge.available { background: #e8f8f1; color: #1a8a4a; border: 1px solid #a3d9be; }
    .avail-badge.unavailable { background: #fef0ee; color: #c8392b; border: 1px solid #f5b8b2; }
    .upload-zone { transition: all .2s ease; }
    .upload-zone:hover { background: #f8f9ff; }
</style>

<script>
    const CARS = @json($cars);
    const CHECK_URL = "{{ route('rentals.check') }}";
    const CONFIRM_URL = "{{ route('rentals.confirm') }}";
    const CSRF_TOKEN = document.head.querySelector('meta[name="csrf-token"]').content;

    let selectedCar = "";
    let selectedPrice = 0;
    let selectedTotal = 0;

    function checkAndBook(carName, pricePerHour) {
        const location = document.getElementById('location').value.trim();
        const date = document.getElementById('date').value;
        const hours = parseInt(document.getElementById('hours').value, 10);

        if (!location || !date || !hours || hours < 1) {
            return flashError('Please fill in Location, Date, and Hours first.');
        }

        const carKey = carName.replace(/\s+/g, '-');
        const btn = document.querySelector('#badge-' + carKey).closest('.card-body').querySelector('.btn-book');
        const badge = document.getElementById('badge-' + carKey);

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Checking…';

        const fd = new FormData();
        fd.append('car_name', carName);
        fd.append('booking_date', date);

        fetch(CHECK_URL, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: fd,
        })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                if (data.available) {
                    btn.innerHTML = '✔ Available — View Payment';
                    badge.className = 'avail-badge available d-block';
                    badge.textContent = '✔ Available on this date';

                    selectedCar = carName;
                    selectedPrice = pricePerHour;
                    selectedTotal = pricePerHour * hours;

                    document.getElementById('sum-car').textContent = carName;
                    document.getElementById('sum-location').textContent = location;
                    document.getElementById('sum-date').textContent = date;
                    document.getElementById('sum-hours').textContent = hours + ' hrs';
                    document.getElementById('sum-total').textContent = 'Rs.' + selectedTotal.toLocaleString();

                    document.getElementById('paymentWrap').style.display = 'block';
                    document.getElementById('paymentWrap').scrollIntoView({ behavior: 'smooth' });
                    resetOtherBadges(carName);
                } else {
                    btn.innerHTML = 'Check & Book';
                    badge.className = 'avail-badge unavailable d-block';
                    badge.textContent = '✖ Already booked on this date';
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = 'Check & Book';
                flashError('Network error: ' + err.message);
            });
    }

    function resetOtherBadges(currentCar) {
        CARS.forEach(car => {
            if (car.name !== currentCar) {
                const key = car.name.replace(/\s+/g, '-');
                const badge = document.getElementById('badge-' + key);
                const btn = badge.closest('.card-body').querySelector('.btn-book');
                badge.className = 'avail-badge d-none';
                btn.innerHTML = 'Check & Book';
                btn.disabled = false;
            }
        });
    }

    function handleFileSelect() {
        const input = document.getElementById('paymentProof');
        const confirmed = document.getElementById('fileConfirmed');
        if (input.files.length > 0) {
            confirmed.textContent = '✔ File selected: ' + input.files[0].name;
            confirmed.style.display = 'block';
        }
    }

    function confirmBooking() {
        const proof = document.getElementById('paymentProof').value;
        if (!proof) {
            return flashError('Please upload your payment screenshot first.');
        }

        const location = document.getElementById('location').value.trim();
        const date = document.getElementById('date').value;
        const hours = parseInt(document.getElementById('hours').value, 10);

        const btn = document.getElementById('btnConfirm');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Confirming…';

        const fd = new FormData();
        fd.append('car_name', selectedCar);
        fd.append('location', location);
        fd.append('booking_date', date);
        fd.append('hours', hours);
        fd.append('total_amount', selectedTotal);

        fetch(CONFIRM_URL, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: fd,
        })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = 'Confirm Booking';
                if (data.success) {
                    document.getElementById('successMsg').textContent = selectedCar + ' booked successfully for ' + date + '!';
                    document.getElementById('successOverlay').style.display = 'flex';
                } else {
                    flashError(data.message || 'Booking failed. Please try again.');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = 'Confirm Booking';
                flashError('Network error: ' + err.message);
            });
    }

    function resetAll() {
        document.getElementById('successOverlay').style.display = 'none';
        document.getElementById('paymentWrap').style.display = 'none';
        document.getElementById('location').value = '';
        document.getElementById('date').value = '';
        document.getElementById('hours').value = '';
        document.getElementById('paymentProof').value = '';
        document.getElementById('fileConfirmed').style.display = 'none';

        CARS.forEach(car => {
            const key = car.name.replace(/\s+/g, '-');
            const badge = document.getElementById('badge-' + key);
            const btn = badge.closest('.card-body').querySelector('.btn-book');
            badge.className = 'avail-badge d-none';
            btn.innerHTML = 'Check & Book';
            btn.disabled = false;
        });

        selectedCar = '';
        selectedPrice = 0;
        selectedTotal = 0;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function flashError(msg) {
        const el = document.createElement('div');
        el.style.cssText = 'position:fixed;bottom:1.5rem;left:50%;transform:translateX(-50%);background:#c8392b;color:#fff;padding:.75rem 1.25rem;border-radius:12px;font-weight:600;z-index:9999;box-shadow:0 10px 25px rgba(0,0,0,.18);max-width:90vw;';
        el.textContent = msg;
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 5000);
    }
</script>
@endsection
